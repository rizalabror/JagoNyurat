<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\SuratModel;
use App\Models\IdentityModel;
use App\Models\OrganisasiModel;
use App\Models\KepengurusanModel;
use App\Models\NotificationModel;
use Myth\Auth\Models\UserModel;
use Myth\Auth\Entities\User;
use Myth\Auth\Authorization\GroupModel;

class SekumController extends BaseController
{
    protected $suratModel;

    public function __construct()
    {
        $this->suratModel = new SuratModel();
        // Muat helper penomoran surat terpusat
        helper('penomoran');
    }

    /**
     * Dashboard Sekum — halaman beranda manajerial setelah login.
     */
    public function dashboard(): string
    {
        // Ambil kepengurusan aktif milik Sekum yang sedang login
        $currentKepengurusanId = user()->kepengurusan_id;

        $allSurat = $this->suratModel
            ->select('surat.*, program_kerja.nama as nama_proker, program_kerja.public_id as proker_public_id, kategori_template.nama_kategori, users.fullname as pembuat_surat')
            ->join('program_kerja', 'program_kerja.id = surat.proker_id', 'left')
            ->join('kategori_template', 'kategori_template.id = surat.kategori_id', 'left')
            ->join('users', 'users.id = surat.user_id', 'left')
            ->where('program_kerja.kepengurusan_id', $currentKepengurusanId)
            ->orderBy('surat.updated_at', 'DESC')
            ->findAll();

        $data['count_pending'] = 0;
        $data['count_revisi']  = 0;
        $data['count_approved']= 0;

        foreach ($allSurat as $s) {
            if ($s['status'] === 'Pending') $data['count_pending']++;
            elseif ($s['status'] === 'Rejected') $data['count_revisi']++;
            elseif ($s['status'] === 'Approved') $data['count_approved']++;
        }

        $prokersGrouped = [];
        foreach ($allSurat as $s) {
            $prokerId = $s['proker_id'] ?: 'non_proker';
            if (!isset($prokersGrouped[$prokerId])) {
                $prokersGrouped[$prokerId] = [
                    'id'             => $prokerId,
                    'public_id'      => $s['proker_public_id'] ?? '',
                    'nama'           => $s['nama_proker'] ?: 'Surat Umum / Lainnya',
                    'kategori_utama' => $s['nama_kategori'] ?: 'Departemen',
                    'stats'          => ['pending' => 0, 'revisi' => 0, 'approved' => 0],
                    'surat_list'     => []
                ];
            }
            if ($s['status'] === 'Pending')  $prokersGrouped[$prokerId]['stats']['pending']++;
            if ($s['status'] === 'Rejected') $prokersGrouped[$prokerId]['stats']['revisi']++;
            if ($s['status'] === 'Approved') $prokersGrouped[$prokerId]['stats']['approved']++;
            if (count($prokersGrouped[$prokerId]['surat_list']) < 5) {
                $prokersGrouped[$prokerId]['surat_list'][] = $s;
            }
        }

        uasort($prokersGrouped, function ($a, $b) {
            $aActive = ($a['stats']['pending'] + $a['stats']['revisi']) > 0 ? 1 : 0;
            $bActive = ($b['stats']['pending'] + $b['stats']['revisi']) > 0 ? 1 : 0;
            return $bActive - $aActive;
        });

        $data['grouped_proker'] = $prokersGrouped;
        $data['allSurat'] = $allSurat;

        // ============================================================
        // DATA STATISTIK PER PROKER — untuk ApexCharts horizontal bar
        // ============================================================
        $chartLabels   = [];
        $chartPending  = [];
        $chartRejected = [];
        $chartApproved = [];

        foreach ($prokersGrouped as $proker) {
            $nama = strlen($proker['nama']) > 22
                ? substr($proker['nama'], 0, 20) . '…'
                : $proker['nama'];
            $chartLabels[]   = $nama;
            $chartPending[]  = (int) $proker['stats']['pending'];
            $chartRejected[] = (int) $proker['stats']['revisi'];
            $chartApproved[] = (int) $proker['stats']['approved'];
        }

        $data['chartLabels']   = json_encode($chartLabels);
        $data['chartPending']  = json_encode($chartPending);
        $data['chartRejected'] = json_encode($chartRejected);
        $data['chartApproved'] = json_encode($chartApproved);

        return view('Sekum/dashboard', $data);

    }

    /**
     * Log Aktivitas — halaman semua aktivitas surat (full history).
     */
    public function aktivitas(): string
    {
        $statusFilter = $this->request->getGet('status') ?? 'all';
        $page         = (int) ($this->request->getGet('page') ?? 1);
        $perPage      = 20;

        $currentKepengurusanId = user()->kepengurusan_id;

        $builder = $this->suratModel
            ->select('surat.*, program_kerja.nama as nama_proker, users.fullname as pembuat_surat')
            ->join('program_kerja', 'program_kerja.id = surat.proker_id', 'left')
            ->join('users', 'users.id = surat.user_id', 'left')
            ->where('program_kerja.kepengurusan_id', $currentKepengurusanId)
            ->orderBy('surat.updated_at', 'DESC');

        if ($statusFilter !== 'all') {
            $builder->where('surat.status', ucfirst($statusFilter));
        }

        $totalRows  = $builder->countAllResults(false);
        $totalPages = (int) ceil($totalRows / $perPage);
        $offset     = ($page - 1) * $perPage;

        $logs = $builder->findAll($perPage, $offset);

        return view('Sekum/aktivitas', [
            'logs'         => $logs,
            'statusFilter' => $statusFilter,
            'currentPage'  => $page,
            'totalPages'   => $totalPages,
            'totalRows'    => $totalRows,
        ]);
    }

    // =====================================================
    // MANAJEMEN AKUN KEPANITIAAN (Dipindahkan dari Admin.php)
    // =====================================================

    public function userList()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('users');
        $builder->select('users.id as userid, fullname, npm, email, auth_groups.name');
        $builder->join('auth_groups_users', 'auth_groups_users.user_id = users.id');
        $builder->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id');
        $builder->whereIn('auth_groups.name', ['Sekpel', 'Sekum']);
        $builder->where('users.active', 1); // Hanya tampilkan user yang masih aktif
        return view('Sekum/Users/user-list', ['title' => 'User List', 'users' => $builder->get()->getResult()]);
    }

    public function addUser()
    {
        return view('Sekum/Users/add-user');
    }

    public function register()
    {
        $userModel = new UserModel();
        if (!$this->validate([
            'fullname'     => 'required|min_length[3]|is_unique[users.fullname]',
            'email'        => 'required|valid_email|is_unique[users.email]',
            'password'     => 'required|min_length[8]',   // ✅ FIX #5: min 8 karakter
            'pass_confirm' => 'required|matches[password]',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $data = $this->request->getPost();
        $user = new User([
            'fullname' => $data['fullname'],
            'npm'      => $data['npm'],
            'email'    => $data['email'],
            'password' => $data['password'],
            'active'   => 1,
        ]);
        $userModel->save($user);
        service('authorization')->addUserToGroup($userModel->getInsertID(), 'Sekpel');
        return redirect()->to('sekum/users')->with('success', 'User Kepanitiaan (Sekpel) berhasil didaftarkan.');
    }

    public function editUser($id)
    {
        $userModel = new UserModel();
        $builder   = $userModel->builder();
        $builder->select('users.id as userid, npm, email, fullname, name');
        $builder->join('auth_groups_users', 'auth_groups_users.user_id = users.id');
        $builder->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id');
        $builder->where('users.id', $id);
        $user = $builder->get()->getRow();
        if (!$user) {
            return redirect()->to(base_url('/'))->with('error', 'User not found');
        }
        return view('Sekum/Users/edit-user', ['user' => $user]);
    }

    public function updateUser($id)
    {
        $userModel = new UserModel();
        $user      = $userModel->find($id);
        if (!$this->validate([
            'fullname' => 'permit_empty|min_length[3]',
            'npm'      => 'permit_empty|numeric',
            'email'    => 'permit_empty|valid_email',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $data = [];
        if ($this->request->getPost('fullname') !== $user->fullname) $data['fullname'] = $this->request->getPost('fullname');
        if ($this->request->getPost('npm')      !== $user->npm)      $data['npm']      = $this->request->getPost('npm');
        $newEmail = $this->request->getPost('email');
        if ($newEmail && $newEmail !== $user->email) {
            if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
                return redirect()->back()->withInput()->with('errors', ['email' => 'Invalid email address']);
            }
            $data['email'] = $newEmail;
        }
        if (empty($data)) return redirect()->to(base_url('sekum/users'))->with('success', 'Tidak ada perubahan.');
        if ($userModel->update($id, $data)) {
            return redirect()->to(base_url('sekum/users'))->with('success', 'User berhasil diperbarui.');
        }
        return redirect()->back()->with('error', 'Gagal memperbarui user.');
    }

    public function deleteUser($id)
    {
        $userModel = new UserModel();
        $user      = $userModel->find($id);
        if (!$user) return redirect()->back()->with('error', 'User not found');
        if ($userModel->delete($id, true)) {
            return redirect()->to(base_url('sekum/users'))->with('success', 'User berhasil dihapus.');
        }
        return redirect()->back()->with('error', 'Gagal menghapus user.');
    }

    // =====================================================

    // Menampilkan Halaman Arsip Seluruh Surat per Proker
    public function detailProker($uuid)
    {
        $prokerModel = new \App\Models\ProgramKerjaModel();
        $proker = $prokerModel->where('public_id', $uuid)->first();

        if (!$proker) {
            return redirect()->to(base_url('admin'))->with('error', 'Kepanitiaan tersebut tidak ditemukan di sistem.');
        }

        // Ambil SEMUA riwayat surat dari yang berstatus draft hingga disetujui (tanpa batas limit baris)
        $suratList = $this->suratModel->select('surat.*, users.fullname as pembuat_surat, kategori_template.nama_kategori')
                                      ->join('users', 'users.id = surat.user_id', 'left')
                                      ->join('kategori_template', 'kategori_template.id = surat.kategori_id', 'left')
                                      ->where('surat.proker_id', $proker['id'])
                                      ->orderBy('surat.updated_at', 'DESC')
                                      ->findAll();

        // Hitung ulang statistik persentase surat untuk dipamerkan di header halaman detail
        $stats = ['pending' => 0, 'revisi' => 0, 'approved' => 0, 'draft' => 0];
        foreach ($suratList as $s) {
            if ($s['status'] == 'Pending') $stats['pending']++;
            elseif ($s['status'] == 'Rejected') $stats['revisi']++;
            elseif ($s['status'] == 'Approved') $stats['approved']++;
            elseif ($s['status'] == 'Draft') $stats['draft']++;
        }

        $data = [
            'title'     => 'Ruang Arsip Proker: ' . $proker['nama'],
            'proker'    => $proker,
            'suratList' => $suratList,
            'stats'     => $stats
        ];

        return view('Sekum/detail_proker', $data);
    }

    // Menampilkan Detail Tinjauan Surat (Preview Mode)
    public function review($uuid)
    {
        // Join dengan tabel user (sekpel pembuat), proker, dan kategori
        $surat = $this->suratModel->select('surat.*, program_kerja.nama as nama_proker, program_kerja.kode_proker, program_kerja.singkatan, kategori_template.nama_kategori, users.fullname as nama_pembuat, users.npm as npm_pembuat')
                                  ->join('program_kerja', 'program_kerja.id = surat.proker_id', 'left')
                                  ->join('kategori_template', 'kategori_template.id = surat.kategori_id', 'left')
                                  ->join('users', 'users.id = surat.user_id', 'left')
                                  ->where('surat.public_id', $uuid)
                                  ->first();

        if (!$surat || $surat['status'] != 'Pending') {
            return redirect()->to(base_url('/'))->with('error', 'Surat tidak ditemukan atau tidak sedang dalam status Pending Review.');
        }

        // Generate nomor surat otomatis sesuai SOP
        $suggestedNomor = generate_nomor_surat($surat['proker_id'] ?? null);

        $organisasiModel = new OrganisasiModel();
        $bem = $organisasiModel->where('organisasi', 'BEM')->first();

        $identityModel = new IdentityModel();
        
        // Ambil data pejabat penandatangan (berdasarkan pilihan simpanan Sekpel)
        $ttd_bem = !empty($surat['ttd_ketua_bem']) ? $identityModel->where('jabatan', $surat['ttd_ketua_bem'])->first() : null;
        $ttd_dekan = !empty($surat['ttd_dekan']) ? $identityModel->where('jabatan', $surat['ttd_dekan'])->first() : null;

        // Surat elektronik: auto-inject TTD Ketua BEM dari identitas (tidak perlu dipilih Sekpel)
        if (($surat['jenis_surat'] ?? '') === 'elektronik' && empty($ttd_bem)) {
            $ttd_bem = $identityModel->where('jabatan', 'Ketua BEM Fasilkom')->first();
        }

        // Ambil Data Kop Deskriptif
        $kop1 = $identityModel->where('jabatan', 'Kementerian')->first();
        $kop2 = $identityModel->where('jabatan', 'Universitas')->first();
        $kop3 = $identityModel->where('jabatan', 'Organisasi')->first();
        $kop4 = $identityModel->where('jabatan', 'Fakultas')->first();

        $prokerModel = new \App\Models\ProgramKerjaModel();
        $proker = $prokerModel->find($surat['proker_id']);

        $data = [
            'title'           => 'Tinjauan Surat Kepanitiaan - Sekum',
            'surat'           => $surat,
            'proker'          => $proker,
            'suggested_nomor' => $suggestedNomor,
            'bem'             => $bem,
            'ttd_bem'         => $ttd_bem,
            'ttd_dekan'       => $ttd_dekan,
            'kop_extra'       => [
                'baris1' => $kop1['nama'] ?? 'KEMENTERIAN PENDIDIKAN TINGGI, SAINS, DAN TEKNOLOGI',
                'baris2' => $kop2['nama'] ?? 'UNIVERSITAS SINGAPERBANGSA KARAWANG',
                'baris3' => $kop3['nama'] ?? 'BADAN EKSEKUTIF MAHASISWA',
                'baris4' => $kop4['nama'] ?? 'FAKULTAS ILMU KOMPUTER',
            ]
        ];

        return view('Sekum/Surat/review', $data);
    }

    // Method untuk Menerima Surat dan Membubuhkan Nomor Resmi
    public function approve($uuid)
    {
        $surat = $this->suratModel->findByPublicId($uuid);
        if (!$surat || $surat['status'] != 'Pending') {
            return redirect()->to(base_url('/'))->with('error', 'Status surat tidak valid.');
        }

        $nomorSurat = trim($this->request->getPost('nomor_surat') ?? '');

        if (empty($nomorSurat)) {
            // Jika kosong, generate otomatis (fallback)
            $nomorSurat = generate_nomor_surat($surat['proker_id'] ?? null);
        }

        // ✅ FIX #4: Validasi format nomor surat — hanya karakter resmi yang diizinkan
        // Format valid: angka, huruf, slash (/), titik (.), strip (-), dan spasi
        if (!preg_match('/^[\d\w\s\/\.\-]+$/u', $nomorSurat)) {
            return redirect()->back()->with('error', 'Format nomor surat tidak valid. Hanya boleh mengandung angka, huruf, garis miring (/), titik (.), dan strip (-).');
        }

        if (strlen($nomorSurat) > 100) {
            return redirect()->back()->with('error', 'Nomor surat terlalu panjang (maksimal 100 karakter).');
        }

        // Update status menjadi Approved & masukkan nomor suratnya
        $this->suratModel->update($surat['id'], [
            'status'      => 'Approved',
            'nomor_surat' => $nomorSurat
        ]);

        // Kirim notifikasi ke Sekpel (pembuat surat)
        $notifModel = new NotificationModel();
        $notifModel->send(
            (int) $surat['user_id'],
            'surat_approved',
            'Surat "' . $surat['perihal'] . '" telah DISETUJUI dengan nomor: ' . $nomorSurat,
            $surat['public_id']
        );

        return redirect()->to(base_url('/'))->with('success', 'Surat Telah BERHASIL disetujui dengan Nomor: ' . $nomorSurat);
    }

    // Method untuk Menolak Surat dengan Catatan Revisi
    public function reject($uuid)
    {
        $surat = $this->suratModel->findByPublicId($uuid);
        if (!$surat || $surat['status'] != 'Pending') {
            return redirect()->to(base_url('/'))->with('error', 'Status surat tidak valid.');
        }

        $catatanRevisi = $this->request->getPost('catatan_revisi');

        if (empty(trim($catatanRevisi))) {
            return redirect()->back()->with('error', 'Catatan revisi wajib diisi jika surat ditolak/revisi.');
        }

        // Ubah status ke Rejected dan bubuhkan alasannya (Agar bisa diedit lagi sama Sekpel)
        $this->suratModel->update($surat['id'], [
            'status' => 'Rejected',
            'catatan_revisi' => $catatanRevisi
        ]);

        // Kirim notifikasi ke Sekpel (pembuat surat)
        $notifModel = new NotificationModel();
        $notifModel->send(
            (int) $surat['user_id'],
            'surat_rejected',
            'Surat "' . $surat['perihal'] . '" perlu DIREVISI. Catatan: ' . mb_substr($catatanRevisi, 0, 80) . (mb_strlen($catatanRevisi) > 80 ? '...' : ''),
            $surat['public_id']
        );

        return redirect()->to(base_url('/'))->with('success', 'Surat berhasil dikembalikan ke Sekpel dengan Catatan Revisi.');
    }

    // Method Preview Surat (Dialog Cetak)
    public function printSurat($uuid)
    {
        $surat = $this->suratModel->select('surat.*, program_kerja.nama as nama_proker, kategori_template.nama_kategori, users.fullname as nama_pembuat, users.npm as npm_pembuat')
                                  ->join('program_kerja', 'program_kerja.id = surat.proker_id', 'left')
                                  ->join('kategori_template', 'kategori_template.id = surat.kategori_id', 'left')
                                  ->join('users', 'users.id = surat.user_id', 'left')
                                  ->where('surat.public_id', $uuid)
                                  ->first();

        if (!$surat || ($surat['status'] != 'Pending' && $surat['status'] != 'Approved')) {
            return redirect()->to(base_url('/'))->with('error', 'Akses surat ditolak.');
        }

        $organisasiModel = new OrganisasiModel();
        $bem = $organisasiModel->where('organisasi', 'BEM')->first();

        $identityModel = new IdentityModel();
        
        // Ambil data pejabat penandatangan HANYA JIKA dipilih di surat oleh Sekpel
        $ttd_bem = !empty($surat['ttd_ketua_bem']) ? $identityModel->where('jabatan', $surat['ttd_ketua_bem'])->first() : null;
        $ttd_dekan = !empty($surat['ttd_dekan']) ? $identityModel->where('jabatan', $surat['ttd_dekan'])->first() : null;

        // Surat elektronik: auto-inject TTD Ketua BEM dari identitas (tidak perlu dipilih Sekpel)
        if (($surat['jenis_surat'] ?? '') === 'elektronik' && empty($ttd_bem)) {
            $ttd_bem = $identityModel->where('jabatan', 'Ketua BEM Fasilkom')->first();
        }

        // Ambil data Program Kerja (untuk identitas ketua pelaksana dll)
        $prokerModel = new \App\Models\ProgramKerjaModel();
        $proker = $prokerModel->find($surat['proker_id']);

        // Ambil Data Kop Deskriptif
        $kop1 = $identityModel->where('jabatan', 'Kementerian')->first();
        $kop2 = $identityModel->where('jabatan', 'Universitas')->first();
        $kop3 = $identityModel->where('jabatan', 'Organisasi')->first();
        $kop4 = $identityModel->where('jabatan', 'Fakultas')->first();

        $data = [
            'title'     => 'Cetak Surat - JagoNyurat',
            'surat'     => $surat,
            'bem'       => $bem,
            'proker'    => $proker,
            'ttd_bem'   => $ttd_bem,
            'ttd_dekan' => $ttd_dekan,
            'kop_extra' => [
                'baris1' => $kop1['nama'] ?? 'KEMENTERIAN PENDIDIKAN TINGGI, SAINS, DAN TEKNOLOGI',
                'baris2' => $kop2['nama'] ?? 'UNIVERSITAS SINGAPERBANGSA KARAWANG',
                'baris3' => $kop3['nama'] ?? 'BADAN EKSEKUTIF MAHASISWA',
                'baris4' => $kop4['nama'] ?? 'FAKULTAS ILMU KOMPUTER',
            ],
            'is_preview' => $this->request->getGet('preview') ? true : false
        ];

        return view('Sekpel/Surat/cetak', $data);
    }

    // --- FITUR MANAJEMEN IDENTITAS & TEMPLATE ---

    public function identitas()
    {
        $idModel = new IdentityModel();
        $kopModel = new OrganisasiModel();

        // [AUTO-SEEDER] Memastikan data identitas awal ada di database
        $defaultKops = [
            ['jabatan' => 'Kementerian', 'nama' => 'KEMENTERIAN PENDIDIKAN TINGGI, SAINS, DAN TEKNOLOGI'],
            ['jabatan' => 'Universitas', 'nama' => 'UNIVERSITAS SINGAPERBANGSA KARAWANG'],
            ['jabatan' => 'Organisasi', 'nama' => 'BADAN EKSEKUTIF MAHASISWA'],
            ['jabatan' => 'Fakultas',  'nama' => 'FAKULTAS ILMU KOMPUTER'],
            ['jabatan' => 'Ketua BEM Fasilkom', 'nama' => 'Nama Ketua BEM'],
            ['jabatan' => 'Dekan Fasilkom',     'nama' => 'Nama Dekan'],
        ];

        foreach ($defaultKops as $dkop) {
            $check = $idModel->where('jabatan', $dkop['jabatan'])->first();
            if (!$check) {
                $idModel->insert($dkop);
            }
        }

        $data = [
            'title'      => 'Pengaturan Identitas & Kop Surat',
            // Kita ambil secara spesifik urutannya agar di view rapi (Kemen -> Univ -> Org -> Fak)
            'kop_texts'  => [
                $idModel->where('jabatan', 'Kementerian')->first(),
                $idModel->where('jabatan', 'Universitas')->first(),
                $idModel->where('jabatan', 'Organisasi')->first(),
                $idModel->where('jabatan', 'Fakultas')->first(),
            ],
            'pejabat'    => [
                $idModel->where('jabatan', 'Ketua BEM Fasilkom')->first(),
                $idModel->where('jabatan', 'Dekan Fasilkom')->first(),
            ],
            'kop_kontak' => $kopModel->where('organisasi', 'BEM')->first()
        ];

        return view('Sekum/identitas', $data);
    }

    public function uploadTtd()
    {
        $jabatan = $this->request->getPost('jabatan');
        if ($jabatan !== 'Ketua BEM Fasilkom') {
            return redirect()->back()->with('error', 'Jabatan tidak valid untuk upload TTD.');
        }

        $file = $this->request->getFile('ttd_file');
        if (!$file || !$file->isValid() || $file->hasMoved()) {
            return redirect()->back()->with('error', 'File tidak valid atau gagal diunggah.');
        }

        // ✅ FIX #6 (Layer 1): Validasi ekstensi file dari nama asli klien
        $allowedExt  = ['png', 'jpg', 'jpeg', 'gif'];
        $clientExt   = strtolower($file->getClientExtension());
        if (!in_array($clientExt, $allowedExt)) {
            return redirect()->back()->with('error', 'Ekstensi file tidak diizinkan. Gunakan PNG, JPG, atau GIF.');
        }

        // ✅ FIX #6 (Layer 2): Validasi MIME type dari ISI FILE (magic bytes) — tidak bisa dipalsukan
        $allowedMime = ['image/png', 'image/jpeg', 'image/gif'];
        $finfo       = new \finfo(FILEINFO_MIME_TYPE);
        $realMime    = $finfo->file($file->getTempName());
        if (!in_array($realMime, $allowedMime)) {
            return redirect()->back()->with('error', 'Isi file tidak sesuai format gambar yang valid. Upload ditolak.');
        }

        if ($file->getSize() > 1048576) {
            return redirect()->back()->with('error', 'Ukuran file maksimal 1MB.');
        }

        $idModel     = new IdentityModel();
        $existing    = $idModel->where('jabatan', $jabatan)->first();

        // Gunakan ekstensi yang sudah divalidasi, bukan dari getExtension() bawaan
        $newFileName = 'ttd_ketua_bem_' . time() . '.' . $clientExt;

        // Hapus file lama jika ada
        if (!empty($existing['ttd'])) {
            $oldPath = FCPATH . 'uploads/ttd/' . $existing['ttd'];
            if (file_exists($oldPath)) @unlink($oldPath);
        }

        // Pindahkan file ke folder uploads/ttd
        $file->move(FCPATH . 'uploads/ttd/', $newFileName);

        // Update langsung via DB builder (hindari masalah primary key di IdentityModel)
        $db = \Config\Database::connect();
        $db->table('identity')->where('jabatan', $jabatan)->update(['ttd' => $newFileName]);

        return redirect()->to(base_url('sekum/pengaturan'))->with('success', 'Tanda tangan Ketua BEM berhasil diunggah!');
    }

    public function updateKop()
    {
        // ✅ FIX #7: Validasi semua input sebelum disimpan ke database
        if (!$this->validate([
            'nohp'    => 'permit_empty|numeric|max_length[15]',
            'email'   => 'permit_empty|valid_email|max_length[100]',
            'website' => 'permit_empty|valid_url|max_length[255]',
            'sekretariat' => 'permit_empty|max_length[500]',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $kopModel = new OrganisasiModel();

        $data = [
            'sekretariat' => $this->request->getPost('sekretariat'),
            'nohp'        => $this->request->getPost('nohp'),
            'email'       => $this->request->getPost('email'),
            'website'     => $this->request->getPost('website'),
        ];

        $kopModel->update('BEM', $data);

        return redirect()->back()->with('success', 'Data Kontak Kop Surat berhasil diperbarui.');
    }

    // --- LOGIKA PENOMORAN OTOMATIS ---
    // Fungsi ini telah dipindahkan ke app/Helpers/penomoran_helper.php
    // Gunakan: generate_nomor_surat($prokerId) dari mana saja.

    // =========================================================
    // PREPARASI DEMISIONER: Buat Akun Sekum Penerus
    // =========================================================
    public function buatSekumPenerus()
    {
        // Rate Limiting: Maksimal 3 kali percobaan per menit
        $throttler = \Config\Services::throttler();
        if ($throttler->check(md5($this->request->getIPAddress()) . '_buat_sekum', 3, MINUTE) === false) {
            return redirect()->back()->with('error', 'Terlalu banyak permintaan pembuatan akun. Silakan tunggu 1 menit.');
        }

        $email         = $this->request->getPost('email_sekum_baru');
        $password      = $this->request->getPost('password_sekum_baru');
        $tahunPeriode  = $this->request->getPost('tahun_periode_baru');

        if (!$this->validate([
            'email_sekum_baru'    => [
                'rules'  => 'required|valid_email|is_unique[users.email]',
                'errors' => [
                    'is_unique' => 'Alamat email ini sudah terdaftar! Silakan gunakan email lain.'
                ]
            ],
            'password_sekum_baru' => 'required|min_length[6]',
            'tahun_periode_baru'  => 'required',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $db = \Config\Database::connect();

        // 1. Buat periode kepengurusan baru di tabel kepengurusan
        $kepengurusanModel = new KepengurusanModel();
        $kepengurusanModel->insert([
            'tahun_periode'   => $tahunPeriode,
            'tanggal_mulai'   => date('Y-m-d'),
            'tanggal_selesai' => null,
            'is_active'       => 1,
        ]);
        $newKepengurusanId = $kepengurusanModel->getInsertID();

        // 2. Buat akun Sekum baru LANGSUNG via Query Builder.
        //    Bypass UserModel sepenuhnya untuk menghindari allowedFields & validasi senyap.
        //    Hash password menggunakan format Myth/Auth: sha384 binary -> base64 -> bcrypt
        $passwordHash = password_hash(
            base64_encode(hash('sha384', $password, true)),
            PASSWORD_DEFAULT
        );

        $inserted = $db->table('users')->insert([
            'email'                => $email,
            'fullname'             => 'Sekum ' . $tahunPeriode,
            'npm'                  => '',
            'user_image'           => 'default.svg',
            'password_hash'        => $passwordHash,
            'active'               => 1,
            'is_profile_completed' => 0,
            'kepengurusan_id'      => $newKepengurusanId,
            'created_at'           => date('Y-m-d H:i:s'),
            'updated_at'           => date('Y-m-d H:i:s'),
        ]);

        if (!$inserted) {
            // Rollback: hapus kepengurusan yang sudah terlanjur dibuat
            $kepengurusanModel->delete($newKepengurusanId, true);
            log_message('error', '[Demisioner] Gagal insert user Sekum ke database.');
            return redirect()->back()->withInput()->with('error', 'Gagal membuat akun Sekum baru. Silakan coba lagi.');
        }

        $newUserId = $db->insertID();

        // 3. Ikat ke role Sekum
        $group = $db->table('auth_groups')->where('name', 'Sekum')->get()->getRowArray();
        if ($group) {
            $db->table('auth_groups_users')->insert([
                'group_id' => $group['id'],
                'user_id'  => $newUserId,
            ]);
        }

        return redirect()->back()->with('success', 'Akun Sekum penerus untuk periode ' . $tahunPeriode . ' berhasil dibuat! Sekarang Anda sudah siap menjalankan Demisioner.');
    }

    // =========================================================
    // EKSEKUSI DEMISIONER: Nonaktifkan Kepengurusan Saat Ini
    // =========================================================
    public function demisionerKepengurusan()
    {
        $konfirmasi = $this->request->getPost('konfirmasi_reset');

        if ($konfirmasi !== 'DEMISIONER') {
            return redirect()->back()->with('error', 'Kata kunci konfirmasi salah. Proses demisioner dibatalkan.');
        }

        $kepengurusanModel = new KepengurusanModel();
        $db = \Config\Database::connect();

        try {
            // 1. Temukan kepengurusan yang sedang aktif milik Sekum yang login
            $currentUser = user();
            $currentKepengurusanId = $currentUser->kepengurusan_id;

            if (!$currentKepengurusanId) {
                return redirect()->back()->with('error', 'Akun Anda tidak terikat ke periode kepengurusan manapun. Hubungi administrator.');
            }

            $kepengurusan = $kepengurusanModel->find($currentKepengurusanId);
            if (!$kepengurusan) {
                return redirect()->back()->with('error', 'Data kepengurusan tidak ditemukan.');
            }

            // 2. Tutup periode kepengurusan ini: catat tanggal selesai & nonaktifkan
            $kepengurusanModel->update($currentKepengurusanId, [
                'tanggal_selesai' => date('Y-m-d'),
                'is_active'       => 0,
            ]);

            // 3. Nonaktifkan SEMUA akun yang terikat ke periode ini (Sekpel & Sekum lama)
            $db->table('users')
               ->where('kepengurusan_id', $currentKepengurusanId)
               ->update([
                   'active'     => 0,
                   'updated_at' => date('Y-m-d H:i:s'),
               ]);

            // 4. TIDAK ADA data surat atau proker yang dihapus — semua aman sebagai arsip

            // 5. Force logout — akhiri sesi Sekum lama
            session()->destroy();

            return redirect()->to(base_url('login'))->with('success', '✅ Demisioner selesai! Kepengurusan ' . $kepengurusan['tahun_periode'] . ' telah resmi ditutup. Silakan berikan akun baru ke Sekum penerus.');

        } catch (\Exception $e) {
            // ✅ FIX #9: Jangan tampilkan pesan teknis ke layar — simpan ke log saja
            log_message('critical', '[Demisioner] Gagal: ' . $e->getMessage() . ' | File: ' . $e->getFile() . ':' . $e->getLine());
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat proses demisioner. Silakan hubungi administrator.');
        }
    }
}

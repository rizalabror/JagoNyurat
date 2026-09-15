<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProgramKerjaModel;
use App\Models\KategoriTemplateModel;
use App\Models\SuratModel;
use App\Models\IdentityModel;
use App\Models\NotificationModel;

class SekpelController extends BaseController
{
    protected $prokerModel;
    protected $kategoriModel;
    protected $suratModel;
    protected $identityModel;

    public function __construct()
    {
        $this->prokerModel   = new ProgramKerjaModel();
        $this->kategoriModel = new KategoriTemplateModel();
        $this->suratModel    = new SuratModel();
        $this->identityModel = new IdentityModel();

        // Muat helper penomoran surat terpusat
        helper('penomoran');
    }

    /**
     * Dashboard Sekpel — halaman beranda setelah login.
     */
    public function dashboard(): string
    {
        $userId = user_id();
        $suratModel = new SuratModel();

        $suratQuery = $suratModel
            ->select('surat.*, program_kerja.nama as nama_proker, kategori_template.nama_kategori')
            ->join('program_kerja', 'program_kerja.id = surat.proker_id', 'left')
            ->join('kategori_template', 'kategori_template.id = surat.kategori_id', 'left')
            ->where('surat.user_id', $userId)
            ->orderBy('surat.created_at', 'DESC')
            ->findAll();

        $count_draft = 0;
        $count_pending = 0;
        $count_rejected = 0;
        $count_approved = 0;

        foreach ($suratQuery as $s) {
            if ($s['status'] === 'Draft') $count_draft++;
            elseif ($s['status'] === 'Pending') $count_pending++;
            elseif ($s['status'] === 'Rejected') $count_rejected++;
            elseif ($s['status'] === 'Approved') $count_approved++;
        }

        $prokerModel = new ProgramKerjaModel();

        return view('Sekpel/dashboard', [
            'surat_sekpel'  => $suratQuery,
            'count_draft'   => $count_draft,
            'count_pending' => $count_pending,
            'count_rejected'=> $count_rejected,
            'count_approved'=> $count_approved,
            'proker_info'   => $prokerModel->where('user_id', $userId)->first(),
        ]);
    }

    // Menampilkan halaman Form Buat Surat Baru
    public function createSurat()
    {
        // Ambil proker yang dimiliki user yang login (auto, tidak perlu dropdown)
        $prokerAktif = $this->prokerModel->where('user_id', user_id())->first();

        $data = [
            'title'       => 'Buat Surat Baru - Sekpel',
            'prokerAktif' => $prokerAktif, // Proker otomatis dari biodata
            'ketua_bem'   => $this->identityModel->where('jabatan', 'Ketua BEM Fasilkom')->findAll(),
            'dekan'       => $this->identityModel->whereIn('jabatan', ['Dekan Fasilkom', 'Wakil Dekan Bidang Akademik dan Kemahasiswaan', 'Wakil Dekan Bidang Umum dan Keuangan'])->findAll(),
            // Flag: TinyMCE hanya dimuat di halaman ini, bukan secara global
            'useTinyMCE'  => true,
        ];

        return view('Sekpel/Surat/create', $data);
    }

    // Proses Penyimpanan Surat dari Sekpel
    public function storeSurat()
    {
        // Ambil proker_id otomatis dari biodata user yang login
        $prokerAktif = $this->prokerModel->where('user_id', user_id())->first();
        if (!$prokerAktif) {
            return redirect()->back()->with('error', 'Anda belum memiliki Program Kerja. Lengkapi biodata terlebih dahulu.');
        }

        $rules = [
            'jenis_surat'            => 'required|in_list[non_elektronik,elektronik]',
            'perihal'                => 'required',
            'tujuan_surat'           => 'required',
            'tempat_tujuan'          => 'required',
        ];

        $sembunyikanTabel = $this->request->getPost('sembunyikan_tabel') ? true : false;
        
        if (!$sembunyikanTabel) {
            $rules['tanggal_kegiatan_mulai'] = 'required|valid_date';
            $rules['waktu_kegiatan_mulai']   = 'required';
            $rules['tempat_kegiatan']        = 'required';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Harap periksa kembali isian form Anda.');
        }

        // Tanggal/waktu selesai opsional — gunakan nilai mulai jika kosong
        $tglSelesai   = $this->request->getPost('tanggal_kegiatan_selesai') ?: $this->request->getPost('tanggal_kegiatan_mulai');
        $waktuSelesai = trim($this->request->getPost('waktu_kegiatan_selesai')) ?: null;

            // Pemrosesan Array Lampiran -> JSON Objek Dinamis
            $isiLampiranRaw = $this->request->getPost('isi_lampiran');
            $halamanLampiran = [];
            
            if (is_array($isiLampiranRaw)) {
                $filtered = array_filter($isiLampiranRaw, function($item) {
                    return !empty(trim($item));
                });
                $halamanLampiran = array_values($filtered);
            } else if (!empty(trim($isiLampiranRaw))) {
                $halamanLampiran = [$isiLampiranRaw];
            }

            $isiLampiranFinal = json_encode([
                'sembunyikan_tabel' => $sembunyikanTabel,
                'pages' => $halamanLampiran
            ]);

            $dataSurat = [
                'user_id'                  => user_id(),
                'proker_id'                => $prokerAktif['id'],
                'jenis_surat'              => $this->request->getPost('jenis_surat'),
                'ttd_ketua_bem'            => $this->request->getPost('ttd_ketua_bem') ?: null,
                'ttd_dekan'                => $this->request->getPost('ttd_dekan') ?: null,
                'lampiran'                 => $this->request->getPost('lampiran'),
                'perihal'                  => $this->request->getPost('perihal'),
                'tujuan_surat'             => $this->request->getPost('tujuan_surat'),
                'tempat_tujuan'            => $this->request->getPost('tempat_tujuan'),
                'isi_paragraf'             => $this->request->getPost('isi_paragraf'),
                'paragraf_isi'             => $this->request->getPost('paragraf_isi'),
                'penutup_paragraf'         => $this->request->getPost('penutup_paragraf'),
                'tanggal_kegiatan_mulai'   => $this->request->getPost('tanggal_kegiatan_mulai') ?: null,
                'tanggal_kegiatan_selesai' => $tglSelesai ?: null,
                'waktu_kegiatan_mulai'     => $this->request->getPost('waktu_kegiatan_mulai') ?: null,
                'waktu_kegiatan_selesai'   => $waktuSelesai,
                'tempat_kegiatan'          => $this->request->getPost('tempat_kegiatan') ?: null,
                'isi_lampiran'             => $isiLampiranFinal,
                'status'                   => 'Pending',
            ];

        $this->suratModel->insert($dataSurat);

        // Kirim notifikasi ke semua akun Sekum
        $db          = \Config\Database::connect();
        $sekumUsers  = $db->table('users')
                          ->select('users.id')
                          ->join('auth_groups_users', 'auth_groups_users.user_id = users.id')
                          ->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id')
                          ->whereIn('auth_groups.name', ['Sekum', 'sekum'])
                          ->get()->getResultArray();
        $perihal     = $this->request->getPost('perihal');
        $notifModel  = new NotificationModel();
        foreach ($sekumUsers as $su) {
            $notifModel->send(
                (int) $su['id'],
                'new_surat',
                user()->fullname . ' mengajukan surat baru: "' . $perihal . '"',
                null
            );
        }

        return redirect()->to(base_url('/'))->with('success', 'Surat berhasil diajukan ke Sekretaris Umum!');
    }

    // Mengajukan Draft ke Sekum (Ubah Status ke Pending)
    public function submitSekum($uuid)
    {
        $surat = $this->suratModel->findByPublicId($uuid);

        // Verifikasi kepemilikan
        if (!$surat || $surat['user_id'] != user_id()) {
            return redirect()->to(base_url('/'))->with('error', 'Akses ditolak atau surat tidak ditemukan.');
        }

        // ✅ FIX #3: Validasi status — hanya Draft/Rejected yang boleh diajukan
        if (!in_array($surat['status'], ['Draft', 'Rejected'])) {
            return redirect()->to(base_url('/'))->with('error', 'Surat tidak valid untuk diajukan. Hanya surat Draft atau Rejected yang bisa dikirim ulang.');
        }

        $this->suratModel->update($surat['id'], ['status' => 'Pending']);

        // Kirim notifikasi ke semua akun Sekum
        $db          = \Config\Database::connect();
        $sekumUsers  = $db->table('users')
                          ->select('users.id')
                          ->join('auth_groups_users', 'auth_groups_users.user_id = users.id')
                          ->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id')
                          ->whereIn('auth_groups.name', ['Sekum', 'sekum'])
                          ->get()->getResultArray();
        $notifModel  = new NotificationModel();
        foreach ($sekumUsers as $su) {
            $notifModel->send(
                (int) $su['id'],
                'new_surat',
                user()->fullname . ' mengajukan ulang surat: "' . $surat['perihal'] . '"',
                $surat['public_id']
            );
        }

        return redirect()->to(base_url('/'))->with('success', 'Surat berhasil diajukan ke Sekum!');
    }

    // Menampilkan halaman Edit form
    public function editSurat($uuid)
    {
        $surat = $this->suratModel->findByPublicId($uuid);

        if (!$surat || $surat['user_id'] != user_id() || ($surat['status'] != 'Draft' && $surat['status'] != 'Rejected')) {
            return redirect()->to(base_url('/'))->with('error', 'Akses ditolak atau surat tidak valid untuk diubah.');
        }

        $prokerAktif = $this->prokerModel->where('user_id', user_id())->first();

        $data = [
            'title'       => 'Edit Surat Revisi - Sekpel',
            'surat'       => $surat,
            'prokerAktif' => $prokerAktif,
            'ketua_bem'   => $this->identityModel->where('jabatan', 'Ketua BEM Fasilkom')->findAll(),
            'dekan'       => $this->identityModel->whereIn('jabatan', ['Dekan Fasilkom', 'Wakil Dekan Bidang Akademik dan Kemahasiswaan', 'Wakil Dekan Bidang Umum dan Keuangan'])->findAll(),
            'useTinyMCE'  => true,
        ];

        return view('Sekpel/Surat/edit', $data);
    }

    // Memproses Perubahan Draft Surat
    public function updateSurat($uuid)
    {
        $surat = $this->suratModel->findByPublicId($uuid);

        if (!$surat || $surat['user_id'] != user_id() || ($surat['status'] != 'Draft' && $surat['status'] != 'Rejected')) {
            return redirect()->to(base_url('/'))->with('error', 'Akses ditolak atau surat tidak valid untuk diubah.');
        }

        $rules = [
            'jenis_surat'            => 'required|in_list[non_elektronik,elektronik]',
            'perihal'                => 'required',
            'tujuan_surat'           => 'required',
            'tempat_tujuan'          => 'required',
        ];

        $sembunyikanTabel = $this->request->getPost('sembunyikan_tabel') ? true : false;
        
        if (!$sembunyikanTabel) {
            $rules['tanggal_kegiatan_mulai'] = 'required|valid_date';
            $rules['waktu_kegiatan_mulai']   = 'required';
            $rules['tempat_kegiatan']        = 'required';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Harap periksa kembali isian form Anda.');
        }

        $tglSelesai   = $this->request->getPost('tanggal_kegiatan_selesai') ?: $this->request->getPost('tanggal_kegiatan_mulai');
        $waktuSelesai = trim($this->request->getPost('waktu_kegiatan_selesai')) ?: null;

        // Pemrosesan Array Lampiran -> JSON Objek Dinamis
        $isiLampiranRaw = $this->request->getPost('isi_lampiran');
        $halamanLampiran = [];
        
        if (is_array($isiLampiranRaw)) {
            $filtered = array_filter($isiLampiranRaw, function($item) {
                return !empty(trim($item));
            });
            $halamanLampiran = array_values($filtered);
        } else if (!empty(trim($isiLampiranRaw))) {
            $halamanLampiran = [$isiLampiranRaw];
        }

        $isiLampiranFinal = json_encode([
            'sembunyikan_tabel' => $sembunyikanTabel,
            'pages' => $halamanLampiran
        ]);

        $dataSurat = [
            'jenis_surat'              => $this->request->getPost('jenis_surat'),
            'ttd_ketua_bem'            => $this->request->getPost('ttd_ketua_bem') ?: null,
            'ttd_dekan'                => $this->request->getPost('ttd_dekan') ?: null,
            'lampiran'                 => $this->request->getPost('lampiran'),
            'perihal'                  => $this->request->getPost('perihal'),
            'tujuan_surat'             => $this->request->getPost('tujuan_surat'),
            'tempat_tujuan'            => $this->request->getPost('tempat_tujuan'),
            'isi_paragraf'             => $this->request->getPost('isi_paragraf'),
            'paragraf_isi'             => $this->request->getPost('paragraf_isi'),
            'penutup_paragraf'         => $this->request->getPost('penutup_paragraf'),
            'tanggal_kegiatan_mulai'   => $this->request->getPost('tanggal_kegiatan_mulai') ?: null,
            'tanggal_kegiatan_selesai' => $tglSelesai ?: null,
            'waktu_kegiatan_mulai'     => $this->request->getPost('waktu_kegiatan_mulai') ?: null,
            'waktu_kegiatan_selesai'   => $waktuSelesai,
            'tempat_kegiatan'          => $this->request->getPost('tempat_kegiatan') ?: null,
            'isi_lampiran'             => $isiLampiranFinal,
            'status'                   => 'Pending',
            'catatan_revisi'           => null,
        ];

        $this->suratModel->update($surat['id'], $dataSurat);

        return redirect()->to(base_url('/'))->with('success', 'Surat berhasil diajukan kembali ke Sekretaris Umum!');
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

        if (!$surat || $surat['user_id'] != user_id()) {
            return redirect()->to(base_url('/'))->with('error', 'Akses surat cetak ditolak.');
        }

        // Ambil Data Kop BEM 
        $organisasiModel = new \App\Models\OrganisasiModel();
        $bem = $organisasiModel->where('organisasi', 'BEM')->first();

        $identityModel = new IdentityModel();
        
        // Ambil data pejabat penandatangan HANYA JIKA dipilih di surat
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

        // Ambil data proker lengkap (nama ketua, npm, foto ttd)
        $proker = $this->prokerModel->find($surat['proker_id']);

        $data = [
            'surat'     => $surat,
            'bem'       => $bem,
            'ttd_bem'   => $ttd_bem,
            'ttd_dekan' => $ttd_dekan,
            'proker'    => $proker,
            'kop_extra' => [
                'baris1' => $kop1['nama'] ?? 'KEMENTERIAN PENDIDIKAN TINGGI, SAINS, DAN TEKNOLOGI',
                'baris2' => $kop2['nama'] ?? 'UNIVERSITAS SINGAPERBANGSA KARAWANG',
                'baris3' => $kop3['nama'] ?? 'BADAN EKSEKUTIF MAHASISWA',
                'baris4' => $kop4['nama'] ?? 'FAKULTAS ILMU KOMPUTER',
            ]
        ];

        return view('Sekpel/Surat/cetak', $data);
    }

    // AJAX Live Preview Endpoint
    public function previewHtml()
    {
        $postData = $this->request->getPost();

        // ✅ FIX #2 (IDOR): Paksa ambil proker dari user yang sedang login.
        // Abaikan proker_id dari POST — mencegah Sekpel A melihat data Sekpel B.
        $prokerFull = $this->prokerModel->where('user_id', user_id())->first();
        if (!$prokerFull) {
            return redirect()->back()->with('error', 'Anda belum memiliki Program Kerja.');
        }

        // Pemrosesan Array Lampiran -> JSON khusus untuk Preview
        $isiLampiranRaw = $postData['isi_lampiran'] ?? '';
        $sembunyikanTabel = !empty($postData['sembunyikan_tabel']);
        
        $halamanLampiran = [];
        if (is_array($isiLampiranRaw)) {
            $filtered = array_filter($isiLampiranRaw, function($item) {
                return !empty(trim($item));
            });
            $halamanLampiran = array_values($filtered);
        } else if (!empty(trim($isiLampiranRaw))) {
            $halamanLampiran = [$isiLampiranRaw];
        }

        $isiLampiranFinal = json_encode([
            'sembunyikan_tabel' => $sembunyikanTabel,
            'pages' => $halamanLampiran
        ]);

        // 2. Siapkan Draf Surat Imitasi
        $tglMulai   = !empty($postData['tanggal_kegiatan_mulai']) ? $postData['tanggal_kegiatan_mulai'] : date('Y-m-d');
        $tglSelesai = !empty($postData['tanggal_kegiatan_selesai']) ? $postData['tanggal_kegiatan_selesai'] : $tglMulai;
        
        $prokerIdForNomor = $prokerFull['id'] ?? null;

        $suratData = [
            'nama_proker'              => $prokerFull['nama'] ?? 'PROGRAM KERJA',
            'jenis_surat'              => $postData['jenis_surat'] ?? 'non_elektronik',
            'perihal'                  => !empty($postData['perihal']) ? $postData['perihal'] : 'Permohonan Izin Kegiatan',
            'lampiran'                 => !empty($postData['lampiran']) ? $postData['lampiran'] : '-',
            'tujuan_surat'             => !empty($postData['tujuan_surat']) ? $postData['tujuan_surat'] : 'Yth. Kepala Bagian Umum',
            'tempat_tujuan'            => !empty($postData['tempat_tujuan']) ? $postData['tempat_tujuan'] : 'Tempat',
            'isi_paragraf'             => $postData['isi_paragraf'] ?? '',
            'paragraf_isi'             => $postData['paragraf_isi'] ?? '',
            'penutup_paragraf'         => $postData['penutup_paragraf'] ?? '',
            'tanggal_kegiatan_mulai'   => $tglMulai,
            'tanggal_kegiatan_selesai' => $tglSelesai,
            'waktu_kegiatan_mulai'     => !empty($postData['waktu_kegiatan_mulai']) ? $postData['waktu_kegiatan_mulai'] : '08:00',
            'waktu_kegiatan_selesai'   => $postData['waktu_kegiatan_selesai'] ?? null,
            'tempat_kegiatan'          => !empty($postData['tempat_kegiatan']) ? $postData['tempat_kegiatan'] : 'Kampus UNSIKA',
            'isi_lampiran'             => $isiLampiranFinal,
            'ttd_ketua_bem'            => $postData['ttd_ketua_bem'] ?? null,
            'ttd_dekan'                => $postData['ttd_dekan'] ?? null,
            'nama_pembuat'             => user() ? user()->fullname : 'Sekretaris Pelaksana',
            'npm_pembuat'              => user() ? user()->npm : 'NPM...',
            'updated_at'               => date('Y-m-d H:i:s'),
            'nomor_surat'              => generate_nomor_surat($prokerIdForNomor),
        ];

        // 3. Ambil Identitas BEM
        $organisasiModel = new \App\Models\OrganisasiModel();
        $bem = $organisasiModel->where('organisasi', 'BEM')->first();

        // 4. Ambil Identitas Pejabat (Berdasarkan Pilihan Dropdown) & Kop
        $identityModel = new IdentityModel();
        $ttd_bem = !empty($suratData['ttd_ketua_bem']) ? $identityModel->where('jabatan', $suratData['ttd_ketua_bem'])->first() : null;
        $ttd_dekan = !empty($suratData['ttd_dekan']) ? $identityModel->where('jabatan', $suratData['ttd_dekan'])->first() : null;

        $kop1 = $identityModel->where('jabatan', 'Kementerian')->first();
        $kop2 = $identityModel->where('jabatan', 'Universitas')->first();
        $kop3 = $identityModel->where('jabatan', 'Organisasi')->first();
        $kop4 = $identityModel->where('jabatan', 'Fakultas')->first();

        $data = [
            'surat'      => $suratData,
            'bem'        => $bem,
            'ttd_bem'    => $ttd_bem,
            'ttd_dekan'  => $ttd_dekan,
            'proker'     => $prokerFull,
            'is_preview' => true,
            'kop_extra'  => [
                'baris1' => $kop1['nama'] ?? 'KEMENTERIAN PENDIDIKAN TINGGI, SAINS, DAN TEKNOLOGI',
                'baris2' => $kop2['nama'] ?? 'UNIVERSITAS SINGAPERBANGSA KARAWANG',
                'baris3' => $kop3['nama'] ?? 'BADAN EKSEKUTIF MAHASISWA',
                'baris4' => $kop4['nama'] ?? 'FAKULTAS ILMU KOMPUTER',
            ]
        ];

        return view('Sekpel/Surat/cetak', $data);
    }

    public function submitSurat($uuid)
    {
        $suratData = $this->suratModel->findByPublicId($uuid);

        if (!$suratData || $suratData['user_id'] != user_id()) {
            return redirect()->back()->with('error', 'Surat tidak ditemukan atau Anda tidak memiliki akses.');
        }

        if ($suratData['status'] !== 'Draft' && $suratData['status'] !== 'Rejected') {
            return redirect()->back()->with('error', 'Hanya surat berstatus Draft atau Rejected yang bisa diajukan.');
        }

        $this->suratModel->update($suratData['id'], [
            'status'     => 'Pending',
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->to(base_url('/'))->with('success', 'Surat berhasil diajukan ke Sekretaris Umum!');
    }

    // --- Cetak / Download PDF Surat (via browser print dialog) ---
    // Cukup redirect ke halaman cetak yang auto-trigger window.print().
    // Browser Chrome menghasilkan PDF pixel-perfect:
    //   ✅ Ukuran kertas F4 (via @page CSS)
    //   ✅ Kop berulang di setiap halaman (table-header-group)
    //   ✅ Tidak ada border issue
    //   ✅ Cap & TTD overlay sempurna
    public function downloadPdf($uuid)
    {
        $surat = $this->suratModel->findByPublicId($uuid);

        if (!$surat || $surat['user_id'] != user_id() || $surat['status'] !== 'Approved') {
            return redirect()->to(base_url('/'))->with('error', 'Akses ditolak atau surat belum disetujui.');
        }

        // Redirect ke halaman cetak (auto-trigger window.print())
        return redirect()->to(base_url('sekpel/surat/print/' . $uuid));
    }

    // --- Upload Surat Fisik (Non-Elektronik) ---
    public function uploadArsipSurat()
    {
        $uuid = $this->request->getPost('surat_id');
        $surat = $this->suratModel->findByPublicId($uuid);

        if (!$surat || $surat['user_id'] != user_id() || $surat['status'] !== 'Approved' || $surat['jenis_surat'] !== 'non_elektronik') {
            return redirect()->to(base_url('/'))->with('error', 'Akses ditolak atau surat tidak valid untuk diupload.');
        }

        $rules = [
            'arsip_surat' => 'uploaded[arsip_surat]|max_size[arsip_surat,5120]|ext_in[arsip_surat,pdf,png,jpg,jpeg]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('error', 'Gagal mengunggah. Pastikan format file PDF/JPG/PNG dan ukuran maksimal 5MB.');
        }

        $fileArsip = $this->request->getFile('arsip_surat');
        if ($fileArsip->isValid() && !$fileArsip->hasMoved()) {
            // Hapus arsip lama jika ada
            if (!empty($surat['file_surat_fisik']) && file_exists(FCPATH . 'uploads/arsip_surat/' . $surat['file_surat_fisik'])) {
                unlink(FCPATH . 'uploads/arsip_surat/' . $surat['file_surat_fisik']);
            }

            $newName = $fileArsip->getRandomName();
            // Buat folder jika belum ada
            if (!is_dir(FCPATH . 'uploads/arsip_surat')) {
                mkdir(FCPATH . 'uploads/arsip_surat', 0777, true);
            }
            $fileArsip->move(FCPATH . 'uploads/arsip_surat', $newName);

            $this->suratModel->update($surat['id'], [
                'file_surat_fisik' => $newName
            ]);

            return redirect()->to(base_url('/'))->with('success', 'Arsip surat fisik berhasil diunggah.');
        }

        return redirect()->back()->with('error', 'Terjadi kesalahan saat mengunggah file.');
    }

}
// Catatan: generateAutoNomor() dipindahkan ke app/Helpers/penomoran_helper.php
// Gunakan: generate_nomor_surat($prokerId) dari mana saja.

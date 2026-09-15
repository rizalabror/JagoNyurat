<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Files\File;
use App\Models\ProgramKerjaModel;
use Myth\Auth\Models\UserModel;

class ProfileController extends BaseController
{
    protected $prokerModel;
    protected $userModel;

    public function __construct()
    {
        $this->prokerModel = new ProgramKerjaModel();
        
        // Memakai Model bawaan MythAuth untuk mengupdate data password & profil
        $this->userModel = new UserModel();
    }

    /**
     * Menampilkan Halaman Form Lengkapi Profil Sekpel
     */
    public function index()
    {
        // Cek dulu, jangan-jangan sudah terisi
        if (user()->is_profile_completed == 1) {
            return redirect()->to('/sekpel')->with('info', 'Profil Anda sudah lengkap.');
        }

        $data = [
            'title' => 'Lengkapi Biodata Kepanitiaan - Sekpel'
        ];

        return view('Profile/lengkapiprofil', $data);
    }

    /**
     * Proses Submit Form Lengkapi Profil
     */
    public function update()
    {
        $userId = user_id();

        // 1. Validasi Input Dasar
        $rules = [
            'password'             => 'required|min_length[8]',
            'pass_confirm'         => 'required|matches[password]',
            'fullname'             => 'required',
            'npm'                  => 'required',
            'kode_proker'          => 'required',
            'nama_proker'          => 'required',
            'tahun_aktif'          => 'required|valid_date[Y]',
            'nama_ketua_pelaksana' => 'required',
            'npm_ketua_pelaksana'  => 'required',
            'ttd_sekpel'           => 'uploaded[ttd_sekpel]|max_size[ttd_sekpel,2048]|is_image[ttd_sekpel]|mime_in[ttd_sekpel,image/png,image/jpeg]',
            'ttd_ketua'            => 'uploaded[ttd_ketua]|max_size[ttd_ketua,2048]|is_image[ttd_ketua]|mime_in[ttd_ketua,image/png,image/jpeg]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // 2. Modifikasi Data User (Tabel users)
        $userData = [
            'fullname'             => $this->request->getPost('fullname'),
            'npm'                  => $this->request->getPost('npm'),
            'password_hash'        => \Myth\Auth\Password::hash($this->request->getPost('password')),
            'is_profile_completed' => 1
        ];

        // Pakai query builder karena method bawaan UserModel MythAuth punya rule sendiri
        $db = \Config\Database::connect();
        $builder = $db->table('users');
        $builder->where('id', $userId)->update($userData);


        // 3. Proses File Upload
        $ttdSekpel = $this->request->getFile('ttd_sekpel');
        $ttdKetua = $this->request->getFile('ttd_ketua');

        $ttdSekpelName = $ttdSekpel->getRandomName();
        $ttdKetuaName = $ttdKetua->getRandomName();

        // Pindahkan file ke /public/uploads/ttd/
        $ttdSekpel->move(FCPATH . 'uploads/ttd', $ttdSekpelName);
        $ttdKetua->move(FCPATH . 'uploads/ttd', $ttdKetuaName);

        // 4. Modifikasi Data Proker (Tabel program_kerja)
        // Ambil kepengurusan_id dari user yang login agar proker terikat ke periode yang benar
        $currentUser = user();
        $prokerData = [
            'user_id'              => $userId,
            'kepengurusan_id'      => $currentUser->kepengurusan_id,
            'kode_proker'          => $this->request->getPost('kode_proker'),
            'nama'                 => $this->request->getPost('nama_proker'),
            'tahun_aktif'          => $this->request->getPost('tahun_aktif'),
            'nama_ketua_pelaksana' => $this->request->getPost('nama_ketua_pelaksana'),
            'npm_ketua_pelaksana'  => $this->request->getPost('npm_ketua_pelaksana'),
            'ttd_sekpel'           => $ttdSekpelName,
            'ttd_ketua_pelaksana'  => $ttdKetuaName
        ];

        $this->prokerModel->insert($prokerData);

        return redirect()->to(base_url('/'))->with('success', 'Biodata Kepanitiaan berhasil disimpan. Anda bisa mengubahnya kembali jika diperlukan.');
    }

    /**
     * Menampilkan Halaman Form Edit Biodata (Bisa diakses kapan saja dari Dasbor Sekpel)
     */
    public function edit()
    {
        $userId = user_id();
        
        // Ambil data Program Kerja milik Sekpel ini
        $proker = $this->prokerModel->where('user_id', $userId)->first();

        // Jika tidak ada proker, ini aneh karena harusnya dihadang filter. Kita lemparkan saja.
        if (!$proker) {
            return redirect()->to('/sekpel/profil/lengkapi');
        }

        $data = [
            'title' => 'Edit Biodata Kepanitiaan',
            'proker' => $proker
        ];

        return view('Profile/editprofil', $data);
    }

    /**
     * Proses Pembaruan Submit Form Edit Biodata
     */
    public function updateEdit()
    {
        $userId = user_id();
        $proker = $this->prokerModel->where('user_id', $userId)->first();

        if (!$proker) {
            return redirect()->to('/sekpel')->with('error', 'Data Program Kerja tidak ditemukan.');
        }

        // 1. Validasi Input Edit
        $rules = [
            'fullname'             => 'required',
            'npm'                  => 'required',
            'kode_proker'          => 'required',
            'nama_proker'          => 'required',
            'tahun_aktif'          => 'required|valid_date[Y]',
            'nama_ketua_pelaksana' => 'required',
            'npm_ketua_pelaksana'  => 'required',
            // Gambar diatur opsional pada saat edit
            'ttd_sekpel'           => 'max_size[ttd_sekpel,2048]|is_image[ttd_sekpel]|mime_in[ttd_sekpel,image/png,image/jpeg]',
            'ttd_ketua'            => 'max_size[ttd_ketua,2048]|is_image[ttd_ketua]|mime_in[ttd_ketua,image/png,image/jpeg]',
        ];

        // Jika input password diisi (opsional ganti sandi)
        if (!empty($this->request->getPost('password'))) {
            $rules['password'] = 'min_length[8]';
            $rules['pass_confirm'] = 'matches[password]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // 2. Modifikasi Data User (Tabel users)
        $userData = [
            'fullname' => $this->request->getPost('fullname'),
            'npm'      => $this->request->getPost('npm'),
        ];

        if (!empty($this->request->getPost('password'))) {
            $userData['password_hash'] = \Myth\Auth\Password::hash($this->request->getPost('password'));
        }

        $db = \Config\Database::connect();
        $builder = $db->table('users');
        $builder->where('id', $userId)->update($userData);

        // 3. Modifikasi Data Proker & Proses File Upload Opsional
        $prokerData = [
            'kode_proker'          => $this->request->getPost('kode_proker'),
            'nama'                 => $this->request->getPost('nama_proker'),
            'tahun_aktif'          => $this->request->getPost('tahun_aktif'),
            'nama_ketua_pelaksana' => $this->request->getPost('nama_ketua_pelaksana'),
            'npm_ketua_pelaksana'  => $this->request->getPost('npm_ketua_pelaksana'),
        ];

        $ttdSekpel = $this->request->getFile('ttd_sekpel');
        if ($ttdSekpel && $ttdSekpel->isValid() && !$ttdSekpel->hasMoved()) {
            // Hapus file lama jika ada (opsional)
            if (file_exists(FCPATH . 'uploads/ttd/' . $proker['ttd_sekpel'])) {
                unlink(FCPATH . 'uploads/ttd/' . $proker['ttd_sekpel']);
            }
            $ttdSekpelName = $ttdSekpel->getRandomName();
            $ttdSekpel->move(FCPATH . 'uploads/ttd', $ttdSekpelName);
            $prokerData['ttd_sekpel'] = $ttdSekpelName;
        }

        $ttdKetua = $this->request->getFile('ttd_ketua');
        if ($ttdKetua && $ttdKetua->isValid() && !$ttdKetua->hasMoved()) {
             if (file_exists(FCPATH . 'uploads/ttd/' . $proker['ttd_ketua_pelaksana'])) {
                unlink(FCPATH . 'uploads/ttd/' . $proker['ttd_ketua_pelaksana']);
            }
            $ttdKetuaName = $ttdKetua->getRandomName();
            $ttdKetua->move(FCPATH . 'uploads/ttd', $ttdKetuaName);
            $prokerData['ttd_ketua_pelaksana'] = $ttdKetuaName;
        }

        $this->prokerModel->update($proker['id'], $prokerData);

        return redirect()->to(base_url('sekpel/profil/edit'))->with('success', 'Biodata Kepanitiaan berhasil diperbarui!');
    }
}

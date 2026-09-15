<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Myth\Auth\Models\UserModel;
use Myth\Auth\Password;

/**
 * Controller onboarding untuk Sekum baru.
 * Dipanggil setelah Sekum baru login pertama kali,
 * sebelum bisa mengakses dashboard.
 */
class SekumProfileController extends BaseController
{
    /**
     * Tampilkan form Lengkapi Biodata Sekum Baru.
     */
    public function index()
    {
        // Jika sudah lengkap, langsung ke dashboard
        if (user()->is_profile_completed == 1) {
            return redirect()->to(base_url('sekum'));
        }

        return view('Sekum/lengkapi_biodata', [
            'title' => 'Lengkapi Biodata — Sekretaris Umum Baru',
        ]);
    }

    /**
     * Proses penyimpanan biodata Sekum baru.
     */
    public function simpan()
    {
        $userId = user_id();

        $rules = [
            'fullname'     => 'required|min_length[3]',
            'npm'          => 'required|numeric',
            'password'     => 'required|min_length[8]',
            'pass_confirm' => 'required|matches[password]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Update data biodata + password + tandai profil selesai
        $db = \Config\Database::connect();
        $db->table('users')->where('id', $userId)->update([
            'fullname'             => $this->request->getPost('fullname'),
            'npm'                  => $this->request->getPost('npm'),
            'password_hash'        => Password::hash($this->request->getPost('password')),
            'is_profile_completed' => 1,
            'updated_at'           => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to(base_url('sekum'))
                         ->with('success', 'Selamat datang! Biodata Anda telah tersimpan. Masa kepengurusan baru dimulai. 🎉');
    }
}

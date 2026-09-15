<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class ProfileCompletionFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Muat helper auth dari Myth Auth agar logged_in(), user(), in_groups() tersedia
        helper('auth');

        // Pastikan user sudah log in
        if (! logged_in()) {
            return redirect()->to(route_to('login'));
        }

        // Pengecekan untuk role Sekpel
        if (in_groups(['Sekpel', 'sekpel'])) {
            $user = user();
            if ($user->is_profile_completed == 0) {
                // Bersihkan redirect_url agar tidak diarahkan ke URL yang salah setelah login
                session()->remove('redirect_url');
                return redirect()->to(base_url('sekpel/profil/lengkapi'))->with('error', 'Akses Ditolak! Anda WAJIB melengkapi biodata kepanitiaan dan mengganti sandi sebelum bisa menggunakan sistem Jago Nyurat.');
            }
        }

        // Pengecekan untuk role Sekum — Sekum baru wajib isi biodata dulu
        if (in_groups(['Sekum', 'sekum'])) {
            $user = user();
            if ($user->is_profile_completed == 0) {
                // Bersihkan redirect_url agar tidak diarahkan ke URL yang salah setelah login
                session()->remove('redirect_url');
                return redirect()->to(base_url('sekum/profil/lengkapi'))->with('error', 'Selamat Datang, Sekum Baru! Mohon lengkapi biodata dan ganti kata sandi akun Anda terlebih dahulu.');
            }
        }
        
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak ada aksi khusus sesudah request
    }
}

<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (! function_exists('logged_in')) {
            helper('auth');
        }

        if (empty($arguments)) {
            return;
        }

        $authenticate = service('authentication');

        // Jika belum login, simpan riwayat klik sebelum login
        if (! $authenticate->check()) {
            session()->set('redirect_url', current_url());
            return redirect()->route('login');
        }

        $authorize = service('authorization');

        // Periksa role
        $hasGroup = false;
        foreach ($arguments as $group) {
            if ($authorize->inGroup(trim($group), $authenticate->id())) {
                $hasGroup = true;
                break;
            }
        }

        if (! $hasGroup) {
            // Hapus target palsu/salah kirim
            if (session()->has('redirect_url')) {
                session()->remove('redirect_url');
            }

            // Meredam Exception bawaan dan melakukan Graceful Redirect
            return redirect()->to('/')->with('error_banned', 'Akses Ditolak: Anda tidak memiliki wewenang tipe akun tersebut.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}

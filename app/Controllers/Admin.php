<?php

namespace App\Controllers;

/**
 * Admin (Gateway Controller)
 *
 * Bertanggung jawab HANYA untuk menjadi jembatan rute utama '/'.
 * Seluruh logika dashboard telah dipindahkan ke controller yang tepat:
 *   - Sekpel -> SekpelController::dashboard()
 *   - Sekum  -> SekumController::dashboard()
 *
 * File ini dipertahankan sementara karena Routes.php masih mengacu ke Admin::index.
 * Jika kelak Routes.php sudah diupdate langsung, file ini boleh dihapus.
 */
class Admin extends BaseController
{
    /**
     * Gateway rute utama '/'.
     * Meneruskan request ke dashboard controller yang sesuai berdasarkan role.
     */
    public function index()
    {
        if (in_groups(['Sekpel', 'sekpel'])) {
            return redirect()->to(base_url('sekpel'));
        }

        if (in_groups(['Sekum', 'sekum'])) {
            return redirect()->to(base_url('sekum'));
        }

        // Fallback: user tidak dikenali atau belum login
        return redirect()->to(config('Auth')->loginURL ?? '/login');
    }
}

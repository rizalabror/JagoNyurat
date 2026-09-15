<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    public function run()
    {
        // 1. Data Dummy Program Kerja
        $dataProker = [
            [
                'kode_proker' => 'COMPFAIR',
                'nama'        => 'Compfair 2025',
                'tahun_aktif' => 2025,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'kode_proker' => 'LKMM',
                'nama'        => 'Latihan Keterampilan Manajemen Mahasiswa (LKMM) 2025',
                'tahun_aktif' => 2025,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'kode_proker' => 'WEBA',
                'nama'        => 'Welcoming Maba 2025',
                'tahun_aktif' => 2025,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ]
        ];
        $this->db->table('program_kerja')->insertBatch($dataProker);

        // 2. Data Dummy Kategori Template Surat
        $dataKategori = [
            [
                'nama_kategori'    => 'Pemberitahuan Kegiatan',
                'kode_klasifikasi' => '020',
            ],
            [
                'nama_kategori'    => 'Permohonan Peminjaman Alat',
                'kode_klasifikasi' => '023',
            ],
            [
                'nama_kategori'    => 'Undangan Pengenalan UKM',
                'kode_klasifikasi' => '007',
            ]
        ];
        $this->db->table('kategori_template')->insertBatch($dataKategori);
    }
}

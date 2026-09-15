<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateSuratFieldsV2 extends Migration
{
    public function up()
    {
        // 1. Tambah kolom jenis_surat (Elektronik / Non Elektronik)
        $this->forge->addColumn('surat', [
            'jenis_surat' => [
                'type'       => 'ENUM',
                'constraint' => ['non_elektronik', 'elektronik'],
                'default'    => 'non_elektronik',
                'after'      => 'kategori_id',
            ],
        ]);

        // 2. Tambah kolom paragraf_isi (bagian "Isi" yang sebelumnya menjadi satu dengan isi_paragraf)
        $this->forge->addColumn('surat', [
            'paragraf_isi' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'isi_paragraf',
            ],
        ]);

        // 3. Ubah waktu_kegiatan_selesai dari TIME ke VARCHAR(20) agar bisa menyimpan "Selesai"
        $fields = [
            'waktu_kegiatan_selesai' => [
                'name'       => 'waktu_kegiatan_selesai',
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],
        ];
        $this->forge->modifyColumn('surat', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('surat', 'jenis_surat');
        $this->forge->dropColumn('surat', 'paragraf_isi');

        // Kembalikan ke TIME (data teks seperti "Selesai" akan hilang)
        $fields = [
            'waktu_kegiatan_selesai' => [
                'name'       => 'waktu_kegiatan_selesai',
                'type'       => 'TIME',
                'null'       => true,
            ],
        ];
        $this->forge->modifyColumn('surat', $fields);
    }
}

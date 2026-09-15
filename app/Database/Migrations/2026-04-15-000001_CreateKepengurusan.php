<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKepengurusan extends Migration
{
    public function up()
    {
        // -------------------------------------------------------
        // 1. Buat tabel kepengurusan (periode per tahun)
        // -------------------------------------------------------
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'tahun_periode' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'comment'    => 'Contoh: 2024/2025',
            ],
            'tanggal_mulai' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'tanggal_selesai' => [
                'type' => 'DATE',
                'null' => true,
                'comment' => 'Diisi otomatis saat demisioner dijalankan',
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
                'comment'    => '1 = sedang berjalan, 0 = sudah demisioner',
            ],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('kepengurusan', true);

        // -------------------------------------------------------
        // 2. Tambah kolom kepengurusan_id ke tabel users
        // -------------------------------------------------------
        $this->forge->addColumn('users', [
            'kepengurusan_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'is_profile_completed',
            ],
        ]);

        // -------------------------------------------------------
        // 3. Tambah kolom kepengurusan_id ke tabel program_kerja
        // -------------------------------------------------------
        $this->forge->addColumn('program_kerja', [
            'kepengurusan_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'id',
            ],
        ]);
    }

    public function down()
    {
        // Rollback kolom program_kerja
        $this->forge->dropColumn('program_kerja', 'kepengurusan_id');

        // Rollback kolom users
        $this->forge->dropColumn('users', 'kepengurusan_id');

        // Hapus tabel kepengurusan
        $this->forge->dropTable('kepengurusan', true);
    }
}

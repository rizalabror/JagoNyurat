<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddProfileDataUsersAndProker extends Migration
{
    public function up()
    {
        // 1. Modifikasi Tabel Users (Menambah flag is_profile_completed)
        $fieldsUsers = [
            'is_profile_completed' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'after'      => 'active'
            ],
        ];
        $this->forge->addColumn('users', $fieldsUsers);

        // 2. Modifikasi Tabel Program Kerja (Menambah Biodata dan Relasi)
        $fieldsProker = [
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'id'
            ],
            'singkatan' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
                'after'      => 'nama'
            ],
            'nama_ketua_pelaksana' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'tahun_aktif'
            ],
            'npm_ketua_pelaksana' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
                'after'      => 'nama_ketua_pelaksana'
            ],
            'ttd_ketua_pelaksana' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'npm_ketua_pelaksana'
            ],
            'ttd_sekpel' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'ttd_ketua_pelaksana'
            ],
        ];
        $this->forge->addColumn('program_kerja', $fieldsProker);
    }

    public function down()
    {
        // Rollback tabel users
        $this->forge->dropColumn('users', 'is_profile_completed');

        // Rollback tabel program_kerja
        $this->forge->dropColumn('program_kerja', ['user_id', 'singkatan', 'nama_ketua_pelaksana', 'npm_ketua_pelaksana', 'ttd_ketua_pelaksana', 'ttd_sekpel']);
    }
}

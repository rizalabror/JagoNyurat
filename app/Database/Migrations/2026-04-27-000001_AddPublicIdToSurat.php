<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPublicIdToSurat extends Migration
{
    public function up()
    {
        // 1. Tambahkan kolom public_id (VARCHAR 36 untuk UUID)
        $this->forge->addColumn('surat', [
            'public_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 36,
                'null'       => true,  // Sementara null agar bisa populate dulu
                'after'      => 'id',
            ],
        ]);

        // 2. Generate UUID untuk semua surat yang sudah ada
        $db   = \Config\Database::connect();
        $rows = $db->table('surat')->select('id')->get()->getResultArray();

        foreach ($rows as $row) {
            $uuid = sprintf(
                '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                mt_rand(0, 0xffff), mt_rand(0, 0xffff),
                mt_rand(0, 0xffff),
                mt_rand(0, 0x0fff) | 0x4000,
                mt_rand(0, 0x3fff) | 0x8000,
                mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
            );
            $db->table('surat')->where('id', $row['id'])->update(['public_id' => $uuid]);
        }

        // 3. Set NOT NULL setelah semua baris terisi
        $this->forge->modifyColumn('surat', [
            'public_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 36,
                'null'       => false,
            ],
        ]);

        // 4. Tambahkan UNIQUE index
        $this->forge->addKey('public_id', false, true);
        $this->forge->processIndexes('surat');
    }

    public function down()
    {
        $this->forge->dropColumn('surat', 'public_id');
    }
}

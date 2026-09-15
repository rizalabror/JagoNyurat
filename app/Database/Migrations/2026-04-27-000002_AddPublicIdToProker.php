<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPublicIdToProker extends Migration
{
    public function up()
    {
        // 1. Tambahkan kolom public_id (VARCHAR 36 untuk UUID)
        $this->forge->addColumn('program_kerja', [
            'public_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 36,
                'null'       => true,
                'after'      => 'id',
            ],
        ]);

        // 2. Generate UUID untuk semua proker yang sudah ada
        $db   = \Config\Database::connect();
        $rows = $db->table('program_kerja')->select('id')->get()->getResultArray();

        foreach ($rows as $row) {
            $uuid = sprintf(
                '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                mt_rand(0, 0xffff), mt_rand(0, 0xffff),
                mt_rand(0, 0xffff),
                mt_rand(0, 0x0fff) | 0x4000,
                mt_rand(0, 0x3fff) | 0x8000,
                mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
            );
            $db->table('program_kerja')->where('id', $row['id'])->update(['public_id' => $uuid]);
        }

        // 3. Set NOT NULL setelah semua baris terisi
        $this->forge->modifyColumn('program_kerja', [
            'public_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 36,
                'null'       => false,
            ],
        ]);

        // 4. Tambahkan UNIQUE index
        $this->forge->addKey('public_id', false, true);
        $this->forge->processIndexes('program_kerja');
    }

    public function down()
    {
        $this->forge->dropColumn('program_kerja', 'public_id');
    }
}

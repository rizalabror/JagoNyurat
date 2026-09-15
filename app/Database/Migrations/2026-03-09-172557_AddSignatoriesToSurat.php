<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSignatoriesToSurat extends Migration
{
    public function up()
    {
        $fields = [
            'ttd_ketua_bem' => [
                'type'       => 'VARCHAR',
                'constraint' => 64,
                'null'       => true,
                'after'      => 'perihal'
            ],
            'ttd_dekan' => [
                'type'       => 'VARCHAR',
                'constraint' => 64,
                'null'       => true,
                'after'      => 'ttd_ketua_bem'
            ],
        ];

        $this->forge->addColumn('surat', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('surat', ['ttd_ketua_bem', 'ttd_dekan']);
    }
}

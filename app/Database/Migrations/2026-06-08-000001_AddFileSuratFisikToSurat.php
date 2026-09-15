<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFileSuratFisikToSurat extends Migration
{
    public function up()
    {
        $this->forge->addColumn('surat', [
            'file_surat_fisik' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
                'default'    => null,
                'after'      => 'isi_lampiran'
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('surat', 'file_surat_fisik');
    }
}

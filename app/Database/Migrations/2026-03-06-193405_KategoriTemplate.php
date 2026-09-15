<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class KategoriTemplate extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'nama_kategori' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
            ],
            'kode_klasifikasi' => [
                'type' => 'VARCHAR',
                'constraint' => '10',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('kategori_template');
    }

    public function down()
    {
        $this->forge->dropTable('kategori_template');
    }
}

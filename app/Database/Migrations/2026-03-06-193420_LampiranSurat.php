<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class LampiranSurat extends Migration
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
            'surat_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'nama_file' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'path_file' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'tipe_file' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        
        // Relasi: Jika surat dihapus, data lampiran ini juga akan lenyap (CASCADE)
        $this->forge->addForeignKey('surat_id', 'surat', 'id', 'CASCADE', 'CASCADE');
        
        $this->forge->createTable('lampiran_surat');
    }

    public function down()
    {
        $this->forge->dropTable('lampiran_surat');
    }
}

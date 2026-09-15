<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Surat extends Migration
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
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'proker_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'kategori_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'nomor_surat' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
            ],
            'lampiran' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
            ],
            'perihal' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'tujuan_surat' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true, // Boleh null jika custom
            ],
            'tempat_tujuan' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true, 
            ],
            'isi_paragraf' => [
                'type' => 'TEXT',
                'null' => true, 
            ],
            'tanggal_kegiatan_mulai' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'tanggal_kegiatan_selesai' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'waktu_kegiatan_mulai' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'waktu_kegiatan_selesai' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'tempat_kegiatan' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'penutup_paragraf' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'isi_lampiran' => [
                'type' => 'LONGTEXT', // Untuk menampung HTML/Tabel Editor
                'null' => true,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['Draft', 'Pending', 'Approved', 'Rejected'],
                'default' => 'Draft',
            ],
            'catatan_revisi' => [
                'type' => 'TEXT', // Dari Sekum
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        
        // Relasi Foreign Key
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('proker_id', 'program_kerja', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('kategori_id', 'kategori_template', 'id', 'SET NULL', 'CASCADE');
        
        $this->forge->createTable('surat');
    }

    public function down()
    {
        $this->forge->dropTable('surat');
    }
}

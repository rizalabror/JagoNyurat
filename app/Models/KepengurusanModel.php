<?php

namespace App\Models;

use CodeIgniter\Model;

class KepengurusanModel extends Model
{
    protected $table            = 'kepengurusan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'tahun_periode',
        'tanggal_mulai',
        'tanggal_selesai',
        'is_active',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Ambil kepengurusan yang sedang aktif (is_active = 1).
     */
    public function getAktif(): ?array
    {
        return $this->where('is_active', 1)->first();
    }

    /**
     * Ambil semua riwayat kepengurusan dari terbaru ke terlama.
     */
    public function getRiwayat(): array
    {
        return $this->orderBy('tanggal_mulai', 'DESC')->findAll();
    }
}

<?php

namespace App\Models;

use CodeIgniter\Model;

class SuratModel extends Model
{
    protected $table            = 'surat';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'public_id', 'user_id', 'proker_id', 'kategori_id', 'nomor_surat', 'lampiran',
        'perihal', 'jenis_surat', 'ttd_ketua_bem', 'ttd_dekan', 'tujuan_surat',
        'tempat_tujuan', 'isi_paragraf', 'paragraf_isi',
        'tanggal_kegiatan_mulai', 'tanggal_kegiatan_selesai',
        'waktu_kegiatan_mulai', 'waktu_kegiatan_selesai',
        'tempat_kegiatan', 'penutup_paragraf', 'isi_lampiran',
        'status', 'catatan_revisi', 'file_surat_fisik'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['generatePublicId'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Auto-generate UUID v4 sebagai public_id setiap kali surat baru dibuat.
     * Ini mencegah URL enumerasi (user tidak bisa menebak ID surat orang lain).
     */
    protected function generatePublicId(array $data): array
    {
        if (empty($data['data']['public_id'])) {
            $data['data']['public_id'] = sprintf(
                '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                mt_rand(0, 0xffff), mt_rand(0, 0xffff),
                mt_rand(0, 0xffff),
                mt_rand(0, 0x0fff) | 0x4000,
                mt_rand(0, 0x3fff) | 0x8000,
                mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
            );
        }
        return $data;
    }

    /**
     * Cari surat berdasarkan public_id (UUID) — digunakan di semua URL publik.
     */
    public function findByPublicId(string $uuid): ?array
    {
        return $this->where('public_id', $uuid)->first();
    }

    /**
     * Ambil statistik jumlah surat Approved & Rejected per bulan.
     *
     * Query SQL yang dijalankan:
     *   SELECT
     *     DATE_FORMAT(updated_at, '%Y-%m') AS bulan,
     *     SUM(status = 'Approved')         AS disetujui,
     *     SUM(status = 'Rejected')         AS direvisi
     *   FROM surat
     *   WHERE status IN ('Approved', 'Rejected')
     *     AND updated_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
     *   GROUP BY bulan
     *   ORDER BY bulan ASC
     *
     * @param  int $bulanTerakhir Rentang bulan ke belakang (default: 12)
     * @return array
     */
    public function getMonthlyStats(int $bulanTerakhir = 12): array
    {
        $db = \Config\Database::connect();

        return $db->query("
            SELECT
                DATE_FORMAT(updated_at, '%Y-%m')     AS bulan,
                SUM(status = 'Approved')              AS disetujui,
                SUM(status = 'Rejected')              AS direvisi
            FROM surat
            WHERE status IN ('Approved', 'Rejected')
              AND updated_at >= DATE_SUB(NOW(), INTERVAL ? MONTH)
            GROUP BY bulan
            ORDER BY bulan ASC
        ", [$bulanTerakhir])->getResultArray();
    }
}

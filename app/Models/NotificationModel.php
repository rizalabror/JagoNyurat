<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationModel extends Model
{
    protected $table      = 'notifications';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['user_id', 'type', 'message', 'reference_id', 'is_read', 'created_at'];
    protected $useTimestamps  = false; // created_at dikelola manual via DEFAULT CURRENT_TIMESTAMP

    /**
     * Ambil notifikasi milik user tertentu, terbaru dulu.
     */
    public function getForUser(int $userId, int $limit = 10, bool $unreadOnly = false): array
    {
        $builder = $this->where('user_id', $userId)
                        ->orderBy('created_at', 'DESC');

        if ($unreadOnly) {
            $builder->where('is_read', 0);
        }

        return $builder->findAll($limit);
    }

    /**
     * Hitung notifikasi belum dibaca milik user.
     */
    public function countUnread(int $userId): int
    {
        return $this->where('user_id', $userId)
                    ->where('is_read', 0)
                    ->countAllResults();
    }

    /**
     * Tandai satu notifikasi sebagai sudah dibaca.
     */
    public function markRead(int $id, int $userId): bool
    {
        return $this->where('id', $id)
                    ->where('user_id', $userId)
                    ->set(['is_read' => 1])
                    ->update();
    }

    /**
     * Tandai semua notifikasi milik user sebagai sudah dibaca.
     */
    public function markAllRead(int $userId): bool
    {
        return $this->where('user_id', $userId)
                    ->where('is_read', 0)
                    ->set(['is_read' => 1])
                    ->update();
    }

    /**
     * Kirim notifikasi baru.
     */
    public function send(int $userId, string $type, string $message, ?string $referenceId = null): bool
    {
        return $this->insert([
            'user_id'      => $userId,
            'type'         => $type,
            'message'      => $message,
            'reference_id' => $referenceId,
            'is_read'      => 0,
        ]) !== false;
    }
}

<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\NotificationModel;

class NotificationController extends BaseController
{
    protected $notifModel;

    public function __construct()
    {
        $this->notifModel = new NotificationModel();
    }

    /**
     * GET /notifikasi/list
     * Mengembalikan JSON daftar 10 notifikasi terbaru milik user yang sedang login.
     */
    public function list(): \CodeIgniter\HTTP\ResponseInterface
    {
        $userId  = user_id();
        $notifs  = $this->notifModel->getForUser($userId, 10);
        $unread  = $this->notifModel->countUnread($userId);

        return $this->response->setJSON([
            'unread' => $unread,
            'items'  => $notifs,
        ]);
    }

    /**
     * POST /notifikasi/baca/{id}
     * Tandai satu notifikasi sebagai sudah dibaca.
     */
    public function markRead(int $id): \CodeIgniter\HTTP\ResponseInterface
    {
        $userId = user_id();
        $this->notifModel->markRead($id, $userId);

        return $this->response->setJSON(['ok' => true]);
    }

    /**
     * POST /notifikasi/baca-semua
     * Tandai semua notifikasi sebagai sudah dibaca.
     */
    public function markAllRead(): \CodeIgniter\HTTP\ResponseInterface
    {
        $userId = user_id();
        $this->notifModel->markAllRead($userId);

        return $this->response->setJSON(['ok' => true]);
    }
}

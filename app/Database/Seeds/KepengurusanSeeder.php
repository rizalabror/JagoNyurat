<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class KepengurusanSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();

        // -------------------------------------------------------
        // 1. Cek apakah periode awal sudah ada agar tidak dobel
        // -------------------------------------------------------
        $existing = $db->table('kepengurusan')->where('tahun_periode', '2024/2025')->countAllResults();

        if ($existing > 0) {
            echo "Seeder: Periode 2024/2025 sudah ada, seeder dilewati.\n";
            return;
        }

        // -------------------------------------------------------
        // 2. Buat periode awal (kepengurusan yang sedang berjalan)
        // -------------------------------------------------------
        $db->table('kepengurusan')->insert([
            'tahun_periode'   => '2024/2025',
            'tanggal_mulai'   => '2024-08-01',
            'tanggal_selesai' => null,
            'is_active'       => 1,
            'created_at'      => date('Y-m-d H:i:s'),
            'updated_at'      => date('Y-m-d H:i:s'),
        ]);

        $kepengurusanId = $db->insertID();
        echo "Seeder: Periode 2024/2025 berhasil dibuat dengan ID: {$kepengurusanId}\n";

        // -------------------------------------------------------
        // 3. Ikat SEMUA user Sekpel & Sekum yang ada ke periode ini
        // -------------------------------------------------------
        $groupQuery = $db->table('auth_groups')
                         ->whereIn('name', ['Sekpel', 'sekpel', 'Sekum', 'sekum'])
                         ->get()->getResultArray();

        if (!empty($groupQuery)) {
            $groupIds = array_column($groupQuery, 'id');
            $userGroupRows = $db->table('auth_groups_users')
                                ->whereIn('group_id', $groupIds)
                                ->get()->getResultArray();

            if (!empty($userGroupRows)) {
                $userIds = array_column($userGroupRows, 'user_id');
                $db->table('users')
                   ->whereIn('id', $userIds)
                   ->update(['kepengurusan_id' => $kepengurusanId]);

                echo "Seeder: " . count($userIds) . " akun Sekpel/Sekum diikat ke periode 2024/2025.\n";
            }
        }

        // -------------------------------------------------------
        // 4. Ikat SEMUA program_kerja yang ada ke periode ini
        // -------------------------------------------------------
        $db->table('program_kerja')
           ->where('kepengurusan_id IS NULL')
           ->update(['kepengurusan_id' => $kepengurusanId]);

        $prokerCount = $db->affectedRows();
        echo "Seeder: {$prokerCount} program kerja diikat ke periode 2024/2025.\n";

        echo "Seeder KepengurusanSeeder SELESAI.\n";
    }
}

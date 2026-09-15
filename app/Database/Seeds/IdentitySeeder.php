<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class IdentitySeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama'    => 'Benedict Raditya Pradipta Ginting',
                'jabatan' => 'Ketua BEM Fakultas Ilmu Komputer',
                'npm'     => '2210631170058',
                'nip'     => null,
            ]
        ];
        
        $builder = $this->db->table('identity');
        foreach ($data as $row) {
            $exists = $builder->where('npm', $row['npm'])->countAllResults(false);
            if ($exists == 0) {
                $builder->insert($row);
            }
        }
    }
}

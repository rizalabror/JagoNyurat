<?php

namespace App\Models;

use CodeIgniter\Model;

class IdentityModel extends Model
{
    protected $table = 'identity';
    protected $primaryKey = 'jabatan';
    protected $returnType = 'array';
    protected $allowedFields = ['nama', 'jabatan', 'npm', 'nip', 'alamat', 'no_hp', 'email', 'website'];

    // Optional: Custom validation rules
    protected $validationRules = [
        'nama'    => 'required|max_length[255]',
        'jabatan' => 'required|max_length[64]',
        'npm'     => 'permit_empty|max_length[13]',
        'nip'     => 'permit_empty|max_length[64]',
        'alamat'  => 'permit_empty',
        'no_hp'   => 'permit_empty|max_length[15]',
        'email'   => 'permit_empty|valid_email',
        'website' => 'permit_empty'
    ];
}

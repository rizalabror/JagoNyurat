<?php

namespace App\Models;

use CodeIgniter\Model;

class OrganisasiModel extends Model
{
    protected $table      = 'kop_surat';
    protected $primaryKey = 'organisasi';

    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $allowedFields    = ['organisasi', 'sekretariat', 'nohp', 'email', 'website'];

    protected $validationRules    = [
        'organisasi'  => 'required|max_length[64]',
        'sekretariat' => 'required|max_length[64]',
        'nohp'        => 'required|max_length[64]',
        'email'       => 'required|valid_email|max_length[64]',
        'website'     => 'permit_empty|max_length[64]'
    ];

    protected $validationMessages = [];
    protected $skipValidation     = false;
}

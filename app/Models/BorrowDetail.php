<?php

namespace App\Models;

use CodeIgniter\Model;

class BorrowDetail extends Model
{
    protected $table            = 'detail_pinjam';
    protected $allowedFields    = ['id_pinjam', 'id_buku', 'status'];
    protected $useTimestamps = false;
    protected $dateFormat    = 'date';

    // protected $validationRules = [
    //     'id_pinjam' => 'required',
    //     'id_buku' => 'required'
    // ];

}

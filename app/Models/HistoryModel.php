<?php

namespace App\Models;

use CodeIgniter\Model;

class HistoryModel extends Model
{
    protected $table            = 'history';
    protected $allowedFields    = ['id_user', 'id_buku', 'id_pinjam'];
    protected $useTimestamps = false;
}

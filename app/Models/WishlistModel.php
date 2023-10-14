<?php

namespace App\Models;

use CodeIgniter\Model;

class WishlistModel extends Model
{
    protected $table            = 'wishlist';
    protected $primaryKey = 'id_list';
    protected $allowedFields    = ['id_user', 'id_buku'];
    protected $useTimestamps = false;
}

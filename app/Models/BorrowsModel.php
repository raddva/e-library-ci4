<?php

namespace App\Models;

use CodeIgniter\Model;

class BorrowsModel extends Model
{
    protected $table            = 'pinjam';
    protected $primaryKey       = 'id_pinjam';
    protected $allowedFields    = ['id_pinjam', 'id_user', 'tgl_pinjam', 'tgl_kembali'];
    protected $useTimestamps = false;
    protected $dateFormat    = 'date';
    protected $validationRules = [
        // 'tgl_pinjam' => 'valid_date',
        'id_user' => 'required',
        // 'tgl_kembali' => 'valid_date'
    ];
    public function getBorrowID()
    {
        $query = $this->db->query('SELECT MAX(id_pinjam) AS id_pinjam FROM pinjam');
        $row = $query->getRow();
        $pinjamID = $row->id_pinjam;

        return $pinjamID ? $pinjamID + 1 : 1;
    }

    public function getBorrows()
    {
        $sql = "SELECT pinjam.id_pinjam, pinjam.id_user, detail_pinjam.id_buku FROM pinjam INNER JOIN detail_pinjam ON pinjam.id_pinjam = detail_pinjam.id_pinjam";
        $query = $this->db->query($sql);
        $results = $query->getResult();
        return $results;
    }
    public function getBorrowData($id)
    {
        $sql = "SELECT pinjam.id_pinjam, pinjam.id_user, detail_pinjam.id_buku FROM pinjam INNER JOIN detail_pinjam ON pinjam.id_pinjam = detail_pinjam.id_pinjam WHERE pinjam.id_pinjam = '$id'";
        $query = $this->db->query($sql);
        $results = $query->getResult();
        return $results;
    }
}

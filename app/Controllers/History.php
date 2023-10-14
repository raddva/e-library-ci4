<?php

namespace App\Controllers;

use App\Models\HistoryModel;
use CodeIgniter\RESTful\ResourceController;

class History extends ResourceController
{
    protected $model;
    public function __construct()
    {
        $this->model = new HistoryModel();
    }
    public function getHistory($id)
    {
        $data = $this->model->select('id_buku')->where('id_pinjam', $id)->get()->getResult();
        if ($data) {
            return $this->respond(['data' => $data], 200);
        } else {
            return $this->fail(['message' => 'data is empty'], 409);
        }
    }
}

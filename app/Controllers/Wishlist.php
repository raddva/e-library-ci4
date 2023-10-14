<?php

namespace App\Controllers;

use App\Models\WishlistModel;
use CodeIgniter\RESTful\ResourceController;

class Wishlist extends ResourceController
{
    protected $model;

    public function __construct()
    {
        $this->model = new WishlistModel();
    }
    public function index()
    {
        //
    }
    public function show($id = null)
    {
        //
    }
    public function getList($id)
    {
        $data = $this->model->where('id_user', $id)->get()->getResult();
        if ($data) {
            return $this->respond(['data' => $data], 200);
        } else {
            return $this->fail(['message' => 'data is empty'], 409);
        }
    }
    public function create()
    {
        if ($this->model->insert([
            'id_user' => $this->request->getVar('id_user'),
            'id_buku' => $this->request->getVar('id_buku')
        ])) {
            return $this->respond(['message' => 'Data Successfully added.'], 200);
        } else {
            return $this->fail(['message' => 'Failed to insert data'], 409);
        }
    }
    public function edit($id = null)
    {
        //
    }
    public function update($id = null)
    {
        //
    }

    public function delete($id = null)
    {
        if ($this->model->where('id_user', $id)) {
            $buku = $this->request->getVar('id_buku');
            $this->model->where('id_buku', $buku)->delete();
            return $this->respond(['message' => 'Data Successfully deleted'], 200);
        } else {
            return $this->fail(['message' => 'cannot delete data'], 409);
        }
    }
}

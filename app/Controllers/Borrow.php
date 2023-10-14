<?php

namespace App\Controllers;

use App\Models\BooksModel;
use App\Models\BorrowDetail;
use App\Models\BorrowsModel;
use App\Models\HistoryModel;
use CodeIgniter\I18n\Time;
use CodeIgniter\RESTful\ResourceController;

class Borrow extends ResourceController
{
    protected $model;
    protected $detail;
    protected $book;
    protected $history;
    public function __construct()
    {
        $this->model = new BorrowsModel();
        $this->detail = new BorrowDetail();
        $this->book = new BooksModel();
        $this->history = new HistoryModel();
    }
    public function index()
    {
        //
    }

    public function show($id = null)
    {
        //
    }

    public function new()
    {
        //
    }

    public function create()
    {
        $idPinjam = $this->model->getBorrowID();
        $user = $this->request->getVar('id_user');
        $dataExist = $this->model->where('id_user', $user)
            ->where('tgl_kembali', null)
            ->first();
        if ($this->validate($this->model->validationRules)) {
            if ($dataExist) {
                $id = $dataExist['id_pinjam'];
            } else {
                $id = $idPinjam;
                $this->model->insert([
                    'id_pinjam'     => $id,
                    'id_user'       => $user,
                    'tgl_pinjam'    => Time::today()
                ]);
            }
            $idbuku = $this->request->getVar('id_buku');
            $this->detail->insert([
                'id_pinjam' => $id,
                'id_buku'   => $idbuku
            ]);
            $this->book->borrowed($idbuku);
            return $this->respond(['message' => 'Data Succesfully Inserted'], 200);
        } else {
            $response = [
                'errors' => $this->validator->getErrors(),
                'message' => 'Error Inserting data'
            ];
            return $this->fail($response, 409);
        }
    }

    public function edit($id = null)
    {
        //
    }

    public function update($id = null)
    {
        $buku = $this->detail->select('id_buku')->where('id_pinjam', $id)->get()->getResult();
        $user = $this->model->select('id_user')->where('id_pinjam', $id)->get()->getRow();
        if ($this->model->update($id, [
            'id_user' => $user->id_user,
            'tgl_kembali' => Time::today()
        ])) {
            foreach ($buku as $b) {
                $this->book->returned($b->id_buku);
                $this->history->insert([
                    'id_user' => $user->id_user,
                    'id_buku' => $b->id_buku,
                    'id_pinjam' => $id
                ]);
            }

            return $this->respond(['message' => 'Data Succesfully Updated'], 200);
        } else {
            $response = [
                'errors' => $this->validator->getErrors(),
                'message' => 'Error updating data'
            ];
            return $this->fail($response, 409);
        }
    }


    public function getBorrowsData()
    {
        return $this->respond(['data' => $this->model->getBorrows()], 200);
    }
    public function getDetailPinjam($id)
    {
        return $this->respond(['data' => $this->model->getBorrowData($id)], 200);
    }
}

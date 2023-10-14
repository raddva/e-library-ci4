<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\BooksModel;

class Books extends ResourceController
{
    protected $model;

    public function __construct()
    {
        $this->model = new BooksModel();
    }

    public function index()
    {
        return view('books/dashboard');
    }
    public function show($id = null)
    {
        $book = $this->model->find($id);
        return $this->respond($book, 200);
    }

    public function new()
    {
        //
    }

    public function create()
    {
        $coverFile = $this->request->getFile('gambar');
        if ($coverFile->getError() == 4) {
            $coverName = "default.jpeg";
        } else {
            $coverName = $coverFile->getName();
            $coverFile->move('covers');
        }
        $slug = url_title($this->request->getVar('judul'), '-', true);

        $bookFile = $this->request->getFile('file');
        if ($bookFile->getError() == 4) {
            $fileName = "default.docx";
        } else {
            $fileName = $bookFile->getName();
            $bookFile->move('files');
        }
        $slug = url_title($this->request->getVar('judul'), '-', true);

        if ($this->validate($this->model->validationRules)) {
            $data = [
                'id_buku' => $this->model->getIDBuku(),
                'judul' => $this->request->getVar('judul'),
                'slug' => $slug,
                'penulis' => $this->request->getVar('penulis'),
                'penerbit' => $this->request->getVar('penerbit'),
                'kategori' => $this->request->getVar('kategori'),
                'genre' => $this->request->getVar('genre'),
                'gambar' => $coverName,
                'stok' => $this->request->getVar('stok'),
                'deskripsi' => $this->request->getVar('deskripsi'),
                'file' => $fileName,
            ];

            $this->model->insert($data);
            return $this->respond(['message' => 'Books Succesfully Added'], 200);
        } else {
            $response = [
                'errors' => $this->validator->getErrors(),
                'message' => 'Invalid Inputs'
            ];
            return $this->fail($response, 409);
        }
    }
    public function find($word)
    {
        $data = $this->model->search($word);
        // print_r($data);
        if ($data) {

            return $this->respond(['buku' => $data], 200);
        } else {
            return $this->fail(['message' => "Data Not Found!"], 409);
        }
    }

    public function update($id = null)
    {

        $coverFile = $this->request->getFile('gambar');
        if ($coverFile->getError() == 4) {
            $coverName = $this->request->getVar('oldcover');
        } else {
            $coverName = $coverFile->getName();
            $coverFile->move('covers');
            unlink('covers/' . $this->request->getVar('oldcover'));
        }

        $bookFile = $this->request->getFile('file');
        if ($bookFile->getError() == 4) {
            $fileName = $this->request->getVar('oldfile');
        } else {
            $fileName = $bookFile->getName();
            $bookFile->move('files');
            unlink('files/' . $this->request->getVar('oldfile'));
        }

        $slug = url_title($this->request->getVar('judul'), '-', true);
        if ($this->validate($this->model->validationRules)) {
            $this->model->update($id, [
                'judul' => $this->request->getVar('judul'),
                'slug' => $slug,
                'penulis' => $this->request->getVar('penulis'),
                'penerbit' => $this->request->getVar('penerbit'),
                'kategori' => $this->request->getVar('kategori'),
                'genre' => $this->request->getVar('genre'),
                'gambar' => $coverName,
                'stok' => $this->request->getVar('stok'),
                'deskripsi' => $this->request->getVar('deskripsi'),
                'file' => $fileName,
            ]);
            return $this->respond(['message' => 'Book Succesfully Updated'], 200);
        } else {
            $response = [
                'errors' => $this->validator->getErrors(),
                'message' => 'Invalid Inputs'
            ];
            return $this->fail($response, 409);
        }
    }

    public function delete($id = null)
    {
        $book = $this->model->find($id);
        if ($book) {
            $this->model->delete($id);
            if ($book['gambar'] != "default.jpeg" && $book['file'] != "default.pdf") {
                unlink('covers/' . $book['gambar']);
                unlink('files/' . $book['file']);
            } else if ($book['file'] != "default.pdf") {
                unlink('files/' . $book['file']);
            } else if ($book['gambar'] != "default.jpeg") {
                unlink('covers/' . $book['gambar']);
            }
            return $this->respond(['message' => 'Book Succesfully Deleted.']);
        } else {
            return $this->fail(['message' => 'Book Data not found, can not be deleted'], 409);
        }
    }
    public function getBooks()
    {
        return $this->respond(['books' => $this->model->findAll()], 200);
    }
}

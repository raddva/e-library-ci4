<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use App\Models\AdminModel;
use \Firebase\JWT\JWT;

class Admin extends BaseController
{
    use ResponseTrait;
    protected $model;
    public function __construct()
    {
        $this->model = new AdminModel();
    }
    public function find($id)
    {
        return $this->respond(['admin' => $this->model->find($id)], 200);
    }
    // public function index()
    // {
    //     if (!$this->session->has('isLogin')) {
    //         return redirect()->to('/admin/login');
    //     }
    //     return view('admin/dashboard');
    // }
    // public function login()
    // {
    //     return view('admin/login');
    // }
    public function auth()
    {

        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');

        $admin = $this->model->where('username', $username)->first();

        if (is_null($admin)) {
            return $this->respond(['error' => 'Admin is not registered'], 401);
        }

        $pwd_verify = password_verify($password, $admin['password']);

        if (!$pwd_verify) {
            return $this->respond(['error' => 'Invalid password.'], 401);
        }

        $key = getenv('JWT_SECRET');
        $iat = time(); // current timestamp value
        $exp = $iat + 3600;

        $payload = array(
            "iss" => "Issuer of the JWT",
            "aud" => "Audience that the JWT",
            "sub" => "Subject of the JWT",
            "iat" => $iat, //Time the JWT issued at
            "exp" => $exp, // Expiration time of token
            "username" => $admin['username'],
        );

        $token = JWT::encode($payload, $key, 'HS256');

        $response = [
            'message' => 'Admin Login Successful',
            'token' => $token
        ];
        $loginSession = [
            'isLogin' => true,
            'username' => $admin['username'],
        ];
        $this->session->set($loginSession);
        return $this->respond($response, 200);
    }

    // public function logout()
    // {
    //     $this->session->destroy();
    //     return redirect()->to('user/login');
    // }
    public function getAdmins()
    {
        return $this->respond(['admin' => $this->model->findAll()], 200);
    }
}

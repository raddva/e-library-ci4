<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;
use \Firebase\JWT\JWT;
use CodeIgniter\RESTful\ResourceController;

class User extends ResourceController
{
    use ResponseTrait;
    protected $model;

    public function __construct()
    {
        $this->model = new UserModel();
    }

    // public function index()
    // {
    //     if (!$this->session->has('isLogin')) {
    //         return redirect()->to('/user/login');
    //     }
    //     return view('pages/dashboard');
    // }
    // public function login()
    // {
    //     return view('user/login');
    // }
    public function auth()
    {
        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');

        $user = $this->model->where('username', $username)->first();

        if (is_null($user)) {
            return $this->respond(['error' => 'Username is not registered'], 401);
        }

        $pwd_verify = password_verify($password, $user['password']);

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
            "iat" => $iat,
            "exp" => $exp,
            "username" => $user['username'],
        );

        $token = JWT::encode($payload, $key, 'HS256');

        $response = [
            'message' => 'User Login Succesful',
            'token' => $token
        ];
        // $loginSession = [
        //     'isLogin' => true,
        //     'username' => $user['username'],
        // ];
        // session()->set($loginSession);
        return $this->respond($response, 200);
    }

    public function register()
    {
        if ($this->validate($this->model->validationRules)) {
            $data = [
                'id_user' => $this->model->getIDUser(),
                'name' => $this->request->getVar('name'),
                'username' => $this->request->getVar('username'),
                'email' => $this->request->getVar('email'),
                'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
            ];

            $this->model->add($data);
            return $this->respond(['message' => 'User is Registered Succesfully'], 200);
        } else {
            $response = [
                'errors' => $this->validator->getErrors(),
                'message' => 'Invalid Inputs'
            ];
            return $this->fail($response, 409);
        }
    }

    public function update($id = null)
    {
        if ($this->validate($this->model->validationRules)) {
            $this->model->update($id, [
                'name' => $this->request->getVar("name"),
                'username' => $this->request->getVar("username"),
                'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
                'email' => $this->request->getVar("email"),
            ]);
            return $this->respond(['message' => 'User Data Has Been Succesfully Updated'], 200);
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
        $userData = $this->model->find($id);
        if ($userData) {
            $this->model->delete($id);
            return $this->respond(['message' => 'User Has Been Succesfully Deleted.']);
        } else {
            return $this->fail(['message' => 'User not found, can not be deleted'], 409);
        }
    }
    public function find($id)
    {
        return $this->respond(['user' => $this->model->find($id)], 200);
    }
    // public function logout()
    // {
    //     $this->session->destroy();
    //     return redirect()->to('user/login');
    // }
    public function getUsers()
    {
        return $this->respond(['users' => $this->model->findAll()], 200);
    }
}

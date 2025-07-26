<?php

namespace App\Controllers;

use App\Models\UsersModel;
use CodeIgniter\API\ResponseTrait;
use Firebase\JWT\JWT;

class Auth extends BaseController
{
    use ResponseTrait;

    protected $validation, $usersModel;

    public function __construct()
    {
        $this->validation = service('validation');
        $this->usersModel = new UsersModel();
    }

    public function register(): object
    {
        $payload = json_decode($this->request->getBody(), true);

        if (!$this->validation->run($payload, 'register')) {
            return $this->respond([
                'meta' => [
                    'code' => 400,
                    'status' => 'error',
                    'message' => $this->validation->getErrors()
                ],
                'data' => null
            ], 400);
        }

        $register = $this->usersModel->register($payload);

        if (!$register) {
            return $this->respond([
                'meta' => [
                    'code' => 500,
                    'status' => 'error',
                    'message' => 'Register Failed'
                ],
                'data' => null
            ], 500);
        }

        return $this->respond([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'Register Success'
            ],
            'data' => [
                'id' => $register,
                'fullname' => $payload['fullname'],
                'email' => $payload['email'],
                'role' => $payload['role']
            ]
        ], 200);
    }

    public function login(): object
    {
        $payload = json_decode($this->request->getBody(), true);

        if (!$this->validation->run($payload, 'login')) {
            return $this->respond([
                'meta' => [
                    'code' => 400,
                    'status' => 'error',
                    'message' => $this->validation->getErrors()
                ],
                'data' => null
            ], 400);
        }

        $login = $this->usersModel->login($payload);

        if (!$login) {
            return $this->respond([
                'meta' => [
                    'code' => 401,
                    'status' => 'error',
                    'message' => 'Email or Password Invalid'
                ],
                'data' => null
            ], 401);
        }

        $key = getenv('JWT_SECRET');
        $time = time();

        $token = JWT::encode([
            'iat' => $time,
            'exp' => $time + 3600,
            'data' => [
                'id' => $login['id'],
                'fullname' => $login['fullname'],
                'email' => $login['email'],
                'role' => $login['role']
            ]
        ], $key, 'HS256');

        return $this->respond([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'Login Success'
            ],
            'data' => $token
        ], 200);
    }

    public function me()
    {        
        return $this->respond([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'Data retrieved successfully'
            ],
            'data' => $this->request->user ?? null
        ]);
    }
}

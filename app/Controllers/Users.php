<?php

namespace App\Controllers;

use App\Models\UsersModel;
use CodeIgniter\API\ResponseTrait;

class Users extends BaseController
{
    use ResponseTrait;

    protected $usersModel;

    public function __construct()
    {
        $this->usersModel = new UsersModel();
    }

    public function index(): object
    {
        $users = $this->usersModel->select('id as user_id, fullname, email, role, created_at as joined')->findAll();

        return $this->respond([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'Data retrieved successfully'
            ],
            'data' => $users
        ], 200);
    }
}

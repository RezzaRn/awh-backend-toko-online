<?php

namespace App\Controllers;

use App\Models\OrdersModel;
use App\Models\ProductsModel;
use App\Models\UsersModel;
use CodeIgniter\API\ResponseTrait;

class Reports extends BaseController
{
    use ResponseTrait;

    protected $ordersModel, $productsModel, $usersModel;

    public function __construct()
    {
        $this->ordersModel = new OrdersModel();
        $this->productsModel = new ProductsModel();
        $this->usersModel = new UsersModel();
    }

    public function index(): object
    {
        $revenue = $this->ordersModel->getRevenue() ?? 0;
        $totalOrder = $this->ordersModel->getTotalOrder() ?? 0;
        $totalStock = $this->productsModel->getTotalStock() ?? 0;
        $totalCustomer = $this->usersModel->getTotalCustomer() ?? 0;

        return $this->respond([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'Data retrieved successfully'
            ],
            'data' => [
                'revenue' => $revenue,
                'orderSuccess' => $totalOrder,
                'totalStock' => $totalStock,
                'totalCustomer' => $totalCustomer
            ]
        ]);
    }
}

<?php

namespace App\Controllers;

use App\Models\OrdersModel;
use CodeIgniter\API\ResponseTrait;

class Orders extends BaseController
{
    use ResponseTrait;

    protected $ordersModel, $validation;

    public function __construct()
    {
        $this->ordersModel = new OrdersModel();
        $this->validation = service('validation');
    }

    public function index()
    {
        $user =  $this->request->user ?? null;
        $userId = $user->id ?? null;
        $role = $user->role ?? null;

        $orders = $this->ordersModel->getOrders($userId, $role);

        if (empty($orders)) {
            return $this->respond([
                'meta' => [
                    'code' => 404,
                    'status' => 'error',
                    'message' => 'Orders not found'
                ],
                'data' => null
            ], 404);
        }

        return $this->respond([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'Orders retrieved successfully'
            ],
            'data' => $orders
        ]);
    }

    public function checkout(): object
    {
        $userId = $this->request->user->id ?? null;

        $payload = json_decode($this->request->getBody(), true);
        $payload['user_id'] = $userId;

        if (!$this->validation->run($payload, 'checkout')) {
            return $this->respond([
                'meta' => [
                    'code' => 400,
                    'status' => 'error',
                    'message' => $this->validation->getErrors()
                ],
                'data' => null
            ], 400);
        }

        foreach ($payload['items'] as $item) {
            if (!$this->validation->run($item, 'items')) {
                return $this->respond([
                    'meta' => [
                        'code' => 400,
                        'status' => 'error',
                        'message' => $this->validation->getErrors()
                    ],
                    'data' => null
                ], 400);
            }
        }

        $checkout = $this->ordersModel->checkout($userId, $payload['payment_method'], $payload['items']);

        if (!$checkout['status']) {
            return $this->respond([
                'meta' => [
                    'code' => $checkout['code'],
                    'status' => 'error',
                    'message' => $checkout['message']
                ],
                'data' => null
            ], $checkout['code']);
        }

        return $this->respond([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'Checkout Success'
            ],
            'data' => $checkout['data']
        ], 200);
    }

    public function getOrderDetails($orderId)
    {
        $user = $this->request->user ?? null;
        $userId = $user->id ?? null;
        $role = $user->role ?? null;

        $getOrder = $this->ordersModel->getOrderDetails($orderId, $userId, $role);

        if (!$getOrder['status']) {
            return $this->respond([
                'meta' => [
                    'code' => $getOrder['code'],
                    'status' => 'error',
                    'message' => $getOrder['message']
                ],
                'data' => null
            ], $getOrder['code']);
        }

        return $this->respond([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'Data retrieved successfully'
            ],
            'data' => $getOrder['data']
        ], 200);
    }
}

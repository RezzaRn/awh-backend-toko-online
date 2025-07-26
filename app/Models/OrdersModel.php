<?php

namespace App\Models;

use CodeIgniter\Model;
use Exception;

class OrdersModel extends Model
{
    protected $table            = 'orders';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id', 'user_id', 'order_date', 'total', 'payment_method', 'status', 'created_at', 'updated_at'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];


    public function checkout($usersId, $paymentMethod, $items)
    {
        try {
            $this->db->transBegin();

            $insertOrder = $this->db->table('orders')->insert([
                'user_id' => $usersId,
                'order_date' => date('Y-m-d H:i:s'),
                'total' => 0.00,
                'payment_method' => $paymentMethod,
                'status' => 'pending'
            ]);

            if (!$insertOrder) {
                $error = $this->db->error();
                throw new Exception('Failed to checkout: ' . $error['message'], 500);
            }

            $ordersId = $this->db->insertID();
            $total = 0;

            foreach ($items as $item) {
                $products = $this->db->query('select * from products where id = ' . $item['product_id'] . ' for update')->getRowArray();

                if (empty($products)) {
                    throw new Exception('Product not found', 404);
                }

                if ($products['stock'] < $item['quantity']) {
                    throw new Exception($products['product_name'] . ' (' . $products['product_code'] . ') stock is not enough', 400);
                }

                $this->db->query('update products set stock = stock - ' . $item['quantity'] . ' where id = ' . $item['product_id']);

                $this->db->table('order_details')->insert([
                    'order_id' => $ordersId,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $products['price']
                ]);

                $total += $products['price'] * $item['quantity'];
            }

            $this->db->table('orders')->where('id', $ordersId)->update([
                'total' => $total
            ]);

            $this->db->transCommit();

            return [
                'status' => true,
                'message' => 'Order created successfully',
                'data' => [
                    'order_id' => $ordersId,
                    'total' => $total,
                    'payment_method' => $paymentMethod,
                    'status' => 'pending'
                ]
            ];
        } catch (Exception $e) {
            $this->db->transRollback();
            return [
                'code' => $e->getCode(),
                'status' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function getOrders($usersId, $role)
    {
        $orders = $this->db->table('orders')
            ->select('orders.id as order_id, fullname as customer, orders.total, orders.payment_method, orders.status,orders.order_date')
            ->join('users', 'users.id = orders.user_id');

        if ($role == 'customer') {
            $orders->where('orders.user_id', $usersId);
        }

        return $orders->get()->getResultArray();
    }

    public function getOrderDetails($orderId, $usersId, $role)
    {
        try {
            $order = $this->db->table('orders')
                ->select('orders.id as order_id, fullname as customer, orders.total, orders.payment_method, orders.status,orders.order_date')
                ->join('users', 'users.id = orders.user_id')
                ->where('orders.id', $orderId);

            if ($role == 'customer') {
                $order->where('orders.user_id', $usersId);
            }

            $order = $order->get()->getRowArray();

            if (empty($order)) {
                throw new Exception('Order not found', 404);
            }

            $orderDetails = $this->db->table('order_details')
                ->select('product_id, product_name, product_code, order_details.quantity, order_details.price')
                ->join('products', 'products.id = order_details.product_id')
                ->where('order_id', $orderId)
                ->get()->getResultArray();

            $order['items'] = $orderDetails;

            return [
                'status' => true,
                'data' => $order
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ];
        }
    }

    public function getRevenue()
    {
        return $this->db->table('orders')->select('sum(total) as revenue')->get()->getRowArray()['revenue'];
    }

    public function getTotalOrder()
    {
        return $this->db->table('orders')->where('status', 'paid')->countAllResults();
    }
}

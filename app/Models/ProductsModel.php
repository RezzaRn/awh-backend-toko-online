<?php

namespace App\Models;

use CodeIgniter\Model;
use Exception;
use ReturnTypeWillChange;

class ProductsModel extends Model
{
    protected $table            = 'products';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id', 'product_code', 'product_name', 'description', 'category', 'price', 'stock', 'status', 'created_at', 'updated_at'];

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

    public function getProducts($search = null)
    {
        $builder = $this->db->table('products')
            ->select('id as product_id, product_code, product_name, description, category, price, stock');

        if ($search) {
            $builder->groupStart();
            $builder->like('product_code', $search);
            $builder->orLike('product_name', $search);
            $builder->orLike('description', $search);
            $builder->orLike('category', $search);
            $builder->groupEnd();
        }

        return $builder->get()->getResultArray();
    }

    public function addProduct($data)
    {
        $this->insert([
            'product_code' => $data['product_code'],
            'product_name' => $data['product_name'],
            'description' => $data['description'],
            'category' => $data['category'],
            'price' => $data['price'],
            'stock' => $data['stock']
        ]);

        $data['id'] = $this->getInsertID();
        return $data;
    }

    public function updateProduct($data, $productId)
    {
        try {
            $products = $this->find($productId);

            if (empty($products)) {
                return [
                    'status' => false,
                    'message' => 'Product not found'
                ];
            }

            $this->update($productId, [
                'product_name' => $data['product_name'] ?? $products['product_name'],
                'description' => $data['description'] ?? $products['description'],
                'category' => $data['category'] ?? $products['category'],
                'price' => $data['price'] ?? $products['price'],
                'stock' => $data['stock'] ?? $products['stock']
            ]);

            $updatedProduct = $this->select('id, product_code, product_name, description, category, price, stock')->find($productId);

            return [
                'status' => true,
                'data' => $updatedProduct
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function deleteProduct($productId)
    {
        try {
            $products = $this->find($productId);

            if (empty($products)) {
                throw new Exception('Product not found', 404);
            }

            $this->delete($productId);

            return [
                'status' => true,
                'message' => 'Product deleted successfully'
            ];
        } catch (Exception $e) {
            return [
                'code' => $e->getCode(),
                'status' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function getTotalStock()
    {
        return $this->select('sum(stock) as total_stock')->get()->getRowArray()['total_stock'];
    }
}

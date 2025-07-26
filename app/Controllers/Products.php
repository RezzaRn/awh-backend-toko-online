<?php

namespace App\Controllers;

use App\Models\ProductsModel;
use CodeIgniter\API\ResponseTrait;

class Products extends BaseController
{
    use ResponseTrait;

    protected $productsModel, $validation;

    public function __construct()
    {
        $this->productsModel = new ProductsModel();
        $this->validation = service('validation');
    }

    public function index(): object
    {
        $search = $this->request->getVar('search', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? null;

        $products = $this->productsModel->getProducts($search);

        if (empty($products)) {
            return $this->respond([
                'meta' => [
                    'code' => 404,
                    'status' => 'error',
                    'message' => 'Products not found'
                ],
                'data' => null
            ], 404);
        }

        return $this->respond([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'Products retrieved successfully'
            ],
            'data' => $products
        ]);
    }


    public function addProduct(): object
    {
        $payload = json_decode($this->request->getBody(), true);

        if (!$this->validation->run($payload, 'addProduct')) {
            return $this->respond([
                'meta' => [
                    'code' => 400,
                    'status' => 'error',
                    'message' => $this->validation->getErrors()
                ],
                'data' => null
            ], 400);
        }

        $addProduct = $this->productsModel->addProduct($payload);

        if (!$addProduct) {
            return $this->respond([
                'meta' => [
                    'code' => 500,
                    'status' => 'error',
                    'message' => 'Failed to add product'
                ],
                'data' => null
            ], 500);
        }

        return $this->respond([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'Product added successfully'
            ],
            'data' => $addProduct
        ]);
    }

    public function updateProduct($productId): object
    {
        $payload = json_decode($this->request->getBody(), true);
        $payload['id'] = $productId;

        if (!$this->validation->run($payload, 'updateProduct')) {
            return $this->respond([
                'meta' => [
                    'code' => 400,
                    'status' => 'error',
                    'message' => $this->validation->getErrors()
                ],
                'data' => null
            ], 400);
        }

        $updateProduct = $this->productsModel->updateProduct($payload, $productId);

        if (!$updateProduct['status']) {
            return $this->respond([
                'meta' => [
                    'code' => 500,
                    'status' => 'error',
                    'message' => $updateProduct['message'] ?? 'Failed to update product'
                ],
                'data' => null
            ], 500);
        }

        return $this->respond([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'Product updated successfully'
            ],
            'data' => $updateProduct['data']
        ], 200);
    }

    public function deleteProduct($productId): object
    {
        $deleteProduct = $this->productsModel->deleteProduct($productId);

        if (!$deleteProduct['status']) {
            return $this->respond([
                'meta' => [
                    'code' => $deleteProduct['code'],
                    'status' => 'error',
                    'message' => $deleteProduct['message'] ?? 'Failed to delete product'
                ],
                'data' => null
            ], $deleteProduct['code']);
        }

        return $this->respond([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'Product deleted successfully'
            ],
            'data' => null
        ], 200);
    }
}

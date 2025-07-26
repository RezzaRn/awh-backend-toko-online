<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'product_code' => 'PRD001',
                'product_name' => 'Kopi Arabika',
                'description'  => 'Kopi khas pegunungan',
                'category'     => 'Minuman',
                'price'        => 25000.00,
                'stock'        => 100,
                'status'       => 1,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'product_code' => 'PRD002',
                'product_name' => 'Teh Hijau',
                'description'  => 'Teh segar dari kebun teh',
                'category'     => 'Minuman',
                'price'        => 15000.00,
                'stock'        => 50,
                'status'       => 1,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'product_code' => 'PRD003',
                'product_name' => 'Gula',
                'description'  => 'Gula pasir',
                'category'     => 'Makanan',
                'price'        => 5000.00,
                'stock'        => 150,
                'status'       => 1,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'product_code' => 'PRD004',
                'product_name' => 'Kopi Robusta',
                'description'  => 'Kopi yang paling populer',
                'category'     => 'Minuman',
                'price'        => 20000.00,
                'stock'        => 200,
                'status'       => 1,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'product_code' => 'PRD005',
                'product_name' => 'Teh Hitam',
                'description'  => 'Teh yang paling nikmat',
                'category'     => 'Minuman',
                'price'        => 12000.00,
                'stock'        => 100,
                'status'       => 1,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'product_code' => 'PRD006',
                'product_name' => 'Kopi Liberica',
                'description'  => 'Kopi yang paling langka',
                'category'     => 'Minuman',
                'price'        => 35000.00,
                'stock'        => 50,
                'status'       => 1,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'product_code' => 'PRD007',
                'product_name' => 'Teh Oolong',
                'description'  => 'Teh yang paling unik',
                'category'     => 'Minuman',
                'price'        => 18000.00,
                'stock'        => 75,
                'status'       => 1,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'product_code' => 'PRD008',
                'product_name' => 'Gula Aren',
                'description'  => 'Gula yang paling manis',
                'category'     => 'Makanan',
                'price'        => 7000.00,
                'stock'        => 200,
                'status'       => 1,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'product_code' => 'PRD009',
                'product_name' => 'Gula Kelapa',
                'description'  => 'Gula yang paling sehat',
                'category'     => 'Makanan',
                'price'        => 10000.00,
                'stock'        => 150,
                'status'       => 1,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'product_code' => 'PRD010',
                'product_name' => 'Gula Jawa',
                'description'  => 'Gula yang paling tradisional',
                'category'     => 'Makanan',
                'price'        => 8000.00,
                'stock'        => 100,
                'status'       => 1,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'product_code' => 'PRD011',
                'product_name' => 'Mie Instant',
                'description'  => 'Makanan cepat saji',
                'category'     => 'Makanan',
                'price'        => 15000.00,
                'stock'        => 200,
                'status'       => 1,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'product_code' => 'PRD012',
                'product_name' => 'Minyak Bimoli',
                'description'  => 'Minyak yang paling berkualitas',
                'category'     => 'Minyak',
                'price'        => 10000.00,
                'stock'        => 150,
                'status'       => 1,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
        ];

        // Insert multiple records
        $this->db->table('products')->insertBatch($data);
    }
}

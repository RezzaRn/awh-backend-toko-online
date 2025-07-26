<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProductsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'int',
                'constraint' => 11,
                'auto_increment' => true
            ],
            'product_code' => [
                'type' => 'varchar',
                'constraint' => 20,
                'unique' => true,
                'null' => false
            ],
            'product_name' => [
                'type' => 'varchar',
                'constraint' => 50,
                'null' => false
            ],
            'description' => [
                'type' => 'varchar',
                'constraint' => 150,
                'null' => true
            ],
            'category' => [
                'type' => 'varchar',
                'constraint' => 30,
                'null' => false
            ],
            'price' => [
                'type' => 'decimal',
                'constraint' => '10,2',
                'null' => false,
                'default' => 0
            ],
            'stock' => [
                'type' => 'int',
                'constraint' => 11,
                'null' => false,
                'default' => 0
            ],
            'status' => [
                'type' => 'boolean',
                'default' => true,
                'null' => false
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp',

        ]);
        $this->forge->addKey('id', true);        
        $this->forge->createTable('products', true, ['ENGINE' => 'InnoDB']);
    }

    public function down()
    {
        $this->forge->dropTable('products');    
    }
}

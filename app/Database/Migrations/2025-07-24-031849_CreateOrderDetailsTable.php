<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOrderDetailsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'int',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'order_id' => [
                'type' => 'int',
                'constraint' => 11,
                'null' => false,
            ],
            'product_id' => [
                'type' => 'int',
                'constraint' => 11,
                'null' => false,
            ],
            'quantity' => [
                'type' => 'int',
                'constraint' => 11,
                'null' => false,
            ],
            'price' => [
                'type' => 'decimal',
                'constraint' => '10,2',
                'null' => false,
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp',
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('order_id', 'orders', 'id');
        $this->forge->addForeignKey('product_id', 'products', 'id');
        $this->forge->createTable('order_details', true, ['ENGINE' => 'InnoDB']);
    }

    public function down()
    {
        $this->forge->dropTable('order_details');
    }
}

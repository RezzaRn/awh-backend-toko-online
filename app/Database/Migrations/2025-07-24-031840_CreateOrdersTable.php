<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'int',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type' => 'int',
                'constraint' => 11,
                'null' => false,
            ],
            'order_date' => [
                'type' => 'datetime',
                'null' => false,
            ],
            'total' => [
                'type' => 'decimal',
                'constraint' => '10,2',
                'null' => false,
                'default' => 0,
            ],
            'payment_method' => [
                'type' => 'varchar',
                'constraint' => 20,
                'null' => false,
                'default' => 'cash',
            ],
            'status' => [
                'type' => 'enum',
                'constraint' => ['pending', 'paid', 'cancelled'],
                'default' => 'pending',
                'null' => false,
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp',
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id');
        $this->forge->createTable('orders', true, ['ENGINE' => 'InnoDB']);
    }

    public function down()
    {
        $this->forge->dropTable('orders');
    }
}

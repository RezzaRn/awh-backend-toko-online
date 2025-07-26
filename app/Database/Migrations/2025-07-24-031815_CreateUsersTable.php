<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'int',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'fullname' => [
                'type' => 'varchar',
                'constraint' => 50,
                'null' => false,
            ],
            'email' => [
                'type' => 'varchar',
                'constraint' => 100,
                'unique' => true,
                'null' => false,
            ],
            'password' => [
                'type' => 'text',
                'null' => false,
            ],
            'role' => [
                'type' => 'enum',
                'constraint' => ['customer', 'admin'],
                'default' => 'customer',
                'null' => false,
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp',

        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('users', true, ['ENGINE' => 'InnoDB']);
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}

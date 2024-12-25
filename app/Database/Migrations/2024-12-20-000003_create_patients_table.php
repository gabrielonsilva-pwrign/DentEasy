<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePatientsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'unique' => true,
            ],
            'address' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'birth_date' => [
                'type' => 'DATE',
            ],
            'gender' => [
                'type' => 'ENUM',
                'constraint' => ['Masculino', 'Feminino', 'Outros'],
            ],
            'medical_history' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'cpf' => [
                'type' => 'VARCHAR',
                'constraint' => '14',
                'unique' => true,
            ],
            'mobile_phone' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
            ]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('patients');
    }

    public function down()
    {
        $this->forge->dropTable('patients');
    }
}

<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTreatmentsTable extends Migration
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
            'appointment_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
            ],
            'value' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'payment_method' => [
                'type' => 'ENUM',
                'constraint' => ['Dinheiro', 'Credito', 'Debito', 'Convenio'],
            ],
            'notes' => [
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
            'patient_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
            ]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('appointment_id', 'appointments', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('patient_id', 'patients', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('treatments');
    }

    public function down()
    {
        $this->forge->dropTable('treatments');
    }
}

<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDentalBudgetToPatients extends Migration
{
    public function up()
    {
        $this->forge->addColumn('patients', [
            'dental_budget' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'medical_history'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('patients', 'dental_budget');
    }
}

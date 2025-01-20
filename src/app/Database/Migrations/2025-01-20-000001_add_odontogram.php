<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddOdontogramToPatients extends Migration
{
    public function up()
    {
        $this->forge->addColumn('patients', [
            'odontogram_data' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'medical_history'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('patients', 'odontogram_data');
    }
}

<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTreatmentsParams extends Migration
{
    public function up()
    {
        $this->db->disableForeignKeyChecks();
        $this->forge->dropForeignKey('treatments','treatments_patient_id_foreign');
        $this->forge->dropColumn('treatments','patient_id');
        $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        $this->forge->addColumn('treatments', 'patient_id', ['type' => 'INT', 'constraint' => 11, 'unsigned' => true]);
    }
}

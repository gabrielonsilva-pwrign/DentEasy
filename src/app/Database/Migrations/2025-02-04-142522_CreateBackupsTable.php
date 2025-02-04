<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBackupsTable extends Migration
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
            'filename' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['Pendente', 'Processando', 'Completo', 'Falhou'],
                'default' => 'Pendente',
            ],
            'type' => [
                'type' => 'ENUM',
                'constraint' => ['Agendado', 'Manual'],
                'default' => 'Manual',
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('backups');
    }

    public function down()
    {
        $this->forge->dropTable('backups');
    }
}

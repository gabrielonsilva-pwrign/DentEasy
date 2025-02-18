<?php

namespace App\Models;

use CodeIgniter\Model;

class BackupModel extends Model
{
    protected $table = 'backups';
    protected $primaryKey = 'id';
    protected $allowedFields = ['filename', 'created_at', 'status', 'type','updated_at'];
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';

    protected $validationRules = [
        'filename' => 'required|max_length[255]',
        'status' => 'required|in_list[Pendente,Processando,Completo,Falhou]',
        'type' => 'required|in_list[Agendado,Manual]',
    ];

    public function getBackups($limit = 10, $offset = 0)
    {
        return $this->orderBy('created_at', 'DESC')
                    ->findAll($limit, $offset);
    }

    public function updateBackup($filename, $status)    {
        $query = $this->db->table('backups')
                 ->set('status', $status)
                 ->where('filename', $filename)
                 ->update();

        log_message('info', $query);

        return $query;
    }
}

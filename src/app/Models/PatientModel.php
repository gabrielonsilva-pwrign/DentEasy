<?php

namespace App\Models;

use CodeIgniter\Model;

class PatientModel extends Model
{
    protected $table = 'patients';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'email', 'cpf', 'gender', 'birth_date', 'mobile_phone', 'address', 'medical_history', 'odontogram_data','dental_budget'];
    protected $useTimestamps = true;
    protected $validationRules = [
        'name' => 'required|min_length[3]|max_length[255]',
        'email' => 'required|valid_email',
        'cpf' => 'required|exact_length[14]|is_unique[patients.cpf,id,{id}]',
        'gender' => 'required|in_list[Masculino,Feminino,Outros]',
        'birth_date' => 'required|valid_date',
        'mobile_phone' => 'required|min_length[16]|max_length[16]',
        'id' => 'max_length[19]'
    ];

    protected $beforeInsert = ['formatCPFAndPhone'];
    protected $beforeUpdate = ['formatCPFAndPhone'];

    protected function formatCPFAndPhone(array $data)
    {
        if (isset($data['data']['cpf'])) {
            $data['data']['cpf'] = preg_replace('/[^0-9]/', '', $data['data']['cpf']);
        }
        if (isset($data['data']['mobile_phone'])) {
            $data['data']['mobile_phone'] = preg_replace('/[^0-9]/', '', $data['data']['mobile_phone']);
        }
        return $data;
    }

    public function search($keyword, $perPage = 20, $page = 1, $orderBy = 'id', $orderDir = 'asc')
    {
        return $this->like('name', $keyword)
                    ->orLike('email', $keyword)
                    ->orLike('cpf', $keyword)
                    ->orderBy($orderBy, $orderDir)
                    ->paginate($perPage, 'default', $page);
    }

    public function getAge($id)
    {
        $patient = $this->find($id);
        if ($patient && $patient['birth_date']) {
            $birthDate = new \DateTime($patient['birth_date']);
            $today = new \DateTime('today');
            return $birthDate->diff($today)->y;
        }
        return null;
    }
}

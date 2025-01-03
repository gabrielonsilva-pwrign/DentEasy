<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'email', 'password', 'group_id'];
    protected $useTimestamps = true;
    protected $validationRules = [
        'name' => 'required|min_length[3]|max_length[255]',
        'email' => 'required|valid_email|is_unique[users.email,id,{id}]',
        'password' => 'required|min_length[6]',
        'group_id' => 'required|numeric',
        'id' => 'max_length[19]'
    ];

    public function getPermissions($userId)
    {
        $user = $this->find($userId);
        if (!$user) {
            return [];
        }

        $groupModel = new GroupModel();
        return $groupModel->getPermissionsArray($user['group_id']);
    }
}

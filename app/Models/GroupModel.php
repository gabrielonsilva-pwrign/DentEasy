<?php

namespace App\Models;

use CodeIgniter\Model;

class GroupModel extends Model
{
    protected $table = 'groups';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'permissions'];
    protected $useTimestamps = true;

    protected $validationRules = [
        'name' => 'required|min_length[3]|max_length[255]|is_unique[groups.name,groups.id, {id}]',
        'permissions' => 'required',
        'id' => 'max_length[19]'
    ];

    public function getPermissionsArray($id)
    {
        $group = $this->find($id);
        return $group ? json_decode($group['permissions'], true) : [];
    }
}

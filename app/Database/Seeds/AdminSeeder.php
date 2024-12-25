<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Create Admin Group
        $groupModel = new \App\Models\GroupModel();
        $groupId = $groupModel->insert([
            'name' => 'Admin',
            'permissions' => json_encode([
                'users' => ['view', 'add', 'edit', 'delete'],
                'groups' => ['view', 'add', 'edit', 'delete'],
                'patients' => ['view', 'add', 'edit', 'delete'],
                'appointments' => ['view', 'add', 'edit', 'delete'],
                'treatments' => ['view', 'add', 'edit', 'delete'],
                'inventory' => ['view', 'add', 'edit', 'delete'],
                'api' => ['view', 'add', 'edit', 'delete'],
                'dashboard' => ['view', 'add', 'edit', 'delete'],
                'patients' => ['view', 'add', 'edit', 'delete']
            ])
        ]);

        // Create Admin User
        $userModel = new \App\Models\UserModel();
        $userModel->insert([
            'name' => getenv('admin_name'),
            'email' => getenv('admin_email'),
            'password' => password_hash(getenv('admin_password'), PASSWORD_DEFAULT),
            'group_id' => $groupId
        ]);

        echo "Admin group and user created successfully.\n";
    }
}

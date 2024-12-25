<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\GroupModel;

class Users extends BaseController
{
    protected $userModel;
    protected $groupModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->groupModel = new GroupModel();
    }

    public function index()
    {
        $data['users'] = $this->userModel->findAll();
        return view('users/index', $data);
    }

    public function new()
    {
        $data['groups'] = $this->groupModel->findAll();
        return view('users/create', $data);
    }

    public function create()
    {
        $data = $this->request->getPost();
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        if ($this->userModel->insert($data)) {
            return redirect()->to('/users')->with('success', 'Usuário criado');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->userModel->errors());
        }
    }

    public function edit($id = null)
    {
        $data['user'] = $this->userModel->find($id);
        $data['groups'] = $this->groupModel->findAll();
        return view('users/edit', $data);
    }

    public function update($id = null)
    {
        $data = $this->request->getPost();
        if (!empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['password']);
        }

        if ($this->userModel->update($id, $data)) {
            return redirect()->to('/users')->with('success', 'Usuário atualizado');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->userModel->errors());
        }
    }

    public function delete($id = null)
    {
        if ($this->userModel->delete($id)) {
            return redirect()->to('/users')->with('success', 'Usuário excluído');
        } else {
            return redirect()->back()->with('error', 'Falha ao excluir usuário');
        }
    }
}

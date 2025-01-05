<?php

namespace App\Controllers;

use App\Models\GroupModel;

class Groups extends BaseController
{
    protected $groupModel;

    public function __construct()
    {
        $this->groupModel = new GroupModel();
    }

    public function index()
    {
        $this->checkPermission('groups','view');
        $data['groups'] = $this->groupModel->findAll();
        return view('groups/index', $data);
    }

    public function new()
    {
        $this->checkPermission('groups','add');
        return view('groups/create');
    }

    public function create()
    {
        $this->checkPermission('groups','add');
        $data = $this->request->getPost();
        $data['permissions'] = json_encode($this->request->getPost('permissions'));

        if ($this->groupModel->insert($data)) {
            return redirect()->to('/groups')->with('success', 'Grupo adicionado');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->groupModel->errors());
        }
    }

    public function edit($id = null)
    {
        $this->checkPermission('groups','edit');
        $data['group'] = $this->groupModel->find($id);
        $data['group']['permissions'] = json_decode($data['group']['permissions'], true);
        return view('groups/edit', $data);
    }

    public function update($id = null)
    {
        $this->checkPermission('groups','edit');
        $data = $this->request->getPost();
        $data['permissions'] = json_encode($this->request->getPost('permissions'));
        $data['id'] = $id;

        if ($this->groupModel->update($id, $data)) {
            return redirect()->to('/groups')->with('success', 'Grupo atualizado');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->groupModel->errors());
        }
    }

    public function delete($id = null)
    {
        $this->checkPermission('groups','delete');
        if ($this->groupModel->delete($id)) {
            return redirect()->to('/groups')->with('success', 'Grupo excluído');
        } else {
            return redirect()->back()->with('error', 'Falha ao excluir grupo');
        }
    }
}

<?php

namespace App\Controllers;

use App\Models\InventoryModel;
use App\Models\InventoryHistoryModel;

class Inventory extends BaseController
{
    protected $inventoryModel;
    protected $inventoryHistoryModel;

    public function __construct()
    {
        $this->inventoryModel = new InventoryModel();
        $this->inventoryHistoryModel = new InventoryHistoryModel();
    }

    public function index()
    {
        $this->checkPermission('inventory','view');
        $keyword = $this->request->getGet('search') ?? '';
        $page = $this->request->getGet('page') ?? 1;
        $perPage = 20;
        $orderBy = $this->request->getGet('order_by') ?? 'name';
        $orderDir = $this->request->getGet('order_dir') ?? 'asc';

        $data['inventory'] = $this->inventoryModel->search($keyword, $perPage, $page, $orderBy, $orderDir);
        $data['pager'] = $this->inventoryModel->pager;
        $data['lowStockItems'] = $this->inventoryModel->getLowStockItems();

        return view('inventory/index', $data);
    }

    public function new()
    {
        $this->checkPermission('inventory','add');
        return view('inventory/create');
    }

    public function create()
    {
        $this->checkPermission('inventory','add');
        $data = $this->request->getPost();

        if ($this->inventoryModel->insert($data)) {
            $this->inventoryHistoryModel->insert([
                'inventory_id' => $this->inventoryModel->getInsertID(),
                'action' => 'Adicionado',
                'quantity' => $data['quantity'],
                'notes' => 'Estoque inicial'
            ]);
            return redirect()->to('/inventory')->with('success', 'Item adicionado');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->inventoryModel->errors());
        }
    }

    public function edit($id = null)
    {
        $this->checkPermission('inventory','edit');
        $data['item'] = $this->inventoryModel->find($id);
        return view('inventory/edit', $data);
    }

    public function update($id = null)
    {
        $this->checkPermission('inventory','edit');
        $data = $this->request->getPost();
        $oldItem = $this->inventoryModel->find($id);

        if ($this->inventoryModel->update($id, $data)) {
            if ($oldItem['quantity'] != $data['quantity']) {
                $this->inventoryHistoryModel->insert([
                    'inventory_id' => $id,
                    'action' => $oldItem['quantity'] < $data['quantity'] ? 'Adicionado' : 'Removido',
                    'quantity' => abs($data['quantity'] - $oldItem['quantity']),
                    'notes' => 'Atualizacão de estoque'
                ]);
            }
            return redirect()->to('/inventory')->with('success', 'Item atualizado');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->inventoryModel->errors());
        }
    }

    public function delete($id = null)
    {
        $this->checkPermission('inventory','delete');
        if ($this->inventoryModel->delete($id)) {
            return redirect()->to('/inventory')->with('success', 'Item excluído');
        } else {
            return redirect()->back()->with('error', 'Falha ao deletar item');
        }
    }

    public function history($id = null)
    {
        $this->checkPermission('inventory','view');
        $data['item'] = $this->inventoryModel->find($id);
        $data['history'] = $this->inventoryHistoryModel->getHistoryForItem($id);
        return view('inventory/history', $data);
    }
}

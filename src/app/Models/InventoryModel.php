<?php

namespace App\Models;

use CodeIgniter\Model;

class InventoryModel extends Model
{
    protected $table = 'inventory';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'description', 'quantity', 'unit', 'purchase_price', 'low_stock_threshold'];
    protected $useTimestamps = true;
    protected $validationRules = [
        'name' => 'required|min_length[3]|max_length[255]',
        'quantity' => 'required|numeric',
        'unit' => 'required|max_length[50]',
        'purchase_price' => 'required|numeric',
        'low_stock_threshold' => 'required|numeric'
    ];

    public function search($keyword, $perPage = 20, $page = 1, $orderBy = 'name', $orderDir = 'asc')
    {
        return $this->like('name', $keyword)
                    ->orLike('description', $keyword)
                    ->orderBy($orderBy, $orderDir)
                    ->paginate($perPage, 'default', $page);
    }

    public function getLowStockItems()
    {
        return $this->where('quantity <= low_stock_threshold')->findAll();
    }
}

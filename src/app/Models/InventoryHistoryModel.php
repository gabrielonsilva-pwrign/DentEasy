<?php

namespace App\Models;

use CodeIgniter\Model;

class InventoryHistoryModel extends Model
{
    protected $table = 'inventory_history';
    protected $primaryKey = 'id';
    protected $allowedFields = ['inventory_id', 'action', 'quantity', 'notes'];
    protected $useTimestamps = true;

    public function getHistoryForItem($inventoryId, $limit = 10)
    {
        return $this->where('inventory_id', $inventoryId)
                    ->orderBy('created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }
}

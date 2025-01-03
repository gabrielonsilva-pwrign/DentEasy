<?php

namespace App\Models;

use CodeIgniter\Model;

class TreatmentItemModel extends Model
{
    protected $table = 'treatment_items';
    protected $primaryKey = 'id';
    protected $allowedFields = ['treatment_id', 'inventory_id', 'quantity'];
    protected $useTimestamps = true;
    protected $validationRules = [
        'treatment_id' => 'required|integer',
        'inventory_id' => 'required|integer',
        'quantity' => 'required|integer|greater_than[0]'
    ];

    public function getTreatmentItems($treatmentId)
    {
        return $this->select('treatment_items.*, inventory.name')
                    ->join('inventory', 'inventory.id = treatment_items.inventory_id')
                    ->where('treatment_id', $treatmentId)
                    ->findAll();
    }
}

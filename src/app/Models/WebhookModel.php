<?php

namespace App\Models;

use CodeIgniter\Model;

class WebhookModel extends Model
{
    protected $table = 'webhooks';
    protected $primaryKey = 'id';
    protected $allowedFields = ['url', 'is_active'];
    protected $useTimestamps = true;

    protected $validationRules = [
        'url' => 'required|valid_url',
        'is_active' => 'required|in_list[0,1]',
    ];

    public function getActiveWebhook()
    {
        return $this->where('is_active', 1)->first();
    }
}

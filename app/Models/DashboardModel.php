<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Cache\CacheInterface;

class DashboardModel extends Model
{
    protected $cache;

    public function __construct()
    {
        parent::__construct();
        $this->cache = \Config\Services::cache();
    }

    public function getTreatmentsSum($startDate, $endDate)
    {
        $cacheKey = "treatments_sum_{$startDate}_{$endDate}";
        
        $result = $this->cache->get($cacheKey);
        
        if ($result === null) {
            $db = \Config\Database::connect();
            $query = $db->query("
                SELECT SUM(value) as total
                FROM treatments
                WHERE created_at BETWEEN ? AND ?
            ", [$startDate, $endDate . " 23:59"]);

            $result = $query->getRow();
            $total = $result ? $result->total : 0;

            // Cache the result for 1 hour (3600 seconds)
            $this->cache->save($cacheKey, $total, 3600);

            return $total;
        }

        return $result;
    }

    public function getWeekAppointments()
    {
        $cacheKey = "week_appointments_" . date('Y-m-d');
        
        $result = $this->cache->get($cacheKey);
        
        if ($result === null) {
            $db = \Config\Database::connect();
            $query = $db->query("
                SELECT a.*, p.name as patient_name
                FROM appointments a
                JOIN patients p ON a.patient_id = p.id
                WHERE a.start_time BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 7 DAY)
                ORDER BY a.start_time ASC
            ");

            $result = $query->getResult();

            // Cache the result for 1 hour (3600 seconds)
            $this->cache->save($cacheKey, $result, 3600);
        }

        return $result;
    }

    public function getInventoryAdditionsSum($startDate, $endDate)
    {
        $cacheKey = "inventory_additions_sum_{$startDate}_{$endDate}";
        
        $result = $this->cache->get($cacheKey);
        
        if ($result === null) {
            $db = \Config\Database::connect();
            $query = $db->query("
                SELECT SUM(INVENTORY.purchase_price) as total
                FROM inventory_history AS HISTORY
                LEFT JOIN inventory AS INVENTORY
                ON HISTORY.inventory_id = INVENTORY.id
                WHERE action = 'Adicionado' AND HISTORY.created_at BETWEEN ? AND ?
            ", [$startDate, $endDate . " 23:59"]);

            $result = $query->getRow();
            $total = $result ? $result->total : 0;

            // Cache the result for 1 hour (3600 seconds)
            $this->cache->save($cacheKey, $total, 3600);

            return $total;
        }

        return $result;
    }
}

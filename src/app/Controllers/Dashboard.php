<?php

namespace App\Controllers;

use App\Models\DashboardModel;
use CodeIgniter\Cache\CacheInterface;

class Dashboard extends BaseController
{
    protected $dashboardModel;
    protected $cache;

    public function __construct()
    {
        $this->dashboardModel = new DashboardModel();
        $this->cache = \Config\Services::cache();
    }

    public function index()
    {
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-d', strtotime('-30 days'));
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-d');

        $cacheKey = "dashboard_data_{$startDate}_{$endDate}";

        $data = $this->cache->get($cacheKey);

        if ($data === null) {
            $data = [
                'startDate' => $startDate,
                'endDate' => $endDate,
                'treatmentsSum' => $this->dashboardModel->getTreatmentsSum($startDate, $endDate),
                'weekAppointments' => $this->dashboardModel->getWeekAppointments(),
                'inventoryAdditionsSum' => $this->dashboardModel->getInventoryAdditionsSum($startDate, $endDate),
            ];

            $this->cache->save($cacheKey, $data, 3600);
        }

        return view('dashboard/index', $data);
    }

    public function clearCache()
    {
        $this->cache->clean();
        return redirect()->to('/dashboard')->with('message', 'Cache atualizado com sucesso');
    }
}

<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Models\UserModel;

class PermissionFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        $userId = $session->get('user_id');

        if (!$userId) {
            return redirect()->to('/login');
        }

        $userModel = new UserModel();
        $permissions = $userModel->getPermissions($userId);

        $uri = $request->getUri();
        $segment1 = $uri->getSegment(1);
        $segment2 = $uri->getSegment(2);

        $module = $segment1;
        $action = $this->getAction($segment2, $request->getMethod());

        if (!isset($permissions[$module]) || !in_array($action, $permissions[$module])) {
            return redirect()->back()->with('error', 'Você não tem essa permissão.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }

    private function getAction($segment, $method)
    {
        if ($segment === 'new' || $method === 'post') {
            return 'add';
        } elseif ($segment === 'edit' || $method === 'put') {
            return 'edit';
        } elseif ($segment === 'delete') {
            return 'delete';
        }
        return 'view';
    }
}

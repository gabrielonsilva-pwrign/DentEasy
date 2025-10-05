<?php

use App\Controllers\Groups;
use App\Models\GroupModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Config\Services;

final class GroupsTest extends CIUnitTestCase
{
    public function testCreateEncodesPermissionsJson(): void
    {
        session()->set('user_id', 1);
        $controller = new class extends Groups {
            protected function checkPermission($m,$a) { /* no-op in tests */ }
        };

        $model = $this->createMock(GroupModel::class);
        $model->method('insert')->willReturn(true);

        (new ReflectionProperty(Groups::class, 'groupModel'))->setAccessible(true);
        (new ReflectionProperty(Groups::class, 'groupModel'))->setValue($controller, $model);

        $request = Services::request();
        $request->setMethod('post');
        $request->setGlobal('post', [
            'name' => 'Admins',
            'permissions' => [
                'users' => ['view','add','edit','delete'],
                'patients' => ['view']
            ]
        ]);
        (new ReflectionProperty(Groups::class, 'request'))->setAccessible(true);
        (new ReflectionProperty(Groups::class, 'request'))->setValue($controller, $request);

        $response = $controller->create();
        $this->assertSame(302, $response->getStatusCode());
    }
}



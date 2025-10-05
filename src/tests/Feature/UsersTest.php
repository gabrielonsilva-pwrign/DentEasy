<?php

use App\Controllers\Users;
use App\Models\UserModel;
use App\Models\GroupModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Config\Services;

final class UsersTest extends CIUnitTestCase
{
    public function testCreateHashesPassword(): void
    {
        session()->set('user_id', 1);

        $controller = new class extends Users {
            protected function checkPermission($m,$a) { /* no-op in tests */ }
        };

        $userModel = $this->createMock(UserModel::class);
        $userModel->method('insert')->willReturn(true);

        $groupModel = $this->createMock(GroupModel::class);
        $groupModel->method('findAll')->willReturn([]);

        (new ReflectionProperty(Users::class, 'userModel'))->setAccessible(true);
        (new ReflectionProperty(Users::class, 'groupModel'))->setAccessible(true);
        (new ReflectionProperty(Users::class, 'userModel'))->setValue($controller, $userModel);
        (new ReflectionProperty(Users::class, 'groupModel'))->setValue($controller, $groupModel);

        $request = Services::request();
        $request->setMethod('post');
        $request->setGlobal('post', [
            'name' => 'Tester',
            'email' => 'tester@example.com',
            'password' => 'secret',
            'group_id' => 2,
        ]);
        (new ReflectionProperty(Users::class, 'request'))->setAccessible(true);
        (new ReflectionProperty(Users::class, 'request'))->setValue($controller, $request);

        $response = $controller->create();
        $this->assertSame(302, $response->getStatusCode());
    }

    public function testUpdateKeepsPasswordWhenEmpty(): void
    {
        session()->set('user_id', 1);
        $controller = new class extends Users {
            protected function checkPermission($m,$a) { /* no-op in tests */ }
        };

        $userModel = $this->createMock(UserModel::class);
        $userModel->method('update')->willReturn(true);
        $groupModel = $this->createMock(GroupModel::class);

        (new ReflectionProperty(Users::class, 'userModel'))->setAccessible(true);
        (new ReflectionProperty(Users::class, 'groupModel'))->setAccessible(true);
        (new ReflectionProperty(Users::class, 'userModel'))->setValue($controller, $userModel);
        (new ReflectionProperty(Users::class, 'groupModel'))->setValue($controller, $groupModel);

        $request = Services::request();
        $request->setMethod('post');
        $request->setGlobal('post', [
            'name' => 'Tester',
            'email' => 'tester@example.com',
            'password' => '',
            'group_id' => 2,
        ]);
        (new ReflectionProperty(Users::class, 'request'))->setAccessible(true);
        (new ReflectionProperty(Users::class, 'request'))->setValue($controller, $request);

        $response = $controller->update(1);
        $this->assertSame(302, $response->getStatusCode());
    }

    public function testDeleteUser(): void
    {
        session()->set('user_id', 1);
        $controller = new class extends Users {
            protected function checkPermission($m,$a) { /* no-op in tests */ }
        };

        $userModel = $this->createMock(UserModel::class);
        $userModel->method('delete')->willReturn(true);
        (new ReflectionProperty(Users::class, 'userModel'))->setAccessible(true);
        (new ReflectionProperty(Users::class, 'userModel'))->setValue($controller, $userModel);

        $controller->initController(service('request'), service('response'), service('logger'));
        $response = $controller->delete(1);
        $this->assertSame(302, $response->getStatusCode());
        $this->assertStringContainsString('/users', $response->getHeaderLine('Location'));
    }
}



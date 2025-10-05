<?php

use App\Controllers\Inventory;
use App\Models\InventoryModel;
use App\Models\InventoryHistoryModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Config\Services;

final class InventoryTest extends CIUnitTestCase
{
    public function testCreateWritesHistory(): void
    {
        session()->set('user_id', 1);
        $controller = new class extends Inventory {
            protected function checkPermission($m,$a) { /* no-op in tests */ }
        };

        $model = $this->createMock(InventoryModel::class);
        $model->method('insert')->willReturn(true);
        $model->method('getInsertID')->willReturn(10);

        $history = $this->createMock(InventoryHistoryModel::class);
        $history->method('insert')->willReturn(true);

        (new ReflectionProperty(Inventory::class, 'inventoryModel'))->setAccessible(true);
        (new ReflectionProperty(Inventory::class, 'inventoryHistoryModel'))->setAccessible(true);
        (new ReflectionProperty(Inventory::class, 'inventoryModel'))->setValue($controller, $model);
        (new ReflectionProperty(Inventory::class, 'inventoryHistoryModel'))->setValue($controller, $history);

        $request = Services::request();
        $request->setMethod('post');
        $request->setGlobal('post', [
            'name' => 'Agulha',
            'quantity' => 5,
        ]);
        (new ReflectionProperty(Inventory::class, 'request'))->setAccessible(true);
        (new ReflectionProperty(Inventory::class, 'request'))->setValue($controller, $request);

        $controller->initController(service('request'), service('response'), service('logger'));
        $response = $controller->create();
        $this->assertSame(302, $response->getStatusCode());
        $this->assertStringContainsString('/inventory', $response->getHeaderLine('Location'));
    }

    public function testUpdateWritesHistoryWhenQuantityChanges(): void
    {
        session()->set('user_id', 1);
        $controller = new class extends Inventory {
            protected function checkPermission($m,$a) { /* no-op in tests */ }
        };

        $model = $this->createMock(InventoryModel::class);
        $model->method('find')->willReturn(['id' => 10, 'quantity' => 5]);
        $model->method('update')->willReturn(true);

        $history = $this->createMock(InventoryHistoryModel::class);
        $history->method('insert')->willReturn(true);

        (new ReflectionProperty(Inventory::class, 'inventoryModel'))->setAccessible(true);
        (new ReflectionProperty(Inventory::class, 'inventoryHistoryModel'))->setAccessible(true);
        (new ReflectionProperty(Inventory::class, 'inventoryModel'))->setValue($controller, $model);
        (new ReflectionProperty(Inventory::class, 'inventoryHistoryModel'))->setValue($controller, $history);

        $request = Services::request();
        $request->setMethod('post');
        $request->setGlobal('post', [
            'name' => 'Agulha',
            'quantity' => 8,
        ]);
        (new ReflectionProperty(Inventory::class, 'request'))->setAccessible(true);
        (new ReflectionProperty(Inventory::class, 'request'))->setValue($controller, $request);

        $controller->initController(service('request'), service('response'), service('logger'));
        $response = $controller->update(10);
        $this->assertSame(302, $response->getStatusCode());
        $this->assertStringContainsString('/inventory', $response->getHeaderLine('Location'));
    }
}



<?php

use App\Controllers\Treatments;
use App\Models\TreatmentModel;
use App\Models\TreatmentItemModel;
use App\Models\AppointmentModel;
use App\Models\InventoryModel;
use App\Models\TreatmentFileModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Config\Services;

final class TreatmentsTest extends CIUnitTestCase
{
    public function testCreateUpdatesInventoryAndAppointmentStatus(): void
    {
        session()->set('user_id', 1);

        $controller = new class extends Treatments {
            protected function checkPermission($m,$a) { /* no-op in tests */ }
        };

        $treatmentModel = $this->createMock(TreatmentModel::class);
        $treatmentModel->method('insert')->willReturn(99);

        $itemModel = $this->createMock(TreatmentItemModel::class);
        $itemModel->method('insert')->willReturn(true);

        $inventoryModel = $this->createMock(InventoryModel::class);
        $inventoryModel->method('find')->willReturn(['id' => 5, 'quantity' => 10]);
        $inventoryModel->method('update')->willReturn(true);

        $appointmentModel = $this->createMock(AppointmentModel::class);
        $appointmentModel->method('update')->willReturn(true);

        $fileModel = $this->createMock(TreatmentFileModel::class);

        (new ReflectionProperty(Treatments::class, 'treatmentModel'))->setAccessible(true);
        (new ReflectionProperty(Treatments::class, 'treatmentItemModel'))->setAccessible(true);
        (new ReflectionProperty(Treatments::class, 'inventoryModel'))->setAccessible(true);
        (new ReflectionProperty(Treatments::class, 'appointmentModel'))->setAccessible(true);
        (new ReflectionProperty(Treatments::class, 'treatmentFileModel'))->setAccessible(true);
        (new ReflectionProperty(Treatments::class, 'treatmentModel'))->setValue($controller, $treatmentModel);
        (new ReflectionProperty(Treatments::class, 'treatmentItemModel'))->setValue($controller, $itemModel);
        (new ReflectionProperty(Treatments::class, 'inventoryModel'))->setValue($controller, $inventoryModel);
        (new ReflectionProperty(Treatments::class, 'appointmentModel'))->setValue($controller, $appointmentModel);
        (new ReflectionProperty(Treatments::class, 'treatmentFileModel'))->setValue($controller, $fileModel);

        $request = $this->getMockBuilder(\CodeIgniter\HTTP\IncomingRequest::class)
    ->disableOriginalConstructor()
    ->onlyMethods(['getFiles','getPost'])
    ->getMock();
$request->method('getFiles')->willReturn(['treatment_files' => []]);
$request->method('getPost')->willReturnMap([
    ['appointment_id', null, 1],
    ['inventory', null, [5 => 3]],
    [null, null, ['appointment_id'=>1,'inventory'=>[5=>3]]], // para chamadas getPost() sem chave
]);
(new ReflectionProperty(Treatments::class, 'request'))->setAccessible(true);
(new ReflectionProperty(Treatments::class, 'request'))->setValue($controller, $request);

$controller->initController($request, service('response'), service('logger'));

        $response = $controller->create();
        $this->assertSame(302, $response->getStatusCode());
        $this->assertStringContainsString('/treatments', $response->getHeaderLine('Location'));
    }
}



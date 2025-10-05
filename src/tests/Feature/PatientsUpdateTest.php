<?php

use App\Controllers\Patients;
use App\Models\PatientModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Config\Services;

final class PatientsUpdateTest extends CIUnitTestCase
{
    public function testUpdateWithOdontogramData(): void
    {
        session()->set('user_id', 1);

        $controller = new class extends Patients {
            protected function checkPermission($m,$a) { /* no-op in tests */ }
        };

        $model = $this->createMock(PatientModel::class);
        $model->method('update')->willReturn(true);

        $prop = new ReflectionProperty(Patients::class, 'patientModel');
        $prop->setAccessible(true);
        $prop->setValue($controller, $model);

        $request = Services::request();
        $request->setMethod('post');
        $request->setGlobal('post', [
            'name' => 'Novo Nome',
            'odontogram_data' => json_encode(['tooth_21' => ['restoration' => true]]),
        ]);

        $controller->initController(service('request'), service('response'), service('logger'));
        $response = $controller->update(5);
        $this->assertSame(302, $response->getStatusCode());
        $this->assertStringContainsString('/patients', $response->getHeaderLine('Location'));
    }
}



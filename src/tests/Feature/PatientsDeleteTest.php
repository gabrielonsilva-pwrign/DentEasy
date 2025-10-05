<?php

use App\Controllers\Patients;
use App\Models\PatientModel;
use CodeIgniter\Test\CIUnitTestCase;

final class PatientsDeleteTest extends CIUnitTestCase
{
    public function testDeleteSuccessRedirects(): void
    {
        session()->set('user_id', 1);

        $controller = new class extends Patients {
            protected function checkPermission($m,$a) { /* no-op in tests */ }
        };

        $model = $this->createMock(PatientModel::class);
        $model->method('delete')->willReturn(true);

        $prop = new ReflectionProperty(Patients::class, 'patientModel');
        $prop->setAccessible(true);
        $prop->setValue($controller, $model);

        $controller->initController(service('request'), service('response'), service('logger'));
        $response = $controller->delete(7);
        $this->assertSame(302, $response->getStatusCode());
        $this->assertStringContainsString('/patients', $response->getHeaderLine('Location'));
    }

    public function testDeleteFailureRedirectsBack(): void
    {
        session()->set('user_id', 1);

        $controller = new class extends Patients {
            protected function checkPermission($m,$a) { /* no-op in tests */ }
        };

        $model = $this->createMock(PatientModel::class);
        $model->method('delete')->willReturn(false);

        $prop = new ReflectionProperty(Patients::class, 'patientModel');
        $prop->setAccessible(true);
        $prop->setValue($controller, $model);

        $response = $controller->delete(7);
        $this->assertSame(302, $response->getStatusCode());
        $this->assertNotEmpty($response->getHeaderLine('Location'));
    }
}



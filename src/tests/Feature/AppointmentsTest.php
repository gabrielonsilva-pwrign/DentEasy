<?php

use App\Controllers\Appointments;
use App\Controllers\Api;
use App\Models\AppointmentModel;
use App\Models\PatientModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Config\Services;

final class AppointmentsTest extends CIUnitTestCase
{
    public function testCreateTriggersWebhook(): void
    {
        session()->set('user_id', 1);
        $controller = new class extends Appointments {
            protected function checkPermission($m,$a) { /* no-op in tests */ }
        };

        $appointmentModel = $this->createMock(AppointmentModel::class);
        $appointmentModel->method('insert')->willReturn(10);
        $appointmentModel->method('getAppointmentWithPatient')->willReturn([
            'title' => 'Consulta',
            'patient_name' => 'Fulano',
            'start_time' => '2025-09-24 10:00:00',
            'mobile_phone' => '11999999999'
        ]);

        $patientModel = $this->createMock(PatientModel::class);
        $apiController = $this->createMock(Api::class);
        $apiController->method('sendWebhook')->willReturn(true);

        (new ReflectionProperty(Appointments::class, 'appointmentModel'))->setAccessible(true);
        (new ReflectionProperty(Appointments::class, 'patientModel'))->setAccessible(true);
        (new ReflectionProperty(Appointments::class, 'apiController'))->setAccessible(true);
        (new ReflectionProperty(Appointments::class, 'appointmentModel'))->setValue($controller, $appointmentModel);
        (new ReflectionProperty(Appointments::class, 'patientModel'))->setValue($controller, $patientModel);
        (new ReflectionProperty(Appointments::class, 'apiController'))->setValue($controller, $apiController);

        $request = Services::request();
        $request->setMethod('post');
        $request->setGlobal('post', [
            'patient_id' => 1,
            'title' => 'Consulta'
        ]);
        (new ReflectionProperty(Appointments::class, 'request'))->setAccessible(true);
        (new ReflectionProperty(Appointments::class, 'request'))->setValue($controller, $request);

        // init request/response
        $controller->initController(service('request'), service('response'), service('logger'));
        $response = $controller->create();
        $this->assertSame(302, $response->getStatusCode());
        $this->assertStringContainsString('/appointments', $response->getHeaderLine('Location'));
    }
}



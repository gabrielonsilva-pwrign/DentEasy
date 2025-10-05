<?php

namespace Tests\Feature;

use App\Controllers\Patients;
use App\Models\PatientModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Config\Services;
use ReflectionProperty;

final class PatientsViewFormattingTest extends CIUnitTestCase
{
    /**
     * @doesNotPerformAssertions
     */
    protected function setUp(): void
    {
        parent::setUp();
        session()->remove(['user_id']);
    }

    protected function tearDown(): void
    {
        session()->destroy();
        parent::tearDown();
    }

    public function testViewFormatsCpfAndMobile(): void
    {
        session()->set('user_id', 1);

        $controller = new class extends Patients {
            protected function checkPermission($m, $a) { return true; }
        };

        // Mock completo com todos os campos necessários
        $patientModel = $this->createMock(PatientModel::class);
        $patientModel->method('find')->willReturn($this->getCompletePatientData());
        $patientModel->method('getAge')->willReturn(30);

        // Injeta o mock
        $prop = new ReflectionProperty(Patients::class, 'patientModel');
        $prop->setAccessible(true);
        $prop->setValue($controller, $patientModel);

        // Mock da view que verifica os dados formatados
        $mockView = $this->createMock('CodeIgniter\View\View');
        $mockView->method('render')->willReturnCallback(function($view, $data) {
            // Verifica se os dados estão formatados corretamente
            $this->assertStringContainsString('123.456.789-01', $data['patient']['cpf']);
            $this->assertStringContainsString('(11) 9 8765-4321', $data['patient']['mobile_phone']);
            $this->assertEquals(30, $data['age']);
            return 'mock_view_content';
        });
        Services::injectMock('renderer', $mockView);

        // Configura o controller
        $request = Services::request();
        $requestProp = new ReflectionProperty(Patients::class, 'request');
        $requestProp->setAccessible(true);
        $requestProp->setValue($controller, $request);

        $controller->initController($request, Services::response(), Services::logger());

        $response = $controller->view(10);
        $this->assertIsString((string) $response);
    }

    private function getCompletePatientData(): array
    {
        return [
            'id' => 10,
            'name' => 'Fulano da Silva',
            'email' => 'fulano@example.com',
            'cpf' => '12345678901',
            'mobile_phone' => '11987654321',
            'gender' => 'Masculino',
            'birth_date' => '1990-01-01',
            'address' => 'Rua Teste, 123 - Centro',
            'medical_history' => 'Nenhuma informação',
            'odontogram_data' => null,
            'dental_budget' => null,
            'created_at' => '2023-01-01 10:00:00',
            'updated_at' => '2023-01-01 10:00:00'
        ];
    }
}
<?php

namespace Tests\Feature;

use App\Controllers\Patients;
use App\Models\PatientModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Config\Services;
use ReflectionProperty;

final class PatientsCreateTest extends CIUnitTestCase
{
    /**
     * @doesNotPerformAssertions
     */
    protected function setUp(): void
    {
        parent::setUp();
        session()->remove(['user_id', 'success', 'errors']);
    }

    protected function tearDown(): void
    {
        session()->destroy();
        parent::tearDown();
    }

    public function testCreateWithOdontogramData(): void
    {
        $controller = new class extends Patients {
            protected function checkPermission($m, $a) { return true; }
        };

        // Mock do PatientModel
        $model = $this->createMock(PatientModel::class);
        $model->method('insert')->willReturn(true);
        $model->method('getInsertID')->willReturn(1);
        $model->method('errors')->willReturn([]);

        $prop = new ReflectionProperty(Patients::class, 'patientModel');
        $prop->setAccessible(true);
        $prop->setValue($controller, $model);

        session()->set('user_id', 1);

        $request = Services::request();
        $request->setMethod('post');
        $request->setGlobal('post', [
            'name' => 'Teste',
            'cpf' => '12345678901',
            'mobile_phone' => '11987654321',
            'odontogram_data' => json_encode(['tooth_11' => ['caries' => true]]),
        ]);

        $controller->initController($request, service('response'), service('logger'));
        $response = $controller->create();

        $this->assertSame(302, $response->getStatusCode());
        $this->assertStringContainsString('/patients', $response->getHeaderLine('Location'));
        $this->assertStringContainsString('adicionado', session('success') ?? '');
    }

    public function testCreateWithInvalidData(): void
    {
        $controller = new class extends Patients {
            protected function checkPermission($m, $a) { return true; }
        };

        $model = $this->createMock(PatientModel::class);
        $model->method('insert')->willReturn(false);
        $model->method('errors')->willReturn([
            'name' => 'O nome é obrigatório',
            'cpf' => 'CPF inválido'
        ]);

        $prop = new ReflectionProperty(Patients::class, 'patientModel');
        $prop->setAccessible(true);
        $prop->setValue($controller, $model);

        session()->set('user_id', 1);

        $request = Services::request();
        $request->setMethod('post');
        $request->setGlobal('post', [
            'name' => '', // Nome vazio para simular erro
            'cpf' => '123',
        ]);

        // Configura o header Referer para simular que veio da página de criação
        $request->setHeader('Referer', '/patients/new');

        $controller->initController($request, service('response'), service('logger'));
        $response = $controller->create();

        // CORREÇÃO: Em caso de erro, pode redirecionar de volta (back) que pode ser '/'
        // Verificamos apenas o status de redirecionamento e a existência de erros
        $this->assertSame(302, $response->getStatusCode());
        
        // Verifica se há erros na sessão (o importante é que a validação falhou)
        $errors = session('errors');
        $this->assertIsArray($errors);
        $this->assertArrayHasKey('name', $errors);
    }

    public function testCreateWithValidationErrors(): void
    {
        $controller = new class extends Patients {
            protected function checkPermission($m, $a) { return true; }
        };

        $model = $this->createMock(PatientModel::class);
        $model->method('insert')->willReturn(false);
        $model->method('errors')->willReturn([
            'email' => 'Email já existe',
            'cpf' => 'CPF já cadastrado'
        ]);

        $prop = new ReflectionProperty(Patients::class, 'patientModel');
        $prop->setAccessible(true);
        $prop->setValue($controller, $model);

        session()->set('user_id', 1);

        $request = Services::request();
        $request->setMethod('post');
        $request->setGlobal('post', [
            'name' => 'Teste',
            'cpf' => '12345678901',
            'email' => 'existente@example.com',
        ]);

        $controller->initController($request, service('response'), service('logger'));
        $response = $controller->create();

        $this->assertSame(302, $response->getStatusCode());
        
        $errors = session('errors');
        $this->assertIsArray($errors);
        $this->assertArrayHasKey('cpf', $errors);
        $this->assertArrayHasKey('email', $errors);
    }

    public function testCreateWithoutPermission(): void
    {
        $controller = new class extends Patients {
            protected function checkPermission($m, $a) { 
                return false;
            }
        };

        session()->set('user_id', 1);

        $request = Services::request();
        $request->setMethod('get');

        $controller->initController($request, service('response'), service('logger'));
        $response = $controller->create();

        $this->assertSame(302, $response->getStatusCode());
    }
}
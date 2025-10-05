<?php

namespace Tests\Feature;

use App\Controllers\Patients;
use App\Models\PatientModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Config\Services;
use ReflectionProperty;

final class PatientsIndexSearchTest extends CIUnitTestCase
{
    /**
     * @doesNotPerformAssertions
     */
    protected function setUp(): void
    {
        parent::setUp();
        session()->remove(['user_id', 'error']);
        
        // Mock da view para todos os testes
        $mockView = $this->getMockBuilder('CodeIgniter\View\View')
                        ->disableOriginalConstructor()
                        ->onlyMethods(['render'])
                        ->getMock();
        $mockView->method('render')->willReturn('mock content');
        Services::injectMock('renderer', $mockView);
    }

    protected function tearDown(): void
    {
        session()->destroy();
        parent::tearDown();
    }

    public function testIndexPassesQueryParamsToSearch(): void
    {
        $controller = new class extends Patients {
            protected function checkPermission($module, $action) { return true; }
        };

        session()->set('user_id', 1);

        // Mock completo do PatientModel
        $model = $this->createMock(PatientModel::class);
        $model->method('search')
              ->with('ana', 20, 3, 'name', 'desc')
              ->willReturn(['patients' => []]);
        
        // Mock do pager
        $pager = $this->createMock('CodeIgniter\Pager\Pager');
        $model->pager = $pager;

        $modelProp = new ReflectionProperty(Patients::class, 'patientModel');
        $modelProp->setAccessible(true);
        $modelProp->setValue($controller, $model);

        $request = Services::request();
        $request->setMethod('get');
        $request->setGlobal('get', [
            'search' => 'ana',
            'page' => 3,
            'order_by' => 'name',
            'order_dir' => 'desc',
        ]);

        $requestProp = new ReflectionProperty(Patients::class, 'request');
        $requestProp->setAccessible(true);
        $requestProp->setValue($controller, $request);

        $controller->initController($request, Services::response(), Services::logger());

        $response = $controller->index();
        $this->assertIsString((string) $response);
    }

    public function testIndexWithDefaultParams(): void
    {
        $controller = new class extends Patients {
            protected function checkPermission($module, $action) { return true; }
        };

        session()->set('user_id', 1);

        $model = $this->createMock(PatientModel::class);
        $model->method('search')
              ->with('', 20, 1, 'id', 'asc')
              ->willReturn(['patients' => []]);
        $model->pager = $this->createMock('CodeIgniter\Pager\Pager');

        $modelProp = new ReflectionProperty(Patients::class, 'patientModel');
        $modelProp->setAccessible(true);
        $modelProp->setValue($controller, $model);

        $request = Services::request();
        $request->setMethod('get');
        $request->setGlobal('get', []);

        $requestProp = new ReflectionProperty(Patients::class, 'request');
        $requestProp->setAccessible(true);
        $requestProp->setValue($controller, $request);

        $controller->initController($request, Services::response(), Services::logger());

        $response = $controller->index();
        $this->assertIsString((string) $response);
    }
}
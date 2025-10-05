<?php

use App\Filters\PatientAuthFilter;
use CodeIgniter\Config\Services;
use CodeIgniter\HTTP\Request;
use CodeIgniter\Test\CIUnitTestCase;

final class PatientAuthFilterTest extends CIUnitTestCase
{
    public function testRedirectsToPortalWhenNoPatientInSession(): void
    {
        $filter = new PatientAuthFilter();
        $request = Services::request();
        $request->setMethod('get');

        session()->destroy();

        $response = $filter->before($request);

        $this->assertNotNull($response);
        $this->assertStringContainsString('/portal', $response->getHeaderLine('Location'));
    }

    public function testAllowsWhenPatientInSession(): void
    {
        $filter = new PatientAuthFilter();
        $request = Services::request();
        $request->setMethod('get');

        session()->set('patient_id', 1);

        $response = $filter->before($request);

        $this->assertNull($response);
    }
}



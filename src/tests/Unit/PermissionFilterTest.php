<?php

use App\Filters\PermissionFilter;
use CodeIgniter\Config\Services;
use CodeIgniter\HTTP\Request;
use CodeIgniter\Test\CIUnitTestCase;

final class PermissionFilterTest extends CIUnitTestCase
{
    public function testWithoutSessionRedirectsToLogin(): void
    {
        $filter = new PermissionFilter();

        $request = Services::request();
        $request->setMethod('get');

        session()->destroy();

        $response = $filter->before($request);

        $this->assertNotNull($response);
        $this->assertStringContainsString('/login', $response->getHeaderLine('Location'));
    }
}



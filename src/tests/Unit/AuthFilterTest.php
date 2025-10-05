<?php

use App\Filters\AuthFilter;
use CodeIgniter\Config\Services;
use CodeIgniter\HTTP\Request; 
use CodeIgniter\Test\CIUnitTestCase;

final class AuthFilterTest extends CIUnitTestCase
{
    public function testRedirectsToLoginWhenNoUserInSession(): void
    {
        $filter = new AuthFilter();
        $request = Services::request();
        $request->setMethod('get');

        session()->destroy();

        $response = $filter->before($request);

        $this->assertNotNull($response);
        $this->assertStringContainsString('/login', $response->getHeaderLine('Location'));
    }

    public function testAllowsWhenUserInSession(): void
    {
        $filter = new AuthFilter();
        $request = Services::request();
        $request->setMethod('get');

        session()->set('user_id', 1);

        $response = $filter->before($request);

        $this->assertNull($response);
    }
}



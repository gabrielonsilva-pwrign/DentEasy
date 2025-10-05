<?php

use App\Controllers\Api;
use App\Models\WebhookModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\CURLRequest;

final class ApiTest extends CIUnitTestCase
{
    public function testSendWebhookUsesActiveWebhookAndReturnsTrue(): void
    {
        $controller = new Api();

        // Mock do WebhookModel
        $model = $this->createMock(WebhookModel::class);
        $model->method('getActiveWebhook')
              ->willReturn(['url' => 'https://example.com/webhook']);

        // Injeta o model mockado no controller usando reflexion
        $reflection = new ReflectionProperty(Api::class, 'webhookModel');
        $reflection->setAccessible(true);
        $reflection->setValue($controller, $model);

        // Cria um Response real em vez de mockar a interface
        $mockResponse = new Response(new \Config\App());
        $mockResponse->setStatusCode(200); // Simula sucesso HTTP

        // Mock do CURLRequest - maneira correta
        $client = $this->getMockBuilder(CURLRequest::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['post'])
            ->getMock();
        
        // O método post deve retornar um objeto Response, não um mock de interface
        $client->method('post')
               ->willReturn($mockResponse);

        // Injeta o mock do cliente HTTP
        \Config\Services::injectMock('curlrequest', $client);

        // Executa o teste
        $ok = $controller->sendWebhook(['foo' => 'bar']);
        $this->assertTrue($ok);
    }
}
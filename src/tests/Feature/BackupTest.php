<?php

use App\Controllers\Backup;
use App\Models\BackupModel;
use CodeIgniter\Test\CIUnitTestCase;

final class BackupTest extends CIUnitTestCase
{
    public function testDownloadUnavailableReturnsErrorJson(): void
    {
        $controller = new Backup();
        // Inicializa response do controller para evitar null
        $ref = new ReflectionProperty(Backup::class, 'response');
        $ref->setAccessible(true);
        $ref->setValue($controller, service('response'));

        $model = $this->createMock(BackupModel::class);
        $model->method('find')->willReturn(['id' => 1, 'status' => 'Pendente']);
        (new ReflectionProperty(Backup::class, 'backupModel'))->setAccessible(true);
        (new ReflectionProperty(Backup::class, 'backupModel'))->setValue($controller, $model);

        $response = $controller->downloadBackup(1);
        $this->assertStringContainsString('Backup indisponível', (string) $response->getBody());
    }
}



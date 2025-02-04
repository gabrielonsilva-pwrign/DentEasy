<?php

namespace App\Controllers;

use App\Models\BackupModel;
use CodeIgniter\I18n\Time;

class Backup extends BaseController
{
    protected $backupModel;

    public function __construct()
    {
        $this->backupModel = new BackupModel();
    }

    public function index()
    {
        $data['backups'] = $this->backupModel->getBackups();
        return view('backup/index', $data);
    }

    public function instantBackup()
    {
        $filename = 'backup_' . Time::now()->toLocalizedString('Y_d_M-H-m-s') . '.zip';
        $data = [
            'filename' => $filename,
            'status' => 'Pendente',
            'type' => 'Manual'
        ];

        $this->backupModel->insert($data);

        // Trigger asynchronous job
        $this->runAsyncBackup($filename);

        return $this->response->setJSON(['message' => 'Backup iniciado']);
    }

    private function runAsyncBackup($filename)
    {
        $phpPath = PHP_BINARY; // Caminho para o executável PHP
        $scriptPath = ROOTPATH . 'spark'; // Caminho para o script spark do CodeIgniter

        // Comando para executar o backup
        $command = "{$phpPath} {$scriptPath} backup:run {$filename} > /dev/null 2>&1 &";

        // Executa o comando
        exec($command);
    }

    public function downloadBackup($id)
    {
        $backup = $this->backupModel->find($id);
        if ($backup && $backup['status'] === 'Completo') {
            return $this->response->download(WRITEPATH . 'backups/' . $backup['filename'], null);
        }
        return $this->response->setJSON(['error' => 'Backup indisponível']);
    }

    public function importBackup()
    {
        // Handle file upload
        $file = $this->request->getFile('backup_file');
        if ($file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(WRITEPATH . 'backups', $newName);

            // Trigger asynchronous job for import
            $this->runAsyncImport($newName);

            $session = session();
            $session->destroy();
            return redirect()->to('login');
        }
        return $this->response->setJSON(['error' => 'Arquivo inválido']);
    }

    private function runAsyncImport($filename)
    {
        $phpPath = PHP_BINARY; // Caminho para o executável PHP
        $scriptPath = ROOTPATH . 'spark'; // Caminho para o script spark do CodeIgniter

        // Comando para executar o backup
        $command = "{$phpPath} {$scriptPath} backup:import {$filename} > /dev/null 2>&1 &";

        // Executa o comando
        exec($command);
    }

}

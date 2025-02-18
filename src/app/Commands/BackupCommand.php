<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\BackupModel;

class BackupCommand extends BaseCommand
{
    protected $group       = 'default';
    protected $name        = 'backup:run';
    protected $description = 'Runs a backup process';

    public function run(array $params)
    {
        $filename = $params[0] ?? null;

        if (!$filename) {
            CLI::error('Filename is required');
            return;
        }

        CLI::write('Starting backup process...', 'green');

        $backupModel = new BackupModel();
        
        $backupModel->updateBackup(['filename' => $filename], ['status' => 'Processando']);

        

        try {
            $backupPath = WRITEPATH . 'backups/';
            $tempPath = $backupPath . 'temp_backup/';

            if (!is_dir($tempPath)) {
                mkdir($tempPath, 0755, true);
            }

            // Backup database
            $this->backupDatabase($tempPath . 'database');

            // Backup uploads folder
            $this->backupUploads($tempPath . 'uploads/');

            // Create Final ZIP
            $this->createFinalZip($backupPath . $filename, $tempPath);

            // Clean up temp directory
            $this->removeDirectory($tempPath);


            $backupModel->updateBackup(['filename' => $filename], ['status' => 'Completo']);
            CLI::write('Backup completed successfully', 'green');
        } catch (\Exception $e) {
            $backupModel->updateBackup(['filename' => $filename], ['status' => 'Falhou']);
            CLI::error('Backup failed: ' . $e->getMessage());
        }
    }

    private function backupDatabase($filename)
    {
        CLI::write('Backing up database...', 'yellow');

        $db = \Config\Database::connect();
        $dbName = getenv('database.default.database');
        $username = getenv('database.default.username');
        $password = getenv('database.default.password');
        $host = getenv('database.default.hostname');
        $port = getenv('database.default.port');

        CLI::write($filename);

        $backupFile = $filename . '.sql';

        $command = "mysqldump -h {$host} -P {$port} -u {$username} -p{$password} {$dbName} > {$backupFile}";

        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            throw new \Exception('Database backup failed');
        }

        CLI::write('Database backup completed', 'green');
    }

    private function backupUploads($destination)
    {
        CLI::write('Backing up uploads folder...', 'yellow');

        $source = ROOTPATH . 'public/uploads';

        $this->recursiveCopy($source, $destination);

        CLI::write('Uploads backup completed', 'green');
    }

    private function createFinalZip($zipFile, $source)
    {
        CLI::write('Creating final ZIP file...', 'yellow');

        $zip = new \ZipArchive();
        if ($zip->open($zipFile, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== TRUE) {
            throw new \Exception('Cannot create zip file');
        }

        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($source),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $name => $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($source));
                $zip->addFile($filePath, $relativePath);
            }
        }

        $zip->close();

        CLI::write('Final ZIP file created', 'green');
    }

    private function recursiveCopy($src, $dst)
    {
        $dir = opendir($src);
        @mkdir($dst);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($src . '/' . $file)) {
                    $this->recursiveCopy($src . '/' . $file, $dst . '/' . $file);
                } else {
                    copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }

    private function removeDirectory($dir)
    {
        if (!is_dir($dir)) {
            return;
        }

        $files = array_diff(scandir($dir), array('.', '..'));
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? $this->removeDirectory("$dir/$file") : unlink("$dir/$file");
        }
        return rmdir($dir);
    }
}
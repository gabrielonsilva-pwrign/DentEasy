<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\BackupModel;

class BackupImportCommand extends BaseCommand
{
    protected $group       = 'default';
    protected $name        = 'backup:import';
    protected $description = 'Imports a backup file';

    protected $backupModel;

    public function run(array $params)
    {
        $filename = $params[0] ?? null;

        if (!$filename) {
            CLI::error('Filename is required');
            return;
        }

        CLI::write('Starting import process...', 'green');


        try {
            $backupPath = WRITEPATH . 'backups/';
            $tempPath = $backupPath . 'temp_backup/';
            
            if (!is_dir($tempPath)) {
                mkdir($tempPath, 0755, true);
            }

            // Extract the ZIP file
            $this->extractZip($backupPath . $filename, $tempPath);

            // Import database
            $this->importLargeDatabase($tempPath . 'database.sql');

            // Restore uploads folder
            $this->restoreUploads($tempPath . 'uploads/');

            // Clean up temp directory
            $this->removeDirectory($tempPath);

            CLI::write('Import completed successfully', 'green');
        } catch (\Exception $e) {
            CLI::error('Import failed: ' . $e->getMessage());
        }
    }

    private function extractZip($zipFile, $destination)
    {
        CLI::write('Extracting ZIP file...', 'yellow');

        $zip = new \ZipArchive;
        if ($zip->open($zipFile) === TRUE) {
            $zip->extractTo($destination);
            $zip->close();
            CLI::write('ZIP file extracted', 'green');
        } else {
            throw new \Exception('Failed to extract ZIP file');
        }
    }

    private function importLargeDatabase($sqlFile)
    {
        CLI::write('Importing database...', 'yellow');
        
        $db = \Config\Database::connect();
        $dbName = getenv('database.default.database');
        $username = getenv('database.default.username');
        $password = getenv('database.default.password');
        $host = getenv('database.default.hostname');
        $port = getenv('database.default.port');

        // Backup existing user and group with ID 1
        $existingUser = $db->query("SELECT * FROM users WHERE id = 1")->getRowArray();
        $existingGroup = $db->query("SELECT * FROM groups WHERE id = 1")->getRowArray();

        // Drop all tables except users and groups
        $tables = $db->listTables();
        $db->query("SET FOREIGN_KEY_CHECKS=0;");
        foreach ($tables as $table) {
            if ($table !== 'users' && $table !== 'groups') {
                $db->query("DROP TABLE IF EXISTS `$table`");
            }
        }

        // Read and execute SQL file in chunks
        $handle = fopen($sqlFile, 'r');
        if ($handle) {
            $query = '';
            while (($line = fgets($handle)) !== false) {
                $query .= $line;
                if (substr(trim($line), -1) == ';') {
                    $db->query($query);
                    $query = '';
                }
            }
            fclose($handle);
        } else {
            throw new \Exception('Unable to open SQL file');
        }

        // Restore user and group with ID 1
        if ($existingUser) {
            $db->table('users')->where('id', 1)->update($existingUser);
        }
        if ($existingGroup) {
            $db->table('groups')->where('id', 1)->update($existingGroup);
        }
		$db->query("SET FOREIGN_KEY_CHECKS=1;");
        CLI::write('Database imported', 'green');
    }

    private function restoreUploads($source)
    {
        CLI::write('Restoring uploads folder...', 'yellow');

        $destination = ROOTPATH . 'public/uploads';

        // Remove existing uploads
        $this->removeDirectory($destination);

        // Copy new uploads
        $this->recursiveCopy($source, $destination);

        CLI::write('Uploads restored', 'green');
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

<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Commands extends BaseConfig
{

    public $commands = [
        'backup:run' => \App\Commands\BackupCommand::class,
        'backup:import' => \App\Commands\BackupImportCommand::class,
    ];
}

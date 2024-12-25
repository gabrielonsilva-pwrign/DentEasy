<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\SecureHeaders;
use App\Filters\AuthFilter;
use App\Filters\PermissionFilter;

class Filters extends BaseConfig
{
    public $aliases = [
        'csrf'          => CSRF::class,
        'toolbar'       => DebugToolbar::class,
        'honeypot'      => Honeypot::class,
        'invalidchars'  => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
        'auth'          => AuthFilter::class,
        'permission'    => PermissionFilter::class,
    ];

    public $globals = [
        'before' => [
            // 'honeypot',
            // 'csrf',
            // 'invalidchars',
        ],
        'after' => [
            'toolbar',
            // 'honeypot',
            // 'secureheaders',
        ],
    ];

    public $methods = [];

    public $filters = [
        'auth' => ['before' => ['/', 'dashboard/*', 'patients/*', 'appointments/*', 'treatments/*', 'inventory/*', 'api/*', 'admin/*']],
        'permission' => ['before' => [
            'dashboard/*', 
            'patients/*', 
            'appointments/*', 
            'treatments/*', 
            'inventory/*', 
            'api/*', 
            'admin/users/*', 
            'admin/groups/*'
        ]],
    ];
}

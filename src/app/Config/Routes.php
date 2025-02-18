<?php

namespace Config;

$routes = Services::routes();

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Auth');
$routes->setDefaultMethod('login');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

// Authentication routes
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attemptLogin');
$routes->get('logout', 'Auth::logout');

$routes->group('', ['filter' => 'auth'], function($routes) {
    // Dashboard
    $routes->group('dashboard', ['filter' => 'permission:dashboard'], function($routes) {
        $routes->get('/', 'Dashboard::index');
        $routes->get('dashboard/clearCache', 'Dashboard::clearCache');
    });

    // Patients
    $routes->group('patients', ['filter' => 'permission:patients'], function($routes) {
        $routes->get('/', 'Patients::index');
        $routes->get('patients/new', 'Patients::new');
        $routes->post('patients/create', 'Patients::create');
        $routes->get('patients/edit/(:num)', 'Patients::edit/$1');
        $routes->post('patients/update/(:num)', 'Patients::update/$1');
        $routes->get('patients/delete/(:num)', 'Patients::delete/$1');
        $routes->get('patients/view/(:num)', 'Patients::view/$1');
        $routes->get('patients/treatmentHistory/(:num)', 'Patients::treatmentHistory/$1');
    });

    // Appointments
    $routes->group('appointments', ['filter' => 'permission:appointments'], function($routes) {
        $routes->get('/', 'Appointments::index');
        $routes->get('appointments/getAppointments', 'Appointments::getAppointments');
        $routes->get('appointments/new', 'Appointments::new');
        $routes->post('appointments/create', 'Appointments::create');
        $routes->get('appointments/edit/(:num)', 'Appointments::edit/$1');
        $routes->post('appointments/update/(:num)', 'Appointments::update/$1');
        $routes->get('appointments/delete/(:num)', 'Appointments::delete/$1');
    });

    // Treatments
    $routes->group('treatments', ['filter' => 'permission:treatments'], function($routes) {
        $routes->get('/', 'Treatments::index');
        $routes->get('treatments/new', 'Treatments::new');
        $routes->post('treatments/create', 'Treatments::create');
        $routes->get('treatments/edit/(:num)', 'Treatments::edit/$1');
        $routes->post('treatments/update/(:num)', 'Treatments::update/$1');
        $routes->get('treatments/delete/(:num)', 'Treatments::delete/$1');
        $routes->get('treatments/view/(:num)', 'Treatments::view/$1');
        $routes->get('treatments/deleteFile/(:num)', 'Treatments::deleteFile/$1');
    });

    // Inventory
    $routes->group('inventory', ['filter' => 'permission:inventory'], function($routes) {
        $routes->get('/', 'Inventory::index');
        $routes->get('inventory/new', 'Inventory::new');
        $routes->post('inventory/create', 'Inventory::create');
        $routes->get('inventory/edit/(:num)', 'Inventory::edit/$1');
        $routes->post('inventory/update/(:num)', 'Inventory::update/$1');
        $routes->get('inventory/delete/(:num)', 'Inventory::delete/$1');
        $routes->get('inventory/history/(:num)', 'Inventory::history/$1');
    });

    // Users
    $routes->group('users', ['filter' => 'permission:users'], function($routes) {
        $routes->get('/', 'Users::index');
        $routes->get('users/new', 'Users::new');
        $routes->post('users/create', 'Users::create');
        $routes->get('users/edit/(:num)', 'Users::edit/$1');
        $routes->post('users/update/(:num)', 'Users::update/$1');
        $routes->get('users/delete/(:num)', 'Users::delete/$1');
        $routes->get('users/view/(:num)', 'Users::view/$1');
    });

    // Groups
    $routes->group('groups', ['filter' => 'permission:groups'], function($routes) {
        $routes->get('/', 'Groups::index');
        $routes->get('groups/new', 'Groups::new');
        $routes->post('groups/create', 'Groups::create');
        $routes->get('groups/edit/(:num)', 'Groups::edit/$1');
        $routes->post('groups/update/(:num)', 'Groups::update/$1');
        $routes->get('groups/delete/(:num)', 'Groups::delete/$1');
        $routes->get('groups/view/(:num)', 'Groups::view/$1');
    });

    // API (Webhooks)
    $routes->group('api', ['filter' => 'permission:api'], function($routes) {
        $routes->get('/', 'Api::index');
        $routes->get('api/new', 'Api::new');
        $routes->post('api/create', 'Api::create');
        $routes->get('api/edit/(:num)', 'Api::edit/$1');
        $routes->post('api/update/(:num)', 'Api::update/$1');
        $routes->get('api/delete/(:num)', 'Api::delete/$1');
    });

    // Backup
    $routes->group('backup', ['filter' => 'permission:backup'], function($routes) {
        $routes->get('/', 'Backup::index');
        $routes->post('scheduleBackup', 'Backup::scheduleBackup');
        $routes->post('instantBackup', 'Backup::instantBackup');
        $routes->get('download/(:num)', 'Backup::downloadBackup/$1');
        $routes->post('import', 'Backup::importBackup');
    });
});


$routes->get('portal', 'PatientAuth::login');
$routes->post('portal', 'PatientAuth::attemptLogin');
$routes->get('patient-logout', 'PatientAuth::logout');

$routes->group('my', ['filter' => 'patientAuth'], function($routes) {
    $routes->get('', 'PatientPortal::index');
    $routes->get('personal', 'PatientPortal::personalData');
    $routes->get('history', 'PatientPortal::treatmentHistory');
    $routes->get('appointments', 'PatientPortal::appointments');
});
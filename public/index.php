<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

// On shared hosting, this file may be served from a document root that
// differs from Laravel's default `public/` folder — tell the app where
// its real public path is so asset(), Vite, and Storage::url() resolve
// correctly. Harmless no-op in a standard local setup.
$app->usePublicPath(__DIR__);

$app->handleRequest(Request::capture());

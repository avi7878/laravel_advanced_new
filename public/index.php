<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

function getTimezone(){
    $tz=isset($_COOKIE[env('APP_UID').'_tz'])?$_COOKIE[env('APP_UID').'_tz']:'UTC';
    if($tz=='Asia/Calcutta'){
        $tz="Asia/Kolkata";
    }
    $tz= in_array($tz, timezone_identifiers_list()) ?$tz:'UTC';
    return $tz;
}

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());

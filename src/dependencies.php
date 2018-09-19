<?php
// DIC configuration

$container = $app->getContainer();

// Logger Provider
$loggerProvider = new App\Providers\LoggerProvider($container);

// Database Provider
$dbProvider = new App\Providers\DBProvider($container);
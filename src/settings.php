<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Monolog settings
        'logger' => [
            'name' => 'api-app',
            'pathApp' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
            'pathError' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/error.log',
            'level' => \Monolog\Logger::DEBUG,
        ],

        // Database settings
        'db' => [
            'driver' => 'mysql',
            'host' => 'creditos-mysql',
            'database' => 'creditos',
            'username' => 'creditos',
            'password' => 'creditos',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ],

        // Token settings
        "jwt" => [
            'secret' => '3Rz7znk032mmtL7oLcP5YvbzG7jwqk0Wjr4U9W20'
        ],
    ],
];

<?php

namespace App\Providers;

use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Monolog\Handler\StreamHandler;

class LoggerProvider implements ProviderInterface
{
	public function __construct($container)
	{
		$this->register($container);
	}

	public function register($container)
	{
		$container['logger'] = function ($c) {
    		$settings = $c->get('settings')['logger'];
    		$logger = new Logger($settings['name']);
    		$logger->pushProcessor(new UidProcessor());
    		$logger->pushHandler(new StreamHandler($settings['path'], $settings['level']));
    		return $logger;
		};
	}
}
<?php

namespace App\Providers;

use Monolog\Formatter\JsonFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;

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

    		$streamHandler = new StreamHandler($settings['pathApp'], $settings['level']);
    		$streamHandler->setFormatter(new JsonFormatter());

    		$logger->pushHandler($streamHandler);

    		return $logger;
		};

		$container['errorLogger'] = function ($c) {
    		$settings = $c->get('settings')['logger'];
    		$name = $settings['name'] . '-error';
    		$errorLogger = new Logger($name);
    		$errorLogger->pushProcessor(new UidProcessor());

    		$streamHandler = new StreamHandler($settings['pathError'], $settings['level']);
    		$streamHandler->setFormatter(new JsonFormatter());

    		$errorLogger->pushHandler($streamHandler);

    		return $errorLogger;
		};
	}
}
<?php

declare(strict_types=1);

namespace Eshop;

use Nette\Bootstrap\Configurator;
use Tester\Environment;


class Bootstrap
{
	public static function boot(): Configurator
	{
		$configurator = new Configurator;
		$appDir = dirname(__DIR__);

		//$configurator->setDebugMode('secret@23.75.345.200'); // enable for your remote IP
		if (getenv('NETTE_DEBUG') == 1) {
			$configurator->setDebugMode(TRUE);
		}
		$configurator->enableTracy($appDir . '/log');

		$configurator->setTimeZone('Europe/Prague');
		$configurator->setTempDirectory($appDir . '/temp');

		$configurator->createRobotLoader()
			->addDirectory(__DIR__)
			->register();

		$configurator->addConfig($appDir . '/config/common.neon');
		$configurator->addConfig($appDir . '/config/services.neon');
		$configurator->addConfig($appDir . '/config/local.neon');

		return $configurator;
	}

	public static function bootForTests(): Configurator
	{
		$configurator = self::boot();
		$configurator->setDebugMode(false);
		Environment::setup();
		return $configurator;
	}

	public static function bootForCli(): Configurator
	{
		$configurator = self::boot();
		$configurator->setDebugMode(false);
		return $configurator;
	}
}

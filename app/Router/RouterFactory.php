<?php

declare(strict_types=1);

namespace Eshop\Router;

use Nette;
use Nette\Application\Routers\RouteList;


final class RouterFactory
{
	use Nette\StaticClass;

	public static function createRouter(): RouteList
	{
		$router = new RouteList;
		$router->addRoute('admin/<action>[/<id>]', 'Admin:dashboard');
		$router->addRoute('eshop', 'Homepage:list');
		$router->addRoute('kosik', 'Homepage:basket');
		$router->addRoute('objednavka', 'Homepage:order');
		$router->addRoute('obsah/<id>', 'Homepage:content');
		$router->addRoute('<url>', 'Homepage:detail');
		$router->addRoute('', 'Homepage:default');
		return $router;
	}
}

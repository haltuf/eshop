<?php declare(strict_types=1);

namespace Eshop\Tests;

use Eshop\Bootstrap;
use Nette\DI\Container;
use Tester\TestCase;

abstract class AbstractTestCase extends TestCase
{

	public function createContainer(): Container
	{
		return Bootstrap::bootForTests()->createContainer();
	}
}
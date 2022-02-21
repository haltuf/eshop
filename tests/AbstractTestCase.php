<?php declare(strict_types=1);

namespace Eshop\Tests;

use Nette\DI\Container;
use Tester\TestCase;

abstract class AbstractTestCase extends TestCase
{

	public function __construct(
		protected Container $container,
	) {}
}
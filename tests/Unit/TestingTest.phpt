<?php declare(strict_types=1);

namespace Eshop\Tests\Unit;

$container = require __DIR__ . '/../bootstrap.php';

use Eshop\Tests\AbstractTestCase;
use Tester\Assert;
use Tester\TestCase;

class TestingTest extends AbstractTestCase
{
	public function testTestingWorks()
	{
		Assert::true(true);
	}
}

(new TestingTest($container))->run();
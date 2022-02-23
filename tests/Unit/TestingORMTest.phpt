<?php declare(strict_types=1);

namespace Eshop\Tests\Unit;

$container = require __DIR__ . '/../bootstrap.php';

use Doctrine\ORM\EntityManagerInterface;
use Eshop\Tests\AbstractTestCase;
use Eshop\Tests\ORMTestCase;
use Tester\Assert;
use Tester\TestCase;

class TestingORMTest extends ORMTestCase
{
	public function testGetEntityManager()
	{
		Assert::type(EntityManagerInterface::class, $this->createEntityManager());
	}
}

(new TestingORMTest())->run();
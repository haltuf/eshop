<?php declare(strict_types=1);

namespace Eshop\Tests;

use Doctrine\ORM\EntityManagerInterface;
use Tester\Environment;
use Tester\TestCase;

abstract class ORMTestCase extends AbstractTestCase
{

	protected EntityManagerInterface $em;

	public function setUp()
	{
		Environment::lock('database-orm', __DIR__ . '/tmp');
		$this->em = $this->getEntityManager();
		$this->em->beginTransaction();
	}

	public function tearDown()
	{
		$this->em->rollback();
	}

	public function getEntityManager(): EntityManagerInterface
	{
		return $this->container->getByType(EntityManagerInterface::class);
	}
}
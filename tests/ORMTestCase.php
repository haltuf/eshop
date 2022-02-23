<?php declare(strict_types=1);

namespace Eshop\Tests;

use Doctrine\ORM\EntityManagerInterface;
use Tester\Environment;

abstract class ORMTestCase extends AbstractTestCase
{

	protected EntityManagerInterface $em;

	public function setUp()
	{
		Environment::lock('database-orm', __DIR__ . '/tmp');
		$this->em = $this->createEntityManager();
		$this->em->beginTransaction();
	}

	public function tearDown()
	{
		$this->em->rollback();
	}

	public function createEntityManager(): EntityManagerInterface
	{
		$container = $this->createContainer();
		$em = $container->getByType(EntityManagerInterface::class);
		return $em;
	}
}
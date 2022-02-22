<?php declare(strict_types=1);

namespace Eshop\Tests\Unit\Model;

$container = require __DIR__ . '/../../bootstrap.php';

use Doctrine\ORM\EntityManagerInterface;
use Eshop\Model\ORM\Entity\User;
use Eshop\Model\ORM\Services\ProductService;
use Eshop\Model\ORM\Services\UserService;
use Eshop\Tests\ORMTestCase;
use Nette\Security\AuthenticationException;
use Nette\Security\SimpleIdentity;
use Tester\Assert;


class ProductTest extends ORMTestCase
{
	protected ProductService $productService;

	public function setUp()
	{
		parent::setUp();
		$this->productService = $this->container->getByType(ProductService::class);
	}

	public function testProductServiceAvailable()
	{
		Assert::type(ProductService::class, $this->productService);
	}

}

(new ProductTest($container))->run();
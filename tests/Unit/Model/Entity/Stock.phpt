<?php declare(strict_types=1);

namespace Eshop\Tests\Unit\Model\Entity;

$container = require __DIR__ . '/../../../bootstrap.php';

use Doctrine\ORM\EntityManagerInterface;
use Eshop\Model\ORM\Entity\Category;
use Eshop\Model\ORM\Entity\Product;
use Eshop\Model\ORM\Entity\User;
use Eshop\Model\ORM\Services\UserService;
use Eshop\Tests\ORMTestCase;
use Nette\Security\AuthenticationException;
use Nette\Security\SimpleIdentity;
use Tester\Assert;


class StockTest extends ORMTestCase
{

	private Category $category;

	public function setUp()
	{
		parent::setUp();
		$this->category = new Category('Kategorie');
	}

	public function testAddStockTransaction()
	{
		$product = new Product($this->category);
		$product->getStock()->addTransaction(1);
		Assert::same(1, $product->getStock()->getQuantity());
	}

	public function testRemoveStockTransaction()
	{
		$product = new Product($this->category);
		$product->getStock()->addTransaction(10);
		$product->getStock()->addTransaction(-2);
		Assert::same(8, $product->getStock()->getQuantity());
	}

	public function testStockCannotGoBelowZero()
	{
		$product = new Product($this->category);
		$product->getStock()->addTransaction(10);
		Assert::exception(function () use ($product) {
			$product->getStock()->addTransaction(-20);
		}, \Exception::class, 'Cannot perform stock transaction, stock quantity would go below zero.');
	}

	public function testIsInStock()
	{
		$product = new Product($this->category);
		Assert::false($product->isInStock());

		$product->getStock()->addTransaction(1);
		Assert::true($product->isInStock());
	}
}

(new StockTest($container))->run();
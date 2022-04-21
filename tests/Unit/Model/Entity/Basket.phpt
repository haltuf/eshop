<?php declare(strict_types=1);

namespace Eshop\Tests\Unit\Model\Entity;

$container = require __DIR__ . '/../../../bootstrap.php';

use Doctrine\ORM\EntityManagerInterface;
use Eshop\Model\ORM\Entity\Basket;
use Eshop\Model\ORM\Entity\BasketItem;
use Eshop\Model\ORM\Entity\Category;
use Eshop\Model\ORM\Entity\Product;
use Eshop\Model\ORM\Entity\User;
use Eshop\Model\ORM\Exception\ProductNotAvailable;
use Eshop\Model\ORM\Services\UserService;
use Eshop\Tests\ORMTestCase;
use Nette\Security\AuthenticationException;
use Nette\Security\SimpleIdentity;
use Tester\Assert;


class BasketTest extends ORMTestCase
{

	public function testCreateBasket()
	{
		$basket = new Basket('123');
		Assert::same('123', $basket->getVisitorId());
		Assert::same(32, strlen($basket->getUniqueId()));
		Assert::match('#[0-9a-f]{32}#', $basket->getUniqueId());
	}

	public function testAddProduct()
	{
		$category = new Category('Kategorie');
		$product = new Product($category);
		$product->getStock()->addTransaction(1);
		$basket = new Basket('123');

		$basket->addProduct($product, 1);
		Assert::same($product, $basket->getItems()[0]->getProduct());
		Assert::same(1, $basket->getItems()[0]->getQuantity());

		$basket->addProduct($product, 2);
		Assert::count(1, $basket->getItems());
		Assert::same(3, $basket->getItems()[0]->getQuantity());
	}

	public function testAddItem()
	{
		$category = new Category('Kategorie');
		$product = new Product($category);
		$product->getStock()->addTransaction(1);
		$basket = new Basket('123');

		$item = new BasketItem($basket, $product, 2);
		Assert::same($product, $basket->getItems()[0]->getProduct());
		Assert::same(2, $basket->getItems()[0]->getQuantity());
	}

	public function testRemoveItem()
	{
		$category = new Category('Kategorie');
		$product = new Product($category);
		$product->getStock()->addTransaction(1);
		$basket = new Basket('123');
		$basket->addProduct($product, 1);

		Assert::count(1, $basket->getItems());
		$basket->removeProduct($product);
		Assert::count(0, $basket->getItems());
	}

	public function testCannotAddNonStockedProduct()
	{
		$category = new Category('Kategorie');
		$product = new Product($category);
		$basket = new Basket('123');

		Assert::exception(function () use ($basket, $product) {
			$basket->addProduct($product, 1);
		}, ProductNotAvailable::class);
	}
}

(new BasketTest())->run();
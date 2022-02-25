<?php declare(strict_types=1);

namespace Eshop\Tests\Unit\Model;

require __DIR__ . '/../../bootstrap.php';

use Eshop\Model\ORM\Entity\Category;
use Eshop\Model\ORM\Entity\Product;
use Eshop\Model\ORM\Lists\VatType;
use Eshop\Model\ORM\Services\ProductService;
use Eshop\Tests\ORMTestCase;
use Nette\Utils\ArrayHash;
use Tester\Assert;


class ProductTest extends ORMTestCase
{
	protected ProductService $productService;

	public function setUp()
	{
		parent::setUp();
		$this->productService = new ProductService($this->em);
	}

	public function testProductServiceAvailable()
	{
		Assert::type(ProductService::class, $this->productService);
	}

	public function testGetCategory()
	{
		$category = $this->productService->getCategory(1);
		Assert::type(Category::class, $category);
		Assert::same('Kategorie 1', $category->getName());

		Assert::null($this->productService->getCategory(-1));
	}

	public function testCreateCategory()
	{
		$category = $this->productService->createCategory('Kategorie 2');
		Assert::type(Category::class, $category);

		$category = $this->productService->getCategory($category->getId());
		Assert::type(Category::class, $category);
		Assert::same('Kategorie 2', $category->getName());
		Assert::null($category->getParent());
		Assert::count(0, $category->getChildren());
	}

	public function testUpdateCategoryWithId()
	{
		$category = $this->productService->updateCategory(1, 'Kategorie 1 - changed');
		Assert::type(Category::class, $category);
		Assert::same('Kategorie 1 - changed', $category->getName());

		$category = $this->productService->getCategory(1);
		Assert::same('Kategorie 1 - changed', $category->getName());
	}

	public function testUpdateCategoryWithClass()
	{
		$category = $this->productService->getCategory(1);
		$category = $this->productService->updateCategory($category, 'Kategorie 1 - changed');
		Assert::type(Category::class, $category);
		Assert::same('Kategorie 1 - changed', $category->getName());

		$category = $this->productService->getCategory(1);
		Assert::same('Kategorie 1 - changed', $category->getName());
	}

	public function testGetProduct()
	{
		$product = $this->productService->getProduct(1);
		Assert::type(Product::class, $product);
		Assert::same(1, $product->getId());

		Assert::null($this->productService->getProduct(-1));
	}

	public function testCreateProduct()
	{
		$data = ArrayHash::from([
			'category' => 1,
			'name' => 'Druhý produkt',
			'EAN' => '1234',
			'HSCode' => '002',
			'price' => '3 123,45',
			'vatType' => VatType::REDUCED_RATE_1->value,
		]);

		$product = $this->productService->saveProduct($data);
		Assert::type(Product::class, $product);

		$product = $this->productService->getProduct($product->getId());
		Assert::type(Product::class, $product);
		Assert::same(1, $product->getCategory()->getId());
		Assert::same('Druhý produkt', $product->getName());
		Assert::same('1234', $product->getEAN());
		Assert::same('002', $product->getHSCode());
		Assert::same(3123.45, $product->getPrice());
		Assert::same(VatType::REDUCED_RATE_1, $product->getVatType());
	}

	public function testUpdateProductWithId()
	{
		$data = ArrayHash::from([
			'name' => 'První produkt - updated',
		]);
		$product = $this->productService->saveProduct($data, 1);
		Assert::type(Product::class, $product);

		$product = $this->productService->getProduct($product->getId());
		Assert::same(1, $product->getCategory()->getId());
		Assert::same('07031010', $product->getHSCode());
		Assert::same('401234567890', $product->getEAN());
		Assert::same('001-R', $product->getSKU());
		Assert::same('První produkt - updated', $product->getName());
		Assert::same(125.89, $product->getPrice());
		Assert::same(VatType::STANDARD_RATE, $product->getVatType());
	}

	public function testUpdateProductWithProduct()
	{
		$data = ArrayHash::from([
			'name' => 'První produkt - updated',
		]);
		$product = $this->productService->getProduct(1);
		$product = $this->productService->saveProduct($data, $product);
		Assert::type(Product::class, $product);

		$product = $this->productService->getProduct($product->getId());
		Assert::same('První produkt - updated', $product->getName());
	}

	public function testUpdateProductWithArray()
	{
		$data = [
			'name' => 'První produkt - updated',
		];
		$product = $this->productService->saveProduct($data, 1);
		Assert::type(Product::class, $product);

		$product = $this->productService->getProduct($product->getId());
		Assert::same('První produkt - updated', $product->getName());
	}

	public function testUpdateProductWithObject()
	{
		$data = (object) [
			'name' => 'První produkt - updated',
		];
		$product = $this->productService->saveProduct($data, 1);
		Assert::type(Product::class, $product);

		$product = $this->productService->getProduct($product->getId());
		Assert::same('První produkt - updated', $product->getName());
	}

	public function testListProducts()
	{
		$list = $this->productService->listProducts();
		Assert::type('array', $list);
		Assert::count(1, $list);
	}

	public function testGetCategoryItems()
	{
		$items = $this->productService->getCategoryItems();
		Assert::type('array', $items);
		Assert::same([
			'1' => 'Kategorie 1',
		], $items);
	}
}

(new ProductTest())->run();
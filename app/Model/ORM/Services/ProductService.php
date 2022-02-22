<?php declare(strict_types=1);

namespace Eshop\Model\ORM\Services;

use Doctrine\Common\Collections\Collection;
use Eshop\Model\ORM\Entity\Category;
use Eshop\Model\ORM\Entity\Product;
use Eshop\Model\ORM\Lists\VatType;
use Nette\Utils\ArrayHash;

class ProductService extends AbstractService
{

	public function getCategory(int $id): ?Category
	{
		return $this->em->getRepository(Category::class)->find($id);
	}

	public function createCategory(string $name): Category
	{
		$category = new Category($name);
		$this->em->persist($category);
		$this->em->flush();
		return $category;
	}

	public function updateCategory(int|Category $category, string $name): Category
	{
		if (is_int($category))
			$category = $this->getCategory($category);

		$category->setName($name);
		$this->em->persist($category);
		$this->em->flush();
		return $category;
	}

	public function getProduct(int $id): ?Product
	{
		return $this->em->getRepository(Product::class)->find($id);
	}

	public function createProduct(ArrayHash $values): Product
	{
		$category = $this->getCategory($values->category);
		$product = new Product($category);
		$product->setName($values->name);
		$product->setEAN($values->EAN);
		$product->setHSCode($values->HSCode);
		$product->setPrice((float) str_replace([' ', ','], ['', '.'], $values->price));
		$product->setVatType(VatType::from($values->vatType));

		$this->em->persist($product);
		$this->em->flush();

		return $product;
	}

	public function updateProduct(int|Product $product, ArrayHash $values): Product
	{
		if (is_int($product))
			$product = $this->getProduct($product);

		$category = $this->getCategory($values->category);

		$product->setCategory($category);
		$product->setName($values->name);
		$product->setEAN($values->EAN);
		$product->setHSCode($values->HSCode);
		$product->setPrice((float) str_replace([' ', ','], ['', '.'], $values->price));
		$product->setVatType(VatType::from($values->vatType));

		$this->em->persist($product);
		$this->em->flush();

		return $product;
	}

	/**
	 * @return Product[]
	 */
	public function listProducts(): array|Collection
	{
		return $this->em->getRepository(Product::class)->findAll();
	}

	/**
	 * @return Category[]
	 */
	public function getCategories(): array|Collection
	{
		return $this->em->getRepository(Category::class)->findAll();
	}

	public function getCategoryItems(): array
	{
		$items = [];

		$categories = $this->getCategories();
		foreach ($categories as $category) {
			$items[$category->getId()] = $category->getName();
		}

		return $items;
	}
}
<?php declare(strict_types=1);

namespace Eshop\Model\ORM\Services;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityRepository;
use Eshop\Model\ORM\Entity\Category;
use Eshop\Model\ORM\Entity\Product;
use Eshop\Model\ORM\Entity\ProductImage;
use Eshop\Model\ORM\Lists\VatType;
use Nette\Http\FileUpload;
use Nette\Utils\ArrayHash;
use Nette\Utils\Strings;

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

	public function getProductByUrl(string $url): ?Product
	{
		return $this->em->getRepository(Product::class)->findOneBy(['url' => $url]);
	}

	public function saveProduct(\Traversable|array|\stdClass $values, Product|int|null $product = null): Product
	{
		$values = (array) $values;

		if (is_int($product))
			$product = $this->getProduct($product);

		if ($product === null) {
			$category = $this->getCategory($values['category']);
			$product = new Product($category);
		}

		foreach (['name', 'EAN', 'HSCode', 'visible', 'description', 'shortDescription'] as $var) {
			if (isset($values[$var]))
				$product->{'set' . ucfirst($var)}($values[$var]);
		}

		if (isset($values['vatType']))
			$product->setVatType(VatType::from($values['vatType']));

		if (isset($values['price']))
			$product->setPrice((float) str_replace([' ', ','], ['', '.'], $values['price']));

		if (isset($values['category']) && !isset($category)) {
			$category = $this->getCategory($values['category']);
			$product->setCategory($category);
		}

		$originalUrl = (isset($values['url']) && $values['url'] !== '') ? $values['url'] : Strings::webalize($values['name']);
		$url = $originalUrl;
		$productExists = $this->getProductByUrl($url);
		$i = 1;
		while ($productExists instanceof Product && $productExists->getId() !== $product->getId()) {
			$i++;
			$url = $originalUrl . (string) $i;
			$productExists = $this->getProductByUrl($url);
		}

		$product->setUrl($url);

		$this->em->persist($product);
		$this->em->flush();

		return $product;
	}

	/**
	 * @return Product[]
	 */
	public function listProducts(): array|Collection
	{
		return $this->em->createQueryBuilder()->select('p')
			->from(Product::class, 'p', 'p.id')
			->getQuery()
			->getResult();
	}

	/**
	 * @return Category[]
	 */
	public function getCategories(): array
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

	/**
	 * @param FileUpload[] $images
	 */
	public function addImages(Product $product, FileUpload|array $images): void
	{
		if ($images instanceof FileUpload)
			$images = [$images];

		foreach ($images as $i) {
			$image = new ProductImage();
			/** @todo: security */
			$targetPath = __DIR__ . '/../../../../www/uploads/' . $image->getUuid();
			$i->move($targetPath);
			if (file_exists($targetPath)) {
				$product->addImage($image);
				$this->em->persist($image);
			}
		}

		$this->em->flush();
	}
}
<?php declare(strict_types=1);

namespace Eshop\Model\ORM\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Eshop\Model\ORM\Lists\VatType;

class Product extends AbstractEntity
{
	private string $HSCode = '';

	private string $EAN = '';

	private string $SKU = '';

	private string $name = '';

	private float $price = 0.0;

	private VatType $vatType = VatType::STANDARD_RATE;

	private StockItem $stockItem;

	private Category $category;

	private array|Collection $images;

	public function __construct(Category $category)
	{
		$this->stockItem = new StockItem($this);
		$this->category = $category;
		$this->images = new ArrayCollection();
	}

	public function getHSCode(): string
	{
		return $this->HSCode;
	}

	public function setHSCode(string $HSCode): void
	{
		$this->HSCode = $HSCode;
	}

	public function getEAN(): string
	{
		return $this->EAN;
	}

	public function setEAN(string $EAN): void
	{
		$this->EAN = $EAN;
	}

	public function getSKU(): string
	{
		return $this->SKU;
	}

	public function setSKU(string $SKU): void
	{
		$this->SKU = $SKU;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function setName(string $name): void
	{
		$this->name = $name;
	}

	public function getPrice(): float
	{
		return $this->price;
	}

	public function setPrice(float $price): void
	{
		$this->price = $price;
	}

	public function getVatType(): VatType
	{
		return $this->vatType;
	}

	public function setVatType(VatType $vatType): void
	{
		$this->vatType = $vatType;
	}

	public function getStock(): StockItem
	{
		return $this->stockItem;
	}

	public function isInStock(): bool
	{
		return $this->getStock()->getQuantity() > 0;
	}

	public function getCategory(): Category
	{
		return $this->category;
	}

	public function setCategory(Category $category): void
	{
		$this->category = $category;
	}

	/**
	 * @return ProductImage[]
	 */
	public function getImages(): Collection|array
	{
		return $this->images;
	}

	public function addImage(ProductImage $image): void
	{
		if (!$this->getImages()->contains($image)) {
			$this->getImages()->add($image);
			$image->setProduct($this);
		}
	}

	public function getUrl(): string
	{
		return (string) $this->getId();	// @todo
	}

	public function getMainImageUrl(): string
	{
		$first = $this->getImages()->first();
		return $first !== false ? $first->getUuid() : '';
	}
}
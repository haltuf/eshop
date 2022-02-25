<?php declare(strict_types=1);

namespace Eshop\Model\ORM\Entity;

use Ramsey\Uuid\Uuid;

class ProductImage extends AbstractEntity
{
	private string $name;

	private string $uuid;

	private Product $product;

	private int $position;

	public function __construct()
	{
		$this->uuid = Uuid::uuid4()->toString();
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function setName(string $name): void
	{
		$this->name = $name;
	}

	public function getProduct(): Product
	{
		return $this->product;
	}

	public function setProduct(Product $product): void
	{
		$this->product = $product;
	}

	public function getPosition(): int
	{
		return $this->position;
	}

	public function setPosition(int $position): void
	{
		$this->position = $position;
	}

	public function getUuid(): string
	{
		return $this->uuid;
	}

}
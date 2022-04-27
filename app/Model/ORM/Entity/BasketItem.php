<?php declare(strict_types=1);

namespace Eshop\Model\ORM\Entity;

use DateTime;

class BasketItem extends AbstractEntity
{

	private ?Basket $basket = null;

	private readonly Product $product;

	private int $quantity = 1;

	private float $price = 0.0;

	private readonly DateTime $createdAt;

	public function __construct(Basket $basket, Product $product, int $quantity = 1)
	{
		$this->basket = $basket;
		$this->product = $product;
		$this->setQuantity($quantity);
		$this->setPrice($product->getPrice());
		$this->createdAt = new DateTime();

		$basket->addItem($this);
	}

	public function getBasket(): Basket
	{
		return $this->basket;
	}

	public function getProduct(): Product
	{
		return $this->product;
	}

	public function getQuantity(): int
	{
		return $this->quantity;
	}

	public function setQuantity(int $quantity): void
	{
		$this->quantity = $quantity;
	}

	public function getPrice(): float
	{
		return $this->price;
	}

	public function setPrice(float $price): void
	{
		$this->price = $price;
	}

	public function getCreatedAt(): DateTime
	{
		return $this->createdAt;
	}

	public function delete(): void
	{
		$this->basket = null;
	}
}
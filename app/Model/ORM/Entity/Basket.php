<?php declare(strict_types=1);

namespace Eshop\Model\ORM\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Eshop\Model\ORM\Embeddable\Address;
use Eshop\Model\ORM\Exception\ProductNotAvailable;
use Eshop\Model\ORM\Traits\TOrderInfo;
use Ramsey\Uuid\Uuid;

class Basket extends AbstractEntity
{

	use TOrderInfo;

	private readonly string $visitorId;

	private readonly string $uniqueId;

	/** @var BasketItem[] */
	private Collection $items;

	public function __construct(string $visitorId)
	{
		$this->visitorId = $visitorId;
		$this->uniqueId = Uuid::uuid4()->getHex()->toString();
		$this->createdAt = new \DateTime();
		$this->items = new ArrayCollection();
		$this->deliveryAddress = new Address();
		$this->invoiceAddress = new Address();
	}

	public function getVisitorId(): string
	{
		return $this->visitorId;
	}

	public function getUniqueId(): string
	{
		return $this->uniqueId;
	}

	/**
	 * @return BasketItem[]
	 */
	public function getItems(): array
	{
		return $this->items->toArray();
	}

	public function addItem(BasketItem $item): void
	{
		$key = $item->getProduct()->getId();
		if ($item->getBasket() !== $this)
			throw new \Exception();

		if (!array_key_exists($key, $this->getItems()))
			$this->items[$key] = $item;
	}

	/**
	 * @throws ProductNotAvailable
	 */
	public function addProduct(Product $product, int $quantity = 1): void
	{
		$key = $product->getId();
		$this->assertProductAvailable($product, $quantity);

		if (!array_key_exists($key, $this->getItems()))
			$this->items[$key] = new BasketItem($this, $product, $quantity);
		else
			$this->getItems()[$key]->setQuantity($this->getItems()[$key]->getQuantity() + $quantity);
	}

	public function removeProduct(Product $product, int $quantity = 0): void
	{
		$key = $product->getId();
		if (!array_key_exists($key, $this->getItems()))
			return ;

		$currentQuantity = $this->getItems()[$key]->getQuantity();
		if ($quantity === 0 || $quantity === $currentQuantity) {
			$removedItem = $this->getItems()[$key];
			unset($this->items[$key]);
			$removedItem->delete();
			return ;
		}

		if ($quantity < $currentQuantity) {
			$this->getItems()[$key]->setQuantity($currentQuantity - $quantity);
		}
	}

	public function getTotal(): float
	{
		$total = 0.0;

		foreach ($this->getItems() as $item) {
			$total += $item->getPrice() * $item->getQuantity();
		}

		return $total;
	}

	public function getItemCount(): int
	{
		$count = 0;
		foreach ($this->getItems() as $item) {
			$count += $item->getQuantity();
		}

		return $count;
	}

	/**
	 * @throws ProductNotAvailable
	 */
	private function assertProductAvailable(Product $product, int $quantity): void
	{
		$currentStock = array_key_exists($product->getId(), $this->getItems()) ? $this->getItems()[$product->getId()]->getQuantity() : 0;

		if ($product->getStock()->getQuantity() < $currentStock + $quantity)
			throw new ProductNotAvailable();
	}


}
<?php declare(strict_types=1);

namespace Eshop\Model\ORM\Entity;

class StockTransaction extends AbstractEntity
{

	private \DateTime $createdAt;

	public function __construct(
		private StockItem $stockItem,
		private int $quantity
	) {
		$this->createdAt = new \DateTime();
	}

	public function getQuantity(): int
	{
		return $this->quantity;
	}

	public function getCreatedAt(): \DateTime
	{
		return $this->createdAt;
	}
}
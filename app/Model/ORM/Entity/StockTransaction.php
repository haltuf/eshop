<?php declare(strict_types=1);

namespace Eshop\Model\ORM\Entity;

class StockTransaction extends AbstractEntity
{

	public function __construct(
		private StockItem $stockItem,
		private int $quantity
	) {}

	public function getQuantity(): int
	{
		return $this->quantity;
	}
}
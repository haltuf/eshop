<?php declare(strict_types=1);

namespace Eshop\Model\ORM\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class StockItem extends AbstractEntity
{
	private int $minimumBalance = 0;

	private int $quantity = 0;

	private int $version = 0;

	private array|Collection $transactions;

	private Product $product;

	public function __construct(Product $product)
	{
		$this->product = $product;
		$this->transactions = new ArrayCollection();
	}

	public function getProduct(): Product
	{
		return $this->product;
	}

	public function getQuantity(): int
	{
		return $this->quantity;
	}

	public function addTransaction(int $quantity): void
	{
		$this->assertTransactionAllowed($quantity);
		$transaction = new StockTransaction($this, $quantity);
		$this->transactions->add($transaction);
		$this->quantity += $quantity;
	}

	private function assertTransactionAllowed(int $quantity): bool
	{
		$futureBalance = $this->getQuantity() + $quantity;
		$allowedMinimumBalance = $this->minimumBalance;
		if ($futureBalance < $allowedMinimumBalance)
			throw new \Exception('Cannot perform stock transaction, stock quantity would go below zero.');

		return true;
	}
}
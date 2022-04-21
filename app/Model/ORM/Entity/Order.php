<?php declare(strict_types=1);

namespace Eshop\Model\ORM\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Eshop\Model\ORM\Embeddable\Address;
use Eshop\Model\ORM\Embeddable\PaymentInfo;
use Eshop\Model\ORM\Embeddable\ShippingInfo;
use Eshop\Model\ORM\Lists\OrderStatus;
use Eshop\Model\ORM\Traits\TOrderInfo;

class Order extends AbstractEntity
{

	use TOrderInfo;

	private float $total = 0.00;

	private Collection $items;

	private OrderStatus $status;

	public function __construct()
	{
		$this->createdAt = new DateTime();
		$this->items = new ArrayCollection();
	}

	public function getTotal(): float
	{
		return $this->total;
	}

	/**
	 * @return OrderItem[]
	 */
	public function getItems(): Collection
	{
		return $this->items;
	}

	public function getStatus(): OrderStatus
	{
		return $this->status;
	}

	public function setStatus(OrderStatus $status): void
	{
		$this->status = $status;
	}
}
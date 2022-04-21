<?php declare(strict_types=1);

namespace Eshop\Model;

use Eshop\Model\ORM\Entity\ShippingCzechPostDeliveryToHand;
use Eshop\Model\ORM\Entity\ShippingGLS;
use Eshop\Model\ORM\Entity\ShippingMethod;
use Eshop\Model\ORM\Entity\ShippingPacketa;

class ShippingManager
{

	private array $methods = [
		ShippingPacketa::class,
		ShippingGLS::class,
		ShippingCzechPostDeliveryToHand::class,
	];

	/** @var ShippingMethod[] */
	private array $shipping = [];


	/**
	 * @return ShippingMethod[]
	 */
	public function getShippingMethods(): array
	{
		if ($this->shipping === []) {
			foreach ($this->methods as $method) {
				$shipping = new $method;
				$this->shipping[$shipping->getCode()] = $shipping;
			}
		}

		return $this->shipping;
	}

}
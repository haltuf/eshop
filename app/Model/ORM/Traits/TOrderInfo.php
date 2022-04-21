<?php declare(strict_types=1);

namespace Eshop\Model\ORM\Traits;

use DateTime;
use Eshop\Model\ORM\Embeddable\Address;
use Eshop\Model\ORM\Entity\PaymentMethod;
use Eshop\Model\ORM\Entity\ShippingMethod;

trait TOrderInfo
{

	private readonly DateTime $createdAt;

	private ?Address $invoiceAddress = null;

	private bool $deliverySame = true;

	private ?Address $deliveryAddress = null;

	private ?string $email = null;

	private ?string $phone = null;

	private ?ShippingMethod $shippingMethod = null;

	private ?PaymentMethod $paymentMethod = null;

}
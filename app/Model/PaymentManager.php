<?php declare(strict_types=1);

namespace Eshop\Model;

use Eshop\Model\ORM\Entity\PaymentBankAccount;
use Eshop\Model\ORM\Entity\PaymentMethod;
use Eshop\Model\ORM\Entity\PaymentOnlineCard;

class PaymentManager
{

	private array $methods = [
		PaymentOnlineCard::class,
		PaymentBankAccount::class
	];

	/** @var PaymentMethod[] */
	private array $payment = [];


	/**
	 * @return PaymentMethod[]
	 */
	public function getPaymentMethods(): array
	{
		if ($this->payment === []) {
			foreach ($this->methods as $method) {
				$payment = new $method;
				$this->payment[$payment->getCode()] = $payment;
			}
		}

		return $this->payment;
	}

}
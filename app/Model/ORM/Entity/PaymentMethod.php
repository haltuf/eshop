<?php declare(strict_types=1);

namespace Eshop\Model\ORM\Entity;

abstract class PaymentMethod extends AbstractEntity
{

	protected string $code;

	protected string $name;

	protected float $price;

	protected string $settings;


	public function getCode(): string
	{
		return $this->code;
	}
}
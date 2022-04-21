<?php declare(strict_types=1);

namespace Eshop\Presenters\Templates;

use Eshop\Model\ORM\Entity\Basket;

final class CommonTemplate extends BaseTemplate
{
	public Basket $basket;
}
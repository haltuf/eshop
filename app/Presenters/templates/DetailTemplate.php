<?php declare(strict_types=1);

namespace Eshop\Presenters\Templates;

use Eshop\Model\ORM\Entity\Product;

final class DetailTemplate extends BaseTemplate
{
	public Product $product;
}
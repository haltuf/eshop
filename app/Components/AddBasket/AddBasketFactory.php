<?php declare(strict_types=1);

namespace Eshop\Components;

use Eshop\Model\ORM\Entity\Product;

interface AddBasketFactory
{
	public function create(Product $product, bool $isSimple = false): AddBasket;
}
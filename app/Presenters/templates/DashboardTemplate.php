<?php declare(strict_types=1);

namespace Eshop\Presenters\Templates;

use Doctrine\Common\Collections\Collection;
use Eshop\Model\ORM\Entity\Product;

class DashboardTemplate
{
	/** @var Product[] */
	public array|Collection $products;
}
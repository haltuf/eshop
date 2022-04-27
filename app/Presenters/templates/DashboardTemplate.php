<?php declare(strict_types=1);

namespace Eshop\Presenters\Templates;

use Doctrine\Common\Collections\Collection;
use Eshop\Model\ORM\Entity\Category;
use Eshop\Model\ORM\Entity\Product;

final class DashboardTemplate extends BaseTemplate
{
	/** @var Product[] */
	public array|Collection $products;

	/** @var Category[] */
	public array|Collection $categories;
}
<?php declare(strict_types=1);

namespace Eshop\Model\ORM\Services;

use Doctrine\ORM\EntityManagerInterface;

abstract class AbstractService
{
	public function __construct(
		protected EntityManagerInterface $em,
	) {}
}
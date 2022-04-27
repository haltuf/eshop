<?php declare(strict_types=1);

namespace Eshop\Model\ORM\Entity;

class AbstractEntity
{
	protected ?int $id = 0;

	public function getId(): int
	{
		return $this->id;
	}

}

<?php declare(strict_types=1);

namespace Eshop\Model\ORM\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Category extends AbstractEntity
{

	private string $name;

	private ?Category $parent = null;

	private array|Collection $children;

	private Collection $products;

	public function __construct(string $name, ?Category $parent = null)
	{
		$this->children = new ArrayCollection();
		$this->products = new ArrayCollection();
		$this->setName($name);
		$this->setParent($parent);
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function setName(string $name): void
	{
		$this->name = $name;
	}

	public function getParent(): ?Category
	{
		return $this->parent;
	}

	public function setParent(?Category $parent): void
	{
		$this->getParent()?->removeChild($this);
		$this->parent = $parent;
		$this->getParent()?->addChild($this);
	}

	/**
	 * @return Category[]
	 */
	public function getChildren(): array|Collection
	{
		return $this->children;
	}

	public function addChild(Category $category): void
	{
		if ($category->getParent() !== null && $category->getParent() !== $this)
			throw new \Exception('Cannot add this category, as it already has a different Parent.');

		if ($this->getChildren()->contains($category) === false) {
			$this->getChildren()->add($category);
			//$category->setParent($this);
		}
	}

	public function removeChild(Category $category): void
	{
		if ($this->getChildren()->contains($category)) {
			$this->getChildren()->removeElement($category);
			//$category->setParent(null);
		}
	}

	public function getProductsCount(): int
	{
		return $this->products->count();
	}
}
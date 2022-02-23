<?php declare(strict_types=1);

namespace Eshop\Tests\Unit\Model\Entity;

$container = require __DIR__ . '/../../../bootstrap.php';

use Doctrine\ORM\EntityManagerInterface;
use Eshop\Model\ORM\Entity\Category;
use Eshop\Model\ORM\Entity\Product;
use Eshop\Model\ORM\Entity\User;
use Eshop\Model\ORM\Services\UserService;
use Eshop\Tests\ORMTestCase;
use Nette\Security\AuthenticationException;
use Nette\Security\SimpleIdentity;
use Tester\Assert;


class CategoryTest extends ORMTestCase
{

	public function testCategoryWithoutParent()
	{
		$category = new Category('Kategorie 1');
		Assert::null($category->getParent());
		Assert::same('Kategorie 1', $category->getName());
	}

	public function testCategoryWithParent()
	{
		$parent = new Category('Parent');
		$child = new Category('Child', $parent);

		Assert::null($parent->getParent());
		Assert::same($parent, $child->getParent());
		Assert::true($parent->getChildren()->contains($child));
	}

	public function testSwitchCategoryParent()
	{
		$oldParent = new Category('Old parent');
		$newParent = new Category('New parent');
		$child = new Category('Child', $oldParent);

		Assert::same($oldParent, $child->getParent());
		Assert::true($oldParent->getChildren()->contains($child));
		Assert::false($newParent->getChildren()->contains($child));

		$child->setParent($newParent);
		Assert::same($newParent, $child->getParent());
		Assert::false($oldParent->getChildren()->contains($child));
		Assert::true($newParent->getChildren()->contains($child));
	}

	public function testSwitchCategoryParentReversedSide()
	{
		$oldParent = new Category('Old parent');
		$newParent = new Category('New parent');
		$child = new Category('Child', $oldParent);

		Assert::same($oldParent, $child->getParent());
		Assert::true($oldParent->getChildren()->contains($child));
		Assert::false($newParent->getChildren()->contains($child));

		Assert::exception(function() use ($newParent, $child) {
			$newParent->addChild($child);
		}, \Exception::class);
	}
}

(new CategoryTest())->run();
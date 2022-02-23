<?php declare(strict_types=1);

namespace Eshop\Tests\Fixtures;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Eshop\Model\ORM\Entity\Category;
use Eshop\Model\ORM\Entity\Product;
use Eshop\Model\ORM\Lists\VatType;

class BasicFixture implements FixtureInterface
{
	public function load(ObjectManager $manager)
	{
		$category = new Category('Kategorie 1');
		$manager->persist($category);

		$product = new Product($category);
		$product->setVatType(VatType::STANDARD_RATE);
		$product->setPrice(125.89);
		$product->setHSCode('07031010');
		$product->setEAN('401234567890');
		$product->setName('První produkt');
		$product->setSKU('001-R');

		$product->getStock()->addTransaction(10);
		$product->getStock()->addTransaction(-2);

		$manager->persist($product);
		$manager->flush();
	}
}
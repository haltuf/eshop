<?php declare(strict_types=1);

namespace Eshop\Tests\Fixtures;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Eshop\Model\ORM\Entity\Category;
use Eshop\Model\ORM\Entity\Product;
use Eshop\Model\ORM\Entity\ProductImage;
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
		$product->setUrl('prvni-produkt');
		$product->setVisible(true);
		$product->setShortDescription('This is short description.');
		$product->setDescription('This is long description.');

		$product->getStock()->addTransaction(10);
		$product->getStock()->addTransaction(-2);

		$product->addImage(new ProductImage('c7ad3886-fa1e-41d5-8d5d-1264453b6249'));
		$product->addImage(new ProductImage('d989aa6a-83b8-4dfb-bacf-7ebd443985c7'));
		$product->addImage(new ProductImage('b2fcf297-95e1-41f8-bfed-1bb45069a589'));
		$product->addImage(new ProductImage('b326f391-7a56-4945-b823-e043b10a9166'));

		$manager->persist($product);
		$manager->flush();
	}
}
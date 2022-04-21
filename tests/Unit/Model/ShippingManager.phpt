<?php declare(strict_types=1);

namespace Eshop\Tests\Unit\Model;

require __DIR__ . '/../../bootstrap.php';

use Eshop\Model\ORM\Entity\ShippingCzechPostDeliveryToHand;
use Eshop\Model\ORM\Entity\ShippingGLS;
use Eshop\Model\ORM\Entity\ShippingPacketa;
use Eshop\Model\ORM\Services\BasketService;
use Eshop\Model\ShippingManager;
use Eshop\Tests\ORMTestCase;
use Nette\Http\Session;
use Tester\Assert;


class ShippingManagerTest extends ORMTestCase
{

	public function testGetShippingMethods()
	{
		$sm = new ShippingManager();
		$shippingMethods = $sm->getShippingMethods();
		Assert::type('array', $shippingMethods);
		Assert::count(3, $shippingMethods);
		Assert::same(['shippingpacketa', 'shippinggls', 'shippingczechpostdeliverytohand'], array_keys($shippingMethods));
		Assert::type(ShippingPacketa::class, $shippingMethods['shippingpacketa']);
		Assert::type(ShippingGLS::class, $shippingMethods['shippinggls']);
		Assert::type(ShippingCzechPostDeliveryToHand::class, $shippingMethods['shippingczechpostdeliverytohand']);
	}
}

(new ShippingManagerTest())->run();
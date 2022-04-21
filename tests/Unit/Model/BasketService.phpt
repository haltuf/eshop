<?php declare(strict_types=1);

namespace Eshop\Tests\Unit\Model;

require __DIR__ . '/../../bootstrap.php';

use Eshop\Model\ORM\Services\BasketService;
use Eshop\Tests\ORMTestCase;
use Nette\Http\Session;
use Tester\Assert;


class BasketTest extends ORMTestCase
{
	protected BasketService $basketService;

	public function setUp()
	{
		parent::setUp();
		$session = $this->createContainer()->getByType(Session::class);
		$this->basketService = new BasketService($this->em, $session);
	}

	public function testProductServiceAvailable()
	{
		Assert::type(BasketService::class, $this->basketService);
	}
}

(new BasketTest())->run();
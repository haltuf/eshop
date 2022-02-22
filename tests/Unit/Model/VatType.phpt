<?php declare(strict_types=1);

namespace Eshop\Tests\Unit\Model;

$container = require __DIR__ . '/../../bootstrap.php';

use Eshop\Model\ORM\Lists\VatType;
use Eshop\Tests\AbstractTestCase;
use Tester\Assert;


class VatTypeTest extends AbstractTestCase
{

	public function testItems()
	{
		Assert::same([
			'standard' => 'Základní sazba 21 %',
			'reduced_rate1' => 'Snížená sazba 15 %',
			'reduced_rate2' => 'Snížená sazba 10 %',
		], VatType::items());
	}
}

(new VatTypeTest($container))->run();
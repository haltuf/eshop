<?php declare(strict_types=1);

namespace Eshop\Model\ORM\Lists;

enum VatType: string
{
	case STANDARD_RATE = 'standard';
	case REDUCED_RATE_1 = 'reduced_rate1';
	case REDUCED_RATE_2 = 'reduced_rate2';

	public function text(): string
	{
		return match($this) {
			self::STANDARD_RATE => 'Základní sazba 21 %',
			self::REDUCED_RATE_1 => 'Snížená sazba 15 %',
			self::REDUCED_RATE_2 => 'Snížená sazba 10 %',
		};
	}

	public static function items(): array
	{
		$keys = array_column(self::cases(), 'value');
		$values = array_map(
			fn(VatType $type) => $type->text(),
			self::cases());

		return array_combine($keys, $values);
	}
}
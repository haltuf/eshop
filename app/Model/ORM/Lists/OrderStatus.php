<?php declare(strict_types=1);

namespace Eshop\Model\ORM\Lists;

enum OrderStatus: string
{

	case CREATED = 'created';
	case PAID = 'paid';
	case SHIPPED = 'shipped';
	case DELIVERED = 'delivered';
	case CANCELED = 'canceled';
	case RETURNED = 'returned';

	public function text(): string
	{
		return match($this) {
			self::CREATED => 'vytvořena',
			self::PAID => 'zaplacena',
			self::SHIPPED => 'odeslána',
			self::DELIVERED => 'doručena',
			self::CANCELED => 'stornována',
			self::RETURNED => 'vrácena',
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
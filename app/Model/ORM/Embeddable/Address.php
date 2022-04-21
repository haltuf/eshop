<?php declare(strict_types=1);

namespace Eshop\Model\ORM\Embeddable;

class Address
{

	public function __construct(
		private readonly string $name = '',
		private readonly string $surname = '',
		private readonly string $address = '',
		private readonly string $zip = '',
		private readonly string $city = '',
		private readonly string $country = 'CZ',
		private readonly string $company = '',
		private readonly string $taxNumber = '',
		private readonly string $vatNumber = '',
	)
	{

	}

	public function getName(): string
	{
		return $this->name;
	}

	public function getSurname(): string
	{
		return $this->surname;
	}

	public function getAddress(): string
	{
		return $this->address;
	}

	public function getZip(): string
	{
		return $this->zip;
	}

	public function getCity(): string
	{
		return $this->city;
	}

	public function getCountry(): string
	{
		return $this->country;
	}

	public function getCompany(): string
	{
		return $this->company;
	}

	public function getTaxNumber(): string
	{
		return $this->taxNumber;
	}

	public function getVatNumber(): string
	{
		return $this->vatNumber;
	}

}
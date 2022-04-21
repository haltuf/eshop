<?php declare(strict_types=1);

namespace Eshop\Presenters;

use Eshop\Model\ORM\Entity\Product;
use Eshop\Model\ORM\Services\BasketService;
use Eshop\Model\ORM\Services\ProductService;
use Latte\Attributes\TemplateFilter;
use Nette\Application\UI\Presenter;
use Nette\Application\UI\Template;
use Nette\DI\Attributes\Inject;
use Nette\Utils\Random;


abstract class BasePresenter extends Presenter
{

	#[Inject]
	public ProductService $productService;
	#[Inject]
	public BasketService $basketService;

	public function startup()
	{
		parent::startup();
		$this->template->addFilter('displayPrice', [$this, 'displayPrice']);

		$session = $this->getSession()->getSection('eshop');
		if (!isset($session->visitorId))
			$session->visitorId = Random::generate(16, 'a-zA-Z0-9');
	}

	#[TemplateFilter]
	public function displayPrice(Product|float $product): string
	{
		$price = $product instanceof Product ? $product->getPrice() : $product;
		return number_format($price, 2, ',', ' ') . ' Kč';
	}
}

<?php declare(strict_types=1);

namespace Eshop\Presenters;

use Eshop\Components\Breadcrumbs\BreadcrumbsComponent;
use Eshop\Model\ORM\Entity\Basket;
use Eshop\Model\ORM\Exception\ProductNotAvailable;
use Nette\Application\UI\Form;

final class HomepagePresenter extends BasePresenter
{

	protected Basket $basket;

	public function startup()
	{
		parent::startup();
		$this->setLayout('frontend');

		$this->basket = $this->basketService->getBasket();
		$this->template->basket = $this->basket;
	}

	public function actionDefault()
	{

	}

	public function actionList()
	{
		$products = $this->productService->listProducts();
		$this->template->products = $products;
	}

	public function actionDetail(string $url)
	{
		$product = $this->productService->getProduct((int) $url);	// @todo
		if ($product === null)
			$this->error('Tento produkt nebyl nalezen');

		$this->template->product = $product;

		$this->getComponent('addProduct')['product']->setDefaultValue($product->getId());
	}

	public function actionBasket()
	{

	}

	public function actionOrder()
	{

	}

	protected function createComponentAddProduct(): Form
	{
		$form = new Form();
		$form->addSelect('quantity', 'Množství', array_combine(range(1, 8), range(1, 8)));
		$form->addHidden('product');
		$form->addSubmit('send', 'Přidat do košíku');

		$form->onSuccess[] = [$this, 'addProductSuccess'];
		return $form;
	}

	public function addProductSuccess(Form $form): void
	{
		$values = $form->getValues();
		$product = $this->productService->getProduct((int) $values->product);
		if ($product === null)
			$this->error();

		try {
			$this->basketService->add($product, $values->quantity);
		} catch (ProductNotAvailable $e) {
			$this->flashMessage('Tolik kusů nemáme na skladě', 'error');
		}

		$this->redirect('this');
	}

	protected function createComponentBreadcrumbs(): BreadcrumbsComponent
	{
		return new BreadcrumbsComponent();
	}
}

<?php declare(strict_types=1);

namespace Eshop\Presenters;

use Eshop\Components\AddBasket;
use Eshop\Components\AddBasketFactory;
use Eshop\Components\Breadcrumbs\BreadcrumbsComponent;
use Eshop\Model\ORM\Entity\Basket;
use Eshop\Model\ORM\Entity\Product;
use Eshop\Model\ORM\Exception\ProductNotAvailable;
use Nette\Application\UI\Form;
use Nette\Application\UI\Multiplier;
use Nette\DI\Attributes\Inject;

final class HomepagePresenter extends BasePresenter
{

	protected Basket $basket;

	#[Inject]
	public AddBasketFactory $addBasketFactory;

	/**
	 * @var Product[]
	 */
	protected $products;
	protected ?Product $product = null;

	public function startup()
	{
		parent::startup();
		$this->setLayout('frontend');

		$this->basket = $this->basketService->getBasket();
		$this->template->basket = $this->basket;
	}

	public function actionDefault()
	{
		$this->redirect('list');
	}

	public function actionContent(string $id): void
	{
		$templatePath = __DIR__ . '/templates/Homepage/Content/' . $id . '.latte';
		if (!file_exists($templatePath))
			$this->error();

		$this->setLayout('content');
		$this->setView('Content/' . $id);
	}


	public function actionList()
	{
		$this->products = $this->productService->listProducts();
		$this->template->products = $this->products;
		$this->template->categories = $this->productService->getCategories();
	}

	public function actionDetail(string $url)
	{
		$this->product = $this->productService->getProduct((int) $url);	// @todo
		if ($this->product === null)
			$this->error('Tento produkt nebyl nalezen');

		$this->template->product = $this->product;
	}

	public function actionBasket()
	{

	}

	public function actionOrder()
	{

	}

	public function handleRemoveBasket(int $id)
	{
		$product = $this->productService->getProduct($id);
		if ($product === null)
			$this->error();

		$this->basketService->remove($product);
		$this->flashMessage('Produkt ' . $product->getName() . ' byl odstraněn z košíku.', 'basket');
		$this->redirect('this');
	}

	protected function createComponentBreadcrumbs(): BreadcrumbsComponent
	{
		return new BreadcrumbsComponent();
	}

	protected function createComponentAddBasketButton(): Multiplier
	{
		return new Multiplier(function ($id) {
			return $this->addBasketFactory->create($this->products[(int) $id], true);
		});
	}

	protected function createComponentAddBasket(): AddBasket
	{
		return $this->addBasketFactory->create($this->product);
	}

	protected function createComponentUpdateBasket(): Form
	{
		$form = new Form();
		$quantity = $form->addContainer('quantity');
		foreach ($this->basket->getItems() as $item) {
			$quantity->addText((string) $item->getProduct()->getId(), 'Kusů')
				->setDefaultValue($item->getQuantity());
		}

		$form->addSubmit('recalculate', 'Přepočítat košík');
		$form->onSuccess[] = [$this, 'updateBasketSuccess'];

		return $form;
	}

	public function updateBasketSuccess(Form $form): void
	{
		$values = $form->getValues();
		$messages = [];

		foreach ($this->basket->getItems() as $item) {
			$key = $item->getProduct()->getId();
			if (!array_key_exists($key, (array) $values->quantity))
				continue;

			$newQuantity = (int) $values->quantity[$key];
			$currentQuantity = $item->getQuantity();
			if ($newQuantity === $currentQuantity)
				continue;

			if ($newQuantity < 0) {
				$messages[] = 'Chyba pro produkt <strong>' . $item->getProduct()->getName() . '</strong>: není možné vkládat záporné hodnoty';
			}

			if ($newQuantity < $currentQuantity) {
				$diff = $currentQuantity - $newQuantity;
				$messages[] = 'U produktu <strong>' . $item->getProduct()->getName() . '</strong> snížen počet z ' . $currentQuantity . ' ks na ' . $newQuantity . ' ks';
				$this->basketService->remove($item->getProduct(), $diff);
			}

			if ($newQuantity > $currentQuantity) {
				$diff = $newQuantity - $currentQuantity;
				try {
					$this->basketService->add($item->getProduct(), $diff);
					$messages[] = 'U produktu <strong>' . $item->getProduct()->getName() . '</strong> zvýšen počet z ' . $currentQuantity . ' ks na ' . $newQuantity . ' ks';
				} catch (ProductNotAvailable $e) {
					$messages[] = 'U produktu <strong>' . $item->getProduct()->getName() . '</strong> není možné přidat další ' . $diff . ' ks. V košíku máte ' . $currentQuantity . ' ks, skladem máme ' . $item->getProduct()->getStock()->getQuantity() . ' ks.';
				}
			}
		}

		$basket = $this->basketService->getBasket();
		if ($messages)
			$this->flashMessage(implode('<br>', $messages), 'basket');

		$this->redirect('this');
	}
}

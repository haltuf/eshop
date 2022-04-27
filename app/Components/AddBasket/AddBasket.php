<?php declare(strict_types=1);

namespace Eshop\Components;

use Eshop\Model\ORM\Entity\Product;
use Eshop\Model\ORM\Exception\ProductNotAvailable;
use Eshop\Model\ORM\Services\BasketService;
use Nette;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;

class AddBasket extends Control
{

	private ?bool $allowed = null;
	private ?int $stockQuantity = null;
	private ?int $basketQuantity = null;

	public function __construct(
		private Product $product,
		private bool $isSimple,
		private BasketService $basketService
	)
	{
	}

	protected function createComponentForm(): Form
	{
		$form = new Form();
		if ($this->isSimple === false) {
			$min = $this->isAllowed() ? 1 : 0;
			$max = min($this->getStockQuantity() - $this->getBasketQuantity(), 6);
			$form->addSelect('quantity', 'Počet ks', array_combine(range($min, $max), range($min, $max)))
				->setDisabled($this->isAllowed() === false);
		}
		$form->addHidden('product', $this->product->getId());
		$form->addSubmit('send', 'Přidat do košíku');
		$form->onSuccess[] = [$this, 'formSuccess'];

		return $form;
	}

	public function formSuccess(Form $form): void
	{
		$values = $form->getValues();
		try {
			$quantity = $this->isSimple ? 1 : $values->quantity;
			$this->basketService->add($this->product, $quantity);
			$this->getPresenter()->flashMessage('Do košíku jsme přidali ' . $quantity . ' ks produktu <strong>' . $this->product->getName() . '</strong>.', 'basket');
		} catch (ProductNotAvailable $e) {
			$basket = $this->basketService->getBasket();
			$this->getPresenter()->flashMessage('Do košíku nelze přidat ' . $quantity . ' ks produktu <strong>' . $this->product->getName() . '</strong>. V košíku máte ' . $basket->getItems()[$this->product->getId()]->getQuantity() . ' ks, skladem máme ' . $this->product->getStock()->getQuantity() . ' ks.', 'basket');
		}

		$this->getPresenter()->redirect('this');
	}

	public function render(): void
	{
		$this->template->simple = $this->isSimple;
		$this->template->isAllowed = $this->isAllowed();
		if ($this->isAllowed() === false)
			$this->getComponent('form')['send']->setDisabled(true);

		$templateFile = $this->isSimple ? __DIR__ . '/AddBasketSimple.latte' : __DIR__ . '/AddBasketFull.latte';
		$this->template->setFile($templateFile);
		$this->template->render();
	}

	private function isAllowed(): bool
	{
		if ($this->allowed === null)
			$this->allowed = ($this->getStockQuantity() - $this->getBasketQuantity()) > 0;

		return $this->allowed;
	}

	private function getStockQuantity(): int
	{
		if ($this->stockQuantity === null)
			$this->stockQuantity = $this->product->getStock()->getQuantity();

		return $this->stockQuantity;
	}

	private function getBasketQuantity(): int
	{
		if ($this->basketQuantity === null) {
			$basketItems = $this->basketService->getBasket()->getItems();
			$this->basketQuantity = array_key_exists($this->product->getId(), $basketItems) ? $basketItems[$this->product->getId()]->getQuantity() : 0;
		}

		return $this->basketQuantity;
	}
}
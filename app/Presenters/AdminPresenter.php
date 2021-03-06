<?php declare(strict_types=1);

namespace Eshop\Presenters;

use Eshop\Model\ORM\Lists\VatType;
use Eshop\Model\ORM\Services\ProductService;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use Nette\DI\Attributes\Inject;
use Nette\Http\FileUpload;
use Nette\Security\AuthenticationException;


final class AdminPresenter extends BasePresenter
{

	public function startup()
	{
		parent::startup();

		if ($this->getUser()->isLoggedIn() !== true && $this->getAction() !== 'signIn')
			$this->redirect('signIn');

		if ($this->getUser()->isAllowed('backend') !== true && $this->getAction() !== 'signIn')
			$this->redirect('signIn');

		$this->setLayout('admin');
		$this->template->user = $this->getUser();
		$this->template->userData = $this->getUser()->getIdentity()?->getData();
	}

	public function actionSignIn()
	{
		$this->setLayout('signIn');
	}

	public function actionSignOut()
	{
		$this->getUser()->logout();
		$this->flashMessage('Uživatel byl úspěšně odhlášen.', 'success');
		$this->redirect('signIn');
	}

	public function actionDashboard()
	{
		$this->template->products = $this->productService->listProducts();
		$this->template->categories = $this->productService->getCategories();
	}

	public function actionProduct(?int $id = null)
	{
		if ($id !== null) {
			$product = $this->productService->getProduct($id);
			if ($product === null)
				$this->error();

			$this->getComponent('productForm')->setDefaults([
				'name' => $product->getName(),
				'HSCode' => $product->getHSCode(),
				'EAN' => $product->getEAN(),
				'category' => $product->getCategory()->getId(),
				'price' => $product->getPrice(),
				'vatType' => $product->getVatType()->value,
				'url' => $product->getUrl(),
				'visible' => $product->isVisible(),
				'shortDescription' => $product->getShortDescription(),
				'description' => $product->getDescription(),
			]);

			$this->template->product = $product;
		} else {
			$this->getComponent('productForm')['url']->setDisabled(true);
		}
	}

	public function actionCategory(?int $id = null)
	{
		if ($id !== null) {
			$category = $this->productService->getCategory($id);
			if ($category === null)
				$this->error();


			$this->getComponent('categoryForm')->setDefaults([
				'name' => $category->getName(),
			]);

			$this->template->category = $category;
		}
	}

	protected function createComponentSignInForm(): Form
	{
		$form = new Form();
		$form->addProtection();
		$form->addText('username', 'Uživatelské jméno');
		$form->addPassword('password', 'Heslo');
		$form->addSubmit('send', 'Přihlásit se');
		$form->onSuccess[] = [$this, 'signInFormSuccess'];

		return $form;
	}

	public function signInFormSuccess(Form $form)
	{
		$values = $form->getValues();
		try {
			$this->getUser()->login($values->username, $values->password);
			$this->redirect('dashboard');
		} catch (AuthenticationException $e) {
			$this->flashMessage('Uživatelské jméno nebo heslo není správné', 'danger');
			$this->redirect('this');
		}
	}

	protected function createComponentProductForm(): Form
	{
		$form = new Form();
		$form->addProtection();
		$form->addText('name', 'Jméno produktu');
		$form->addText('EAN', 'EAN');
		$form->addText('HSCode', 'HS kód');
		$form->addText('price', 'Cena');
		$form->addSelect('vatType', 'DPH', VatType::items());
		$form->addSelect('category', 'Kategorie', $this->productService->getCategoryItems());
		$form->addMultiUpload('images', 'Obrázky');
		$form->addCheckbox('visible', 'Produkt je viditelný');
		$form->addText('url', 'Nice URL');
		$form->addTextArea('shortDescription', 'Krátký popisek');
		$form->addTextArea('description', 'Popis produktu');
		$form->addSubmit('send', 'Uložit');
		$form->onSuccess[] = [$this, 'productFormSuccess'];

		return $form;
	}

	public function productFormSuccess(Form $form)
	{
		$values = $form->getValues();

		$id = (int) $this->getParameter('id');
		if (empty($id)) {
			$product = $this->productService->saveProduct($values);
			$this->flashMessage('Produkt ID: ' . $product->getId() . ' - ' . $product->getName() . ' byl úspěšně vytvořen.');
		} else {
			$product = $this->productService->saveProduct($values, $id);
			$this->flashMessage('Produkt ID: ' . $product->getId() . ' - ' . $product->getName() . ' byl úspěšně upraven.');
		}

		$this->productService->addImages($product, $values->images);

		$this->redirect('dashboard');
	}

	public function createComponentCategoryForm(): Form
	{
		$form = new Form();
		$form->addProtection();
		$form->addText('name', 'Jméno kategorie');
		$form->addSubmit('send', 'Uložit');
		$form->onSuccess[] = [$this, 'categoryFormSuccess'];

		return $form;
	}
	public function categoryFormSuccess(Form $form)
	{
		$values = $form->getValues();
		$id = (int) $this->getParameter('id');
		if (empty($id)) {
			$category = $this->productService->createCategory($values->name);
			$this->flashMessage('Kategorie ID: ' . $category->getId() . ' - ' . $category->getName() . ' byla úspěšně vytvořena.');
		} else {
			$category = $this->productService->updateCategory($id, $values->name);
			$this->flashMessage('Kategorie ID: ' . $category->getId() . ' - ' . $category->getName() . ' byla úspěšně upravena.');
		}

		$this->redirect('dashboard');
	}

}

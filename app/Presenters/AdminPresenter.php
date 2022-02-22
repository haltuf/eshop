<?php declare(strict_types=1);

namespace Eshop\Presenters;

use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use Nette\Security\AuthenticationException;


final class AdminPresenter extends Presenter
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
}

<?php declare(strict_types=1);

namespace Eshop\Components\Breadcrumbs;

use Nette\Application\UI\Control;

class BreadcrumbsComponent extends Control
{

	public function render()
	{
		$breadcrumbs = [
			'default' => ['default'],
			'list' => ['default', 'list'],
			'detail' => ['default', 'list', 'detail'],
			'basket' => ['default', 'list', 'basket'],
			//'order' => ['defa']
		];
		$names = [
			'default' => 'Hlavní stránka',
			'list' => 'E-shop',
			'basket' => 'Košík',
		];
		$this->template->presenter = $this->presenter;
		$this->template->breadcrumbs = $breadcrumbs;
		$this->template->names = $names;
		$this->template->product = isset($this->presenter->template->product) ? $this->presenter->template->product : null;
		$this->template->render(__DIR__ . '/breadcrumbs.latte');
	}
}
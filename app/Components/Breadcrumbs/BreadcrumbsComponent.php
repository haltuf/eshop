<?php declare(strict_types=1);

namespace Eshop\Components\Breadcrumbs;

use Nette\Application\UI\Control;

class BreadcrumbsComponent extends Control
{

	public function render(bool $light = true)
	{
		$breadcrumbs = [
			'default' => ['default'],
			'list' => ['default', 'list'],
			'detail' => ['default', 'list', 'detail'],
			'basket' => ['default', 'list', 'basket'],
			'content' => ['default', 'content'],
			//'order' => ['defa']
		];
		$names = [
			'default' => 'Hlavní stránka',
			'list' => 'E-shop',
			'basket' => 'Košík',
			'content' => 'Obsah',
		];
		$this->template->presenter = $this->presenter;
		$this->template->breadcrumbs = $breadcrumbs;
		$this->template->names = $names;
		$this->template->product = isset($this->presenter->template->product) ? $this->presenter->template->product : null;
		$this->template->id = $this->presenter->getParameter('id');
		$this->template->light = $light;
		$this->template->render(__DIR__ . '/breadcrumbs.latte');
	}
}
<?php declare(strict_types=1);

namespace Eshop\Model\Security;

use Nette\Security\Permission;

class AuthorizatorFactory
{
	public static function create(): Permission
	{
		$acl = new Permission();
		$acl->addRole('guest');
		$acl->addRole('administrator', 'guest');
		$acl->addResource('backend');

		$acl->allow('administrator', 'backend');

		return $acl;
	}
}
<?php declare(strict_types=1);

namespace Eshop\Tests\Unit\Model;

$container = require __DIR__ . '/../../bootstrap.php';

use Doctrine\ORM\EntityManagerInterface;
use Eshop\Model\ORM\Entity\User;
use Eshop\Model\ORM\Services\UserService;
use Eshop\Tests\ORMTestCase;
use Nette\Security\AuthenticationException;
use Nette\Security\SimpleIdentity;
use Tester\Assert;


class SecurityTest extends ORMTestCase
{

	public function testAuthenticatorAndAuthorizator()
	{
		$user = $this->container->getByType(\Nette\Security\User::class);
		Assert::false($user->isLoggedIn());
		Assert::false($user->isAllowed('backend'));

		$user->login('MichalHaltuf', 'test');
		Assert::true($user->isLoggedIn());
		Assert::true($user->isAllowed('backend'));
	}
}

(new SecurityTest($container))->run();
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


class UserServiceTest extends ORMTestCase
{
	protected UserService $userService;

	public function setUp()
	{
		parent::setUp();
		$this->userService = $this->container->getByType(UserService::class);
	}

	public function testFindUser()
	{
		$user = $this->userService->findUser('MichalHaltuf');
		Assert::type(User::class, $user);

		$user = $this->userService->findUser('unknown');
		Assert::null($user);
	}

	public function testAuthenticateCorrectValues()
	{
		$identity = $this->userService->authenticate('MichalHaltuf', 'test');
		Assert::type(SimpleIdentity::class, $identity);
		Assert::same(['administrator'], $identity->getRoles());
	}

	public function testAuthenticateIncorrectPassword()
	{
		Assert::exception(function () {
			$identity = $this->userService->authenticate('MichalHaltuf', 'wrong');
		}, AuthenticationException::class, 'Password not correct.');
	}

	public function testAuthenticateIncorrectUsername()
	{
		Assert::exception(function () {
			$identity = $this->userService->authenticate('wrong', 'wrong');
		}, AuthenticationException::class, 'Username wrong not found.');
	}
}

(new UserServiceTest($container))->run();
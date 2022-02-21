<?php declare(strict_types=1);

namespace Eshop\Model\ORM\Services;

use Doctrine\ORM\EntityManagerInterface;
use Eshop\Model\ORM\Entity\User;
use Nette\Security\AuthenticationException;
use Nette\Security\Authenticator;
use Nette\Security\IIdentity;
use Nette\Security\Passwords;
use Nette\Security\SimpleIdentity;

class UserService extends AbstractService implements Authenticator
{

	public function __construct(
		protected EntityManagerInterface $em,
		protected Passwords $passwords,
	)
	{}

	public function registerUser(string $username, string $password, string $email, string $role): User
	{
		$user = new User();
		$user->setUsername($username);
		$user->setPassword($this->passwords->hash($password));
		$user->setEmail($email);
		$user->setRole($role);

		$this->em->persist($user);
		$this->em->flush();

		return $user;
	}

	public function findUser(string $username): ?User
	{
		return $this->em->getRepository(User::class)->findOneBy(['username' => $username]);
	}

	public function authenticate(string $username, string $password): IIdentity
	{
		$user = $this->findUser($username);

		if ($user === null)
			throw new AuthenticationException('Username ' . $username . ' not found.');

		if (!$this->passwords->verify($password, $user->getPassword()))
			throw new AuthenticationException( 'Password not correct.');

		return new SimpleIdentity($user->getId(), $user->getRole(), ['username' => $user->getUsername()]);
	}
}
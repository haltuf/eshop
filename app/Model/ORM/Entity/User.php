<?php declare(strict_types=1);

namespace Eshop\Model\ORM\Entity;


class User extends AbstractEntity
{
	protected string $username;

	protected string $password;

	protected string $role;

	protected string $email;

	protected ?string $confirmationHash = null;

	protected ?\DateTimeImmutable $confirmationDate = null;


	public function getUsername(): string
	{
		return $this->username;
	}

	public function setUsername(string $username): void
	{
		$this->username = $username;
	}

	public function getPassword(): string
	{
		return $this->password;
	}

	public function setPassword(string $password): void
	{
		$this->password = $password;
	}

	public function getRole(): string
	{
		return $this->role;
	}

	public function setRole(string $role): void
	{
		$this->role = $role;
	}

	public function getEmail(): string
	{
		return $this->email;
	}

	public function setEmail(string $email): void
	{
		$this->email = $email;
	}

	public function getConfirmationHash(): ?string
	{
		return $this->confirmationHash;
	}

	public function setConfirmationHash(?string $confirmationHash): void
	{
		$this->confirmationHash = $confirmationHash;
	}

	public function getConfirmationDate(): ?\DateTimeImmutable
	{
		return $this->confirmationDate;
	}

	public function setConfirmationDate(?\DateTimeImmutable $confirmationDate): void
	{
		$this->confirmationDate = $confirmationDate;
	}


}

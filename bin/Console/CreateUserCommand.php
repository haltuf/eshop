<?php declare(strict_types=1);

namespace Eshop\Console;

use Eshop\Model\ORM\Services\UserService;
use Nette\DI\Attributes\Inject;
use Nette\Utils\Random;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateUserCommand extends Command
{

	#[Inject]
	public UserService $userService;

	protected static $defaultName = 'eshop:create-user';

	protected static $defaultDescription = 'Creates a new user';

	protected function configure()
	{
		parent::configure();
		$this->addArgument('username', InputArgument::REQUIRED, 'Username');
		$this->addArgument('email', InputArgument::REQUIRED, 'E-mail');
		$this->addArgument('role', InputArgument::REQUIRED, 'Role');
		$this->addArgument('password', InputArgument::OPTIONAL, 'Password');
	}

	public function execute(InputInterface $input, OutputInterface $output)
	{
		$password = $input->getArgument('password') ?? Random::generate(16, 'a-zA-Z0-9');
		$username = $input->getArgument('username');
		$email = $input->getArgument('email');
		$role = $input->getArgument('role');

		$user = $this->userService->registerUser($username, $password, $email, $role);

		$output->writeln('User ' . $username . ' created successfully with password ' . $password . ', email: ' . $email . ' and role: ' . $role);

		return Command::SUCCESS;
	}
}
<?php

declare(strict_types=1);

namespace App\User\Credentials\Console;

use App\ItOps\Uuid\UuidGenerator;
use App\User\Credentials\RegisterUser;
use App\User\Credentials\User;
use App\User\Credentials\UserAlreadyExists;
use App\User\Credentials\UserCredentialsManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @internal
 * @psalm-internal App\User\Credentials\Console
 */
#[AsCommand('user:register', 'Register new user in system')]
final class RegisterUserCommand extends Command
{
    public function __construct(
        private readonly UserCredentialsManager $userManager,
        private readonly UuidGenerator $uuidGenerator,
        ?string $name = null,
        ?callable $code = null,
    ) {
        parent::__construct($name, $code);
    }

    #[\Override]
    protected function configure(): void
    {
        $this->addArgument(name: 'username', mode: InputArgument::REQUIRED, description: 'Username')
            ->addArgument(name: 'password', mode: InputArgument::REQUIRED, description: 'User password')
            ->addOption(name: 'admin', mode: InputOption::VALUE_NONE);
    }

    /**
     * @throws UserAlreadyExists
     */
    #[\Override]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $username = mb_trim((string) $input->getArgument('username'));

        if ('' === $username) {
            throw new \LogicException('Username can not be empty.');
        }

        $password = mb_trim((string) $input->getArgument('password'));

        if ('' === $password) {
            throw new \LogicException('Password can not be empty.');
        }

        $isAdmin = (bool) $input->getOption('admin');

        $this->userManager->registerUser(new RegisterUser(
            id: $this->uuidGenerator->generate(),
            username: $username,
            password: $password,
            roles: $isAdmin ? [User::ROLE_ADMIN] : [User::ROLE_USER],
        ));

        return self::SUCCESS;
    }
}

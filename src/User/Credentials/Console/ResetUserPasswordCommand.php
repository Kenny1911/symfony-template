<?php

declare(strict_types=1);

namespace App\User\Credentials\Console;

use App\User\Credentials\FindUserById;
use App\User\Credentials\FindUserByUsername;
use App\User\Credentials\ResetUserPassword;
use App\User\Credentials\UserCredentialsManager;
use App\User\Credentials\UserNotFound;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Uid\Uuid;

/**
 * @internal
 * @psalm-internal App\User\Credentials\Console
 */
#[AsCommand('user:reset-password', 'Reset user password')]
final class ResetUserPasswordCommand extends Command
{
    public function __construct(
        private readonly UserCredentialsManager $userManager,
        ?string $name = null,
        ?callable $code = null,
    ) {
        parent::__construct($name, $code);
    }

    #[\Override]
    protected function configure(): void
    {
        $this->addArgument(name: 'username-or-id', mode: InputArgument::REQUIRED, description: 'Username or user id')
            ->addArgument(name: 'password', mode: InputArgument::REQUIRED, description: 'New password');
    }

    /**
     * @throws UserNotFound
     */
    #[\Override]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $usernameOrId = (string) $input->getArgument('username-or-id');

        if ('' === $usernameOrId) {
            throw new \LogicException('Username or id not set');
        }

        $password = (string) $input->getArgument('password');

        if ('' === $password) {
            throw new \LogicException('Password can not be empty.');
        }

        $user = (Uuid::isValid($usernameOrId) ? $this->userManager->findUserById(new FindUserById(Uuid::fromString($usernameOrId))) : null)
            ?? $this->userManager->findUserByUsername(new FindUserByUsername($usernameOrId));

        if (null === $user) {
            throw new \LogicException('User not found.');
        }

        $this->userManager->resetUserPassword(new ResetUserPassword(
            id: $user->getId(),
            newPassword: $password,
        ));

        return self::SUCCESS;
    }
}

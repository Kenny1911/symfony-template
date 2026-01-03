<?php

declare(strict_types=1);

namespace App\Tests\User\Credentials;

use App\Tests\KernelTestCase;
use App\User\Credentials\ChangeUserPassword;
use App\User\Credentials\InvalidUserPassword;
use App\User\Credentials\RegisterUser;
use App\User\Credentials\ResetUserPassword;
use App\User\Credentials\User;
use App\User\Credentials\UserAlreadyExists;
use App\User\Credentials\UserCredentialsManager;
use App\User\Credentials\UserNotFound;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Uid\Uuid;

/**
 * @internal
 * @psalm-internal App\Tests\User\Credentials
 */
final class UserCredentialsManagerTest extends KernelTestCase
{
    private UserCredentialsManager $userManager;

    #[\Override]
    protected function setUp(): void
    {
        self::bootKernelAndInitDb();
        $userManager = self::getContainer()->get(UserCredentialsManager::class);
        \assert($userManager instanceof UserCredentialsManager);
        $this->userManager = $userManager;
    }

    /**
     * @throws UserAlreadyExists
     */
    #[Test]
    public function registerUser(): void
    {
        $id = Uuid::v7();
        $repo = self::getEntityManager()->getRepository(User::class);

        self::assertNull($repo->find($id));

        $this->userManager->registerUser(new RegisterUser(
            id: $id,
            username: 'user',
            password: '123',
            roles: [User::ROLE_USER],
        ));

        $user = $repo->find($id);
        self::assertInstanceOf(User::class, $user);
        self::assertUuid($id, $user->getId());
        self::assertSame('user', $user->getUsername());
        self::assertTrue(self::getPasswordHasher()->verify($user->getPassword(), '123'));
        self::assertSame([User::ROLE_USER], $user->getRoles());
    }

    #[Test]
    public function registerUserThrowUserAlreadyExists(): void
    {
        self::expectException(UserAlreadyExists::class);

        $this->userManager->registerUser(new RegisterUser(
            id: Uuid::v7(),
            username: 'user',
            password: '123',
            roles: [User::ROLE_USER],
        ));
        $this->userManager->registerUser(new RegisterUser(
            id: Uuid::v7(),
            username: 'user',
            password: '321',
            roles: [User::ROLE_ADMIN],
        ));
    }

    /**
     * @throws UserAlreadyExists
     * @throws UserNotFound
     * @throws InvalidUserPassword
     */
    #[Test]
    public function changeUserPassword(): void
    {
        $id = Uuid::v7();
        $this->userManager->registerUser(new RegisterUser(
            id: $id,
            username: 'user',
            password: '123',
            roles: [User::ROLE_USER],
        ));
        $this->userManager->changeUserPassword(new ChangeUserPassword(
            id: $id,
            oldPassword: '123',
            newPassword: '456',
        ));

        $user = self::getEntityManager()->getRepository(User::class)->find($id);
        self::assertInstanceOf(User::class, $user);
        self::assertTrue(self::getPasswordHasher()->verify($user->getPassword(), '456'));
    }

    /**
     * @throws InvalidUserPassword
     */
    #[Test]
    public function changeUserPasswordThrowUserNotFound(): void
    {
        self::expectException(UserNotFound::class);

        $this->userManager->changeUserPassword(new ChangeUserPassword(
            id: Uuid::v7(),
            oldPassword: '123',
            newPassword: '456',
        ));
    }

    /**
     * @throws UserAlreadyExists
     * @throws UserNotFound
     */
    #[Test]
    public function changeUserPasswordThrowInvalidUserPassword(): void
    {
        self::expectException(InvalidUserPassword::class);

        $id = Uuid::v7();
        $this->userManager->registerUser(new RegisterUser(
            id: $id,
            username: 'user',
            password: '123',
            roles: [User::ROLE_USER],
        ));
        $this->userManager->changeUserPassword(new ChangeUserPassword(
            id: $id,
            oldPassword: '321', // wrong password
            newPassword: '456',
        ));
    }

    /**
     * @throws UserAlreadyExists
     * @throws UserNotFound
     */
    #[Test]
    public function resetUserPassword(): void
    {
        $id = Uuid::v7();
        $this->userManager->registerUser(new RegisterUser(
            id: $id,
            username: 'user',
            password: '123',
            roles: [User::ROLE_USER],
        ));
        $this->userManager->resetUserPassword(new ResetUserPassword(
            id: $id,
            newPassword: '456',
        ));

        $user = self::getEntityManager()->getRepository(User::class)->find($id);
        self::assertInstanceOf(User::class, $user);
        self::assertTrue(self::getPasswordHasher()->verify($user->getPassword(), '456'));
    }

    #[Test]
    public function resetUserPasswordThrowUserNotFound(): void
    {
        self::expectException(UserNotFound::class);

        $this->userManager->resetUserPassword(new ResetUserPassword(
            id: Uuid::v7(),
            newPassword: '456',
        ));
    }

    private static function getPasswordHasher(): PasswordHasherInterface
    {
        $passwordHasherFactory = self::getContainer()->get(PasswordHasherFactoryInterface::class);
        \assert($passwordHasherFactory instanceof PasswordHasherFactoryInterface);

        return $passwordHasherFactory->getPasswordHasher(User::class);
    }
}

<?php

declare(strict_types=1);

namespace App\User\Credentials;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Uid\Uuid;

/**
 * @api
 */
final readonly class UserCredentialsManager
{
    private PasswordHasherInterface $passwordHasher;

    public function __construct(
        PasswordHasherFactoryInterface $passwordHasherFactory,
        private ManagerRegistry $doctrine,
    ) {
        $this->passwordHasher = $passwordHasherFactory->getPasswordHasher(User::class);
    }

    /**
     * @throws UserAlreadyExists
     */
    public function registerUser(RegisterUser $command): void
    {
        if ($this->getEntityManager()->getRepository(User::class)->count(['username' => $command->username]) > 0) {
            throw UserAlreadyExists::create();
        }

        $user = new User(
            id: $command->id,
            username: $command->username,
            password: $this->hashPassword($command->password),
            roles: $command->roles,
        );
        $em = $this->getEntityManager();
        $em->persist($user);
        $em->flush();
    }

    /**
     * @throws UserNotFound
     * @throws InvalidUserPassword
     */
    public function changeUserPassword(ChangeUserPassword $command): void
    {
        $em = $this->getEntityManager();
        $user = $this->findUserById($command->id) ?? throw UserNotFound::create();

        if (false === $this->passwordHasher->verify(hashedPassword: $user->getPassword(), plainPassword: $command->oldPassword)) {
            throw InvalidUserPassword::create();
        }

        $user->changePassword($this->hashPassword($command->newPassword));
        $em->persist($user);
        $em->flush();
    }

    /**
     * @throws UserNotFound
     */
    public function resetUserPassword(ResetUserPassword $command): void
    {
        $em = $this->getEntityManager();
        $user = $this->findUserById($command->id) ?? throw UserNotFound::create();
        $user->changePassword($this->hashPassword($command->newPassword));
        $em->persist($user);
        $em->flush();
    }

    private function getEntityManager(): EntityManagerInterface
    {
        $em = $this->doctrine->getManagerForClass(User::class);

        if ($em instanceof EntityManagerInterface) {
            return $em;
        }

        throw new \LogicException('Invalid EntityManager for User class.');
    }

    private function findUserById(Uuid $id): ?User
    {
        return $this->getEntityManager()->getRepository(User::class)->find($id);
    }

    /**
     * @param non-empty-string $plainPassword
     * @return non-empty-string
     */
    private function hashPassword(#[\SensitiveParameter] string $plainPassword): string
    {
        /** @var non-empty-string */
        return $this->passwordHasher->hash($plainPassword);
    }
}

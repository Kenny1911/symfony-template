<?php

declare(strict_types=1);

namespace App\User\Credentials;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;

/**
 * @api
 */
#[ORM\Entity]
#[ORM\Table(name: 'users')]
final class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    public const string ROLE_USER = 'ROLE_USER';
    public const string ROLE_ADMIN = 'ROLE_ADMIN';
    public const array AVAILABLE_ROLES = [
        self::ROLE_USER,
        self::ROLE_ADMIN,
    ];

    /**
     * @var non-empty-list<self::ROLE_*>
     */
    #[ORM\Column(type: Types::JSON)]
    private array $roles;

    /**
     * @param non-empty-string $username
     * @param non-empty-string $password
     * @param non-empty-list<self::ROLE_*> $roles
     *
     * @internal
     * @psalm-internal App\User\Credentials
     */
    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: UuidType::NAME)]
        private readonly Uuid $id,
        #[ORM\Column(unique: true)]
        private readonly string $username,
        #[ORM\Column]
        #[\SensitiveParameter]
        private string $password,
        array $roles,
    ) {
        $this->setRoles($roles);
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    /**
     * @return non-empty-string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return non-empty-list<self::ROLE_*>
     */
    #[\Override]
    public function getRoles(): array
    {
        return $this->roles;
    }

    public function hasRole(string $role): bool
    {
        return \in_array($role, $this->roles, true);
    }

    /**
     * @param non-empty-list<self::ROLE_*> $roles
     *
     * @internal
     * @psalm-internal App\User\Credentials
     */
    public function setRoles(array $roles): void
    {
        $this->roles = array_values(array_unique($roles));
    }

    /**
     * @internal
     * @psalm-internal App\User\Credentials
     */
    #[\Override]
    public function eraseCredentials(): void {}

    #[\Override]
    public function getUserIdentifier(): string
    {
        return $this->getUsername();
    }

    #[\Override]
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param non-empty-string $password
     *
     * @internal
     * @psalm-internal App\User\Credentials
     */
    public function changePassword(#[\SensitiveParameter] string $password): void
    {
        $this->password = $password;
    }
}

<?php

declare(strict_types=1);

namespace App\ItOps\Symfony\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * @internal
 * @psalm-internal App\ItOps\Symfony\Security\Voter
 *
 * @extends Voter<'IS_ANONYMOUS', mixed>
 */
final class AnonymousVoter extends Voter
{
    private const string IS_ANONYMOUS = 'IS_ANONYMOUS';

    #[\Override]
    protected function supports(string $attribute, mixed $subject): bool
    {
        return self::IS_ANONYMOUS === $attribute;
    }

    #[\Override]
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        return null === $token->getUser();
    }
}

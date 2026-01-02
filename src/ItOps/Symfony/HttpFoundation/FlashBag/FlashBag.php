<?php

declare(strict_types=1);

namespace App\ItOps\Symfony\HttpFoundation\FlashBag;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\FlashBagAwareSessionInterface;

/**
 * @api
 */
final readonly class FlashBag
{
    public function __construct(
        private RequestStack $requestStack,
    ) {}

    /**
     * @param non-empty-string $message
     */
    public function add(Type $type, string $message): void
    {
        $this->getFlashBag()?->add($type->value, mb_trim($message));
    }

    /**
     * @return list<Message>
     */
    public function all(): array
    {
        $messages = [];

        foreach (Type::cases() as $type) {
            $messages = array_merge(
                $messages,
                array_map(
                    static fn(string $text) => new Message($type, $text),
                    $this->get($type),
                ),
            );
        }

        return $messages;
    }

    /**
     * @return list<non-empty-string>
     */
    public function get(Type $type): array
    {
        /** @psalm-suppress MixedArgument {@see FlashBagInterface::get()} always return string[] */
        return array_values(
            array_filter(
                array_map(
                    'strval',
                    $this->getFlashBag()?->get($type->value) ?? [],
                ),
            ),
        );
    }

    private function getFlashBag(): ?FlashBagInterface
    {
        $request = $this->requestStack->getMainRequest();

        if (null === $request) {
            return null;
        }

        $session = $request->getSession();

        if (false === $session instanceof FlashBagAwareSessionInterface) {
            return null;
        }

        return $session->getFlashBag();
    }
}

<?php

declare(strict_types=1);

namespace App\ItOps\Symfony\Form\FormNormalizer;

use Symfony\Component\Form\FormInterface;

/**
 * @api
 */
final readonly class FormNormalizer
{
    public function normalize(FormInterface $form): mixed
    {
        if (false === $form->getConfig()->getCompound()) {
            return $form->getNormData();
        }

        $data = [];

        foreach ($form as $innerForm) {
            /** @psalm-suppress MixedAssignment */
            $data[$innerForm->getName()] = $this->normalize($innerForm);
        }

        $data = array_filter($data);

        if (null === $form->getParent() && '' !== $form->getName()) {
            return [$form->getName() => $data];
        }

        return $data;
    }
}

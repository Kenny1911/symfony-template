<?php

declare(strict_types=1);

namespace App\Gateway;

use App\ItOps\Breadcrumbs\BreadcrumbsBuilder;
use App\ItOps\Pagination\Paginator;
use App\ItOps\Symfony\Form\FormNormalizer\FormNormalizer;
use App\ItOps\Symfony\HttpFoundation\FlashBag\FlashBag;
use App\ItOps\Uuid\UuidGenerator;
use Kenny1911\SymfonyAttributeForm\AttributeFormFactory;
use Psr\Clock\ClockInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $di): void {
    $di->services()
        ->set(ControllerHelper::class)
            ->args([
                service(TokenStorageInterface::class),
                service(RequestStack::class),
                service(UrlGeneratorInterface::class),
                service(AuthorizationCheckerInterface::class),
                service(FormFactoryInterface::class),
                service(AttributeFormFactory::class),
                service(FormNormalizer::class),
                service(UuidGenerator::class),
                service(Paginator::class),
                service(BreadcrumbsBuilder::class),
                service(FlashBag::class),
                service(ClockInterface::class),
            ]);
};

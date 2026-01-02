<?php

declare(strict_types=1);

namespace App\ItOps\Twig\GlobalVariable;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\param;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $di): void {
    // Repeat `twig.app_variable` service definition
    $di->services()
        ->set(AppVariable::class)
            ->call('setDebug', [param('kernel.debug')])
            ->call('setTokenStorage', [service('security.token_storage')->ignoreOnInvalid()])
            ->call('setRequestStack', [service('request_stack')->ignoreOnInvalid()])
            ->call('setLocaleSwitcher', [service('translation.locale_switcher')->ignoreOnInvalid()])
            ->call('setEnabledLocales', [param('kernel.enabled_locales')]);

    $di->extension('twig', [
        'globals' => [
            'app' => '@' . AppVariable::class,
        ],
    ]);
};

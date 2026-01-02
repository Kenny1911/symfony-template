<?php

declare(strict_types=1);

namespace App\ItOps\Twig\GlobalVariable;

use Symfony\Component\DependencyInjection\ContainerBuilder;

return static function (ContainerBuilder $builder): void {
    $builder->addCompilerPass(new AppVariableOverridePass());
};

<?php

declare(strict_types=1);

/*
 * This file is part of the Contao Categories News Filter extension.
 *
 * (c) INSPIRED MINDS
 */

namespace InspiredMinds\ContaoCategoriesNewsfilter;

use InspiredMinds\ContaoCategoriesNewsfilter\DependencyInjection\Compiler\BackwardsCompatibilityPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ContaoCategoriesNewsFilterBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }

    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new BackwardsCompatibilityPass(), priority: -100);
    }
}

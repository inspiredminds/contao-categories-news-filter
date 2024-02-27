<?php

declare(strict_types=1);

/*
 * This file is part of the Contao Categories News Filter extension.
 *
 * (c) INSPIRED MINDS
 */

namespace InspiredMinds\ContaoCategoriesNewsfilter\ContaoManager;

use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use InspiredMinds\ContaoCategoriesNewsfilter\ContaoCategoriesNewsFilterBundle;

class Plugin implements BundlePluginInterface
{
    public function getBundles(ParserInterface $parser)
    {
        return [
            BundleConfig::create(ContaoCategoriesNewsFilterBundle::class),
        ];
    }
}

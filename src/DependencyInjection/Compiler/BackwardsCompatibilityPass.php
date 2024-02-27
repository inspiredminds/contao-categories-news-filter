<?php

declare(strict_types=1);

/*
 * This file is part of the Contao Categories News Filter extension.
 *
 * (c) INSPIRED MINDS
 */

namespace InspiredMinds\ContaoCategoriesNewsfilter\DependencyInjection\Compiler;

use Codefog\NewsCategoriesBundle\Criteria\NewsCriteriaBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Backwards compatibility for codefog/contao-news_categories:^3.0.
 */
class BackwardsCompatibilityPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if ($container->hasDefinition(NewsCriteriaBuilder::class)) {
            return;
        }

        if (!$container->hasDefinition('codefog_news_categories.news_criteria_builder')) {
            return;
        }

        $container->setAlias(NewsCriteriaBuilder::class, 'codefog_news_categories.news_criteria_builder');
    }
}

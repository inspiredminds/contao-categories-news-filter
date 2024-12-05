<?php

declare(strict_types=1);

/*
 * This file is part of the Contao Categories News Filter extension.
 *
 * (c) INSPIRED MINDS
 */

namespace InspiredMinds\ContaoCategoriesNewsfilter\EventListener;

use Codefog\NewsCategoriesBundle\Criteria\NewsCriteriaBuilder;
use Codefog\NewsCategoriesBundle\Exception\CategoryNotFoundException;
use Contao\CoreBundle\Exception\PageNotFoundException;
use InspiredMinds\ContaoNewsFilterEvent\Event\NewsFilterEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener]
class NewsFilterEventListener
{
    public function __construct(private readonly NewsCriteriaBuilder $criteriaBuilder)
    {
    }

    public function __invoke(NewsFilterEvent $event): void
    {
        try {
            $criteria = $this->criteriaBuilder
                ->getCriteriaForListModule(
                    $event->getArchives(),
                    $event->getFeatured(),
                    $event->getModule(),
                )
            ;
        } catch (CategoryNotFoundException $e) {
            throw new PageNotFoundException($e->getMessage());
        }

        // If $criteria is null, no news are found. Thus force an empty result.
        if (!$criteria) {
            $event
                ->setForceEmptyResult(true)
                ->stopPropagation()
            ;

            return;
        }

        if (!$event->isCountOnly()) {
            $criteria->setLimit($event->getLimit());
            $criteria->setOffset($event->getOffset());
        }

        $event
            ->addColumns($criteria->getColumns())
            ->addValues($criteria->getValues())
            ->addOptions($criteria->getOptions())
        ;

        // Do not set defaults, as the NewsCriteriaBuilder already takes care of that
        $event->setAddDefaults(false);
    }
}

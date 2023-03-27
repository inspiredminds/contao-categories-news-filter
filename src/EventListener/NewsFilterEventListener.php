<?php

declare(strict_types=1);

/*
 * This file is part of the Contao Categories News Filter extension.
 *
 * (c) inspiredminds
 *
 * @license LGPL-3.0-or-later
 */

namespace InspiredMinds\ContaoCategoriesNewsfilter\EventListener;

use Codefog\NewsCategoriesBundle\Criteria\NewsCriteriaBuilder;
use Codefog\NewsCategoriesBundle\Exception\CategoryNotFoundException;
use Contao\CoreBundle\Exception\PageNotFoundException;
use InspiredMinds\ContaoNewsFilterEvent\Event\NewsFilterEvent;

class NewsFilterEventListener
{
    private NewsCriteriaBuilder $criteriaBuilder;

    public function __construct(NewsCriteriaBuilder $criteriaBuilder)
    {
        $this->criteriaBuilder = $criteriaBuilder;
    }

    public function __invoke(NewsFilterEvent $event): void
    {
        try {
            $criteria = $this->criteriaBuilder
                ->getCriteriaForListModule(
                    $event->getArchives(),
                    $event->getFeatured(),
                    $event->getModule()
                )
            ;
        } catch (CategoryNotFoundException $e) {
            throw new PageNotFoundException($e->getMessage());
        }

        if (null === $criteria) {
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

<?php

namespace InspiredMinds\ContaoCategoriesNewsfilter\EventListener;

use Codefog\NewsCategoriesBundle\Criteria\NewsCriteriaBuilder;
use Codefog\NewsCategoriesBundle\Exception\CategoryNotFoundException;
use Contao\CoreBundle\Exception\PageNotFoundException;
use InspiredMinds\ContaoNewsFilterEvent\Event\NewsFilterEvent;

class NewsFilterEventListener
{
    private $criteriaBuilder;

    public function __construct(NewsCriteriaBuilder $criteriaBuilder)
    {
        $this->criteriaBuilder = $criteriaBuilder;
    }

    public function __invoke(NewsFilterEvent $event)
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

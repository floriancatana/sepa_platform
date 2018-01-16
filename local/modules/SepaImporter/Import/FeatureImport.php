<?php

namespace SepaImporter\Import;

use Thelia\Core\Event\TheliaEvents;
use Thelia\Core\Event\Feature\FeatureCreateEvent;
use Thelia\Core\Event\Feature\FeatureUpdateEvent;
use Thelia\ImportExport\Import\AbstractImport;
use Thelia\Model\Feature;
use Thelia\Model\FeatureQuery;

class FeatureImport extends AbstractImport {

    protected $mandatoryColumns = [
        'Id',
        'Locale',
        'Title',
        'Description'
    ];

    public function importData(array $data) {
        /** @var EventDispatcherInterface $eventDispatcher */
        $eventDispatcher = $this->getContainer()->get('event_dispatcher');

        $featureQuery = FeatureQuery::create();
        $feature = $featureQuery->findOneById($data['Id']);

        if ($feature === null) {
            $newFeature = new Feature();
            $featurecreate = new FeatureCreateEvent($newFeature);
            $featurecreate->setLocale($data['Locale'])
                    ->setTitle($data['Title'])
            ;
            $eventDispatcher->dispatch(TheliaEvents::FEATURE_CREATE, $featurecreate);
            //Tlog::getInstance()->error("attributeimport ".$newFeature->__toString());
        }
        $featureupdate = new FeatureUpdateEvent($data['Id']);
        $featureupdate->setLocale($data['Locale'])
                ->setTitle($data['Title'])
                ->setDescription($data['Description'])
        ;
        $eventDispatcher->dispatch(TheliaEvents::FEATURE_UPDATE, $featureupdate);
        //Tlog::getInstance()->info("Changee"."Updated");
    }

}

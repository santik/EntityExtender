<?php

namespace Santik\EntityExtender\Entity;

use Doctrine\Common\EventManager;
use Santik\EntityExtender\Event\MetadataPropertiesToSaveEventArgs;

class EntityManager extends \Doctrine\ORM\EntityManager
{
    public function persist($entity)
    {
        parent::persist($entity);

        if ($entity instanceof ExtendedEntity && $entity->hasNeedToSaveMetadata()) {
            /** @var EventManager $eventManager */
            $eventManager = $this->getEventManager();
            $listener = $this->getConfiguration()->getEntityListenerResolver()->resolve('ExtendedEntitySubscriber');
            if (!method_exists('Santik\EventSubscriber\ExtendedEntitySubscriber',__FUNCTION__)) {
                return;
            }
            $eventManager->addEventListener(__FUNCTION__, $listener);
            $eventManager->dispatchEvent(__FUNCTION__, new MetadataPropertiesToSaveEventArgs($entity));
        }
    }
}
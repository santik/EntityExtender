<?php

namespace Santik\EntityExtender\Event;

use Doctrine\Common\EventArgs;
use Santik\EntityExtender\Entity\ExtendedEntity;

class MetadataPropertiesToSaveEventArgs extends EventArgs
{
    /**
     * @var ExtendedEntity
     */
    private $entity;

    /**
     * @param ExtendedEntity $entity
     */
    public function __construct(ExtendedEntity $entity)
    {
        $this->entity = $entity;
    }

    /**
     * @return ExtendedEntity
     */
    public function getEntity()
    {
        return $this->entity;
    }
}

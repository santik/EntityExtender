<?php

namespace Santik\EntityExtender\Entity;

use Santik\EntityExtender\Annotations\MetadataProperty;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\EntityListeners({"Santik\EntityExtender\EventSubscriber\MetadataEntitySubscriber"})
 */
class MetadataTest extends ExtendedEntity
{
    private $id;

    /**
     * @MetadataProperty("type", name="type",type="integer")
     */
    private $type;


    public function getId()
    {
        return $this->id;
    }

    public function getType()
    {
        return (int)$this->type;
    }

    public function setType($type)
    {
        if ($this->type != $type) {
            $this->needToSaveMetadata = true;
        }

        $this->type = $type;
        return $this;
    }
}

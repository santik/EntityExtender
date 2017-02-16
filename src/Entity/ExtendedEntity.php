<?php

namespace Santik\EntityExtender\Entity;

abstract class ExtendedEntity
{
    protected $needToSaveMetadata = false;

    public function hasNeedToSaveMetadata()
    {
        return $this->needToSaveMetadata;
    }
}
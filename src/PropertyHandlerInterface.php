<?php

namespace Santik\EntityExtender;

use Santik\EntityExtender\Entity\ExtendedEntity;

interface PropertyHandlerInterface
{
    function loadProperty(ExtendedEntity $entity, $propertyName);
    function saveProperty(ExtendedEntity $entity, $propertyName);
}
<?php

namespace Santik\EntityExtender\EventSubscriber;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Inflector\Inflector;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Santik\EntityExtender\Entity\ExtendedEntity;
use Santik\EntityExtender\Event\MetadataPropertiesToSaveEventArgs;
use Santik\EntityExtender\PropertyHandlerInterface;

class ExtendedEntitySubscriber
{
    const ANNOTATION_METADATA = 'Santik\\EntityExtender\\Annotations\\MetadataProperty';

    /**
     * @var AnnotationReader
     */
    private $annotationReader;
    /**
     * @var PropertyHandlerInterface
     */
    private $propertyHandler;

    public function __construct(AnnotationReader $annotationReader, PropertyHandlerInterface $propertyHandler)
    {
        $this->annotationReader = $annotationReader;
        $this->propertyHandler = $propertyHandler;
    }

    public function postLoad($entity, LifecycleEventArgs $event = null)
    {
        $metadataProperties = $this->getMetadataProperties($entity);
        foreach ($metadataProperties as $propertyName) {
            $this->loadProperty($entity, $propertyName);
        }
    }

    public function persist(MetadataPropertiesToSaveEventArgs $args)
    {
        $entity = $args->getEntity();
        $metadataProperties = $this->getMetadataProperties($entity);
        foreach ($metadataProperties as $propertyName) {
            $this->saveProperty($entity, $propertyName);
        }
    }

    private function getMetadataProperties($entity)
    {
        $properties = [];

        $className = get_class($entity);
        $reflectionObj = new \ReflectionObject(new $className);
        $vars = $reflectionObj->getProperties();
        //for proxies
        $parentClass = $reflectionObj->getParentClass();
        if ($parentClass) {
            $vars = array_merge($vars, $parentClass->getProperties());
        }
        foreach ($vars as $property) {
            $key = $property->getName();
            $property = Inflector::camelize($key);
            if ($reflectionObj->hasProperty($property)) {
                $reflectionProp = new \ReflectionProperty($className, $property);
                $this->addProperty($reflectionProp, $properties);
            } elseif ($parentClass && $parentClass->hasProperty($property)) {
                $reflectionProp = new \ReflectionProperty($parentClass->getName(), $property);
                $this->addProperty($reflectionProp, $properties);
            }
        }

        return $properties;
    }

    private function addProperty($reflectionProp, &$properties)
    {
        $metadataProperty = $this->annotationReader->getPropertyAnnotation($reflectionProp, self::ANNOTATION_METADATA);

        if ($metadataProperty) {
            $properties[] = $metadataProperty->name;
        }
    }

    public function loadProperty(ExtendedEntity $entity, $propertyName)
    {
        $this->propertyHandler->loadProperty($entity, $propertyName);
    }

    public function saveProperty(ExtendedEntity $entity, $propertyName)
    {
        $this->propertyHandler->saveProperty($entity, $propertyName);
    }
}


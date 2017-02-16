<?php

use Santik\EntityExtender\Annotations\MetadataProperty;
use Santik\EntityExtender\Entity\MetadataTest;
use Santik\EntityExtender\Event\MetadataPropertiesToSaveEventArgs;
use Santik\EntityExtender\EventSubscriber\ExtendedEntitySubscriber;

class MetadataEntitySubscriberTest extends \PHPUnit_Framework_TestCase
{

    const METADATA_FIELD = 'type';

    const METADATA_VAL = 12345;

    private function getAnnotationReaderMock($entity)
    {
        $annotationReader = $this->getMockBuilder('Doctrine\Common\Annotations\AnnotationReader')->disableOriginalConstructor()->getMock();
        $metadataAnnotation = new MetadataProperty(['name' => self::METADATA_FIELD]);
        $reflection = new \ReflectionProperty(get_class($entity), self::METADATA_FIELD);
        $annotationReader->expects($this->at(1))->method('getPropertyAnnotation')
            ->with($this->equalTo($reflection), $this->equalTo(ExtendedEntitySubscriber::ANNOTATION_METADATA))
            ->will($this->returnValue($metadataAnnotation));

        $annotationReader->expects($this->at(0))->method('getPropertyAnnotation')
            ->will($this->returnValue(null));

        return $annotationReader;
    }

    public function testPostLoad()
    {
        $entity = new MetadataTest();
        $propertyHandler = $this->prophesize(\Santik\EntityExtender\PropertyHandlerInterface::class);
        $propertyHandler->loadProperty($entity, self::METADATA_FIELD)->shouldBeCalled();
        $subscriber = new ExtendedEntitySubscriber($this->getAnnotationReaderMock($entity), $propertyHandler->reveal());
        $subscriber->postLoad($entity, null);
    }

    public function testPersist()
    {

        $entity = new MetadataTest();
        $propertyHandler = $this->prophesize(\Santik\EntityExtender\PropertyHandlerInterface::class);
        $propertyHandler->saveProperty($entity, self::METADATA_FIELD)->shouldBeCalled();
        $subscriber = new ExtendedEntitySubscriber($this->getAnnotationReaderMock($entity), $propertyHandler->reveal());

        $entity->setType(111);
        $this->assertTrue($entity->hasNeedToSaveMetadata());
        $arguments = new MetadataPropertiesToSaveEventArgs($entity);
        $subscriber->persist($arguments);
    }
}
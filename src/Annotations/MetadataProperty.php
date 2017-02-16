<?php
namespace Santik\EntityExtender\Annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target({"PROPERTY"})
 */
final class MetadataProperty extends Annotation
{
    public $name;

    public $value;
}
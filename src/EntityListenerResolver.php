<?php
namespace Santik\EntityExtender;

use DI\Container;
use Doctrine\ORM\Mapping\DefaultEntityListenerResolver;

class EntityListenerResolver extends DefaultEntityListenerResolver
{
    /**
     * @var Container
     */
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function resolve($className)
    {
        $shortName = explode('\\', $className);
        $obj = $this->container->get(end($shortName));
        return $obj;
    }
}
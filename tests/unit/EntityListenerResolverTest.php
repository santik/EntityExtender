<?php


use Santik\EntityExtender\EntityListenerResolver;

class EntityListenerResolverTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var EntityListenerResolver
     */
    private $resolver;

    const TO_RESOLVE = 'name';

    protected function setUp()
    {
        $container = $this->getMockBuilder('DI\Container')->disableOriginalConstructor()->getMock();
        $container->expects($this->any())->method('get')->with($this->equalTo(self::TO_RESOLVE))->will($this->returnValue(self::TO_RESOLVE));
        $this->resolver = new EntityListenerResolver($container);
    }

    public function testResolve()
    {
        $this->assertEquals(self::TO_RESOLVE, $this->resolver->resolve(self::TO_RESOLVE));
    }

}
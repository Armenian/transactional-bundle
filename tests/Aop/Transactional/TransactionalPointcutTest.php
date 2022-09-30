<?php

declare(strict_types=1);

namespace DMP\TransactionalBundle\Tests\Aop\Transactional;

use DMP\TransactionalBundle\Annotation\Transactional;
use DMP\TransactionalBundle\Aop\TransactionalPointcut;
use Doctrine\Common\Annotations\Reader;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;

class TransactionalPointcutTest extends TestCase
{

    private TransactionalPointcut $pointcut;
    private Reader|MockObject $reader;

    protected function setUp(): void
    {
        $this->reader = $this->createMock(Reader::class);
        $this->pointcut = new TransactionalPointcut($this->reader);
    }

    public function testMatchesClass(): void
    {
        $reflectionClass = $this->createMock(ReflectionClass::class);
        $this->assertTrue($this->pointcut->matchesClass($reflectionClass));
    }

    public function testMatchesMethodTrue(): void
    {
        $annotation = $this->createMock(Transactional::class);
        $reflectionMethod = $this->createMock(ReflectionMethod::class);
        $this->reader->expects(self::once())
            ->method('getMethodAnnotation')
            ->with($reflectionMethod, Transactional::class)
            ->willReturn($annotation);

        $this->assertTrue($this->pointcut->matchesMethod($reflectionMethod));
    }


    public function testMatchesMethodFalse(): void
    {
        $reflectionMethod = $this->createMock(ReflectionMethod::class);
        $this->reader->expects(self::once())
            ->method('getMethodAnnotation')
            ->with($reflectionMethod, Transactional::class)
            ->willReturn(null);

        $this->assertFalse($this->pointcut->matchesMethod($reflectionMethod));
    }


}

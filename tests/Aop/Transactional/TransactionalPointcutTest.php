<?php

declare(strict_types=1);

namespace DMP\TransactionalBundle\Tests\Aop\Transactional;

use DMP\TransactionalBundle\Annotation\Transactional;
use DMP\TransactionalBundle\Aop\TransactionalPointcut;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;

class TransactionalPointcutTest extends TestCase
{

    private TransactionalPointcut $pointcut;

    protected function setUp(): void
    {
        $this->pointcut = new TransactionalPointcut();
    }

    public function testMatchesClass(): void
    {
        $reflectionClass = new ReflectionClass(self::class);
        $this->assertTrue($this->pointcut->matchesClass($reflectionClass));
    }

    public function testMatchesMethodTrue(): void
    {
        $reflectionMethod = new ReflectionMethod($this, 'methodWithAttribute');
        $this->assertTrue($this->pointcut->matchesMethod($reflectionMethod));
    }

    public function testMatchesMethodOnClassTrue(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithAttribute::class, 'methodWithoutAttribute');
        $this->assertTrue($this->pointcut->matchesMethod($reflectionMethod));
    }

    public function testMatchesMethodFalse(): void
    {
        $reflectionMethod = new ReflectionMethod($this, 'methodWithoutAttribute');
        $this->assertFalse($this->pointcut->matchesMethod($reflectionMethod));
    }

    #[Transactional]
    public function methodWithAttribute(): void
    {
    }

    public function methodWithoutAttribute(): void
    {
    }
}

#[Transactional]
class ClassWithAttribute
{
    public function methodWithoutAttribute(): void
    {
    }
}

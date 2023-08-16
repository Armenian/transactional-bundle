<?php

declare(strict_types=1);

namespace DMP\TransactionalBundle\Aop;

use DMP\TransactionalBundle\Annotation\Transactional;
use Doctrine\Common\Annotations\Reader;
use DMP\AopBundle\Aop\PointcutInterface;
use ReflectionClass;
use ReflectionMethod;

final class TransactionalPointcut implements PointcutInterface
{
    public function __construct(
        private readonly Reader $reader)
    {}


    public function matchesClass(ReflectionClass $class): bool
    {
        return true;
    }

    public function matchesMethod(ReflectionMethod $method): bool
    {
        return null !== $this->reader->getMethodAnnotation($method, Transactional::class);
    }

}

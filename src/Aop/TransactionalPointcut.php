<?php

declare(strict_types=1);

namespace DMP\TransactionalBundle\Aop;

use DMP\TransactionalBundle\Annotation\Transactional;
use DMP\AopBundle\Aop\PointcutInterface;
use ReflectionClass;
use ReflectionMethod;

final readonly class TransactionalPointcut implements PointcutInterface
{
    public function matchesClass(ReflectionClass $class): bool
    {
        return true;
    }

    public function matchesMethod(ReflectionMethod $method): bool
    {
        if (!empty($method->getAttributes(Transactional::class))) {
            return true;
        }

        $reflectedClass = new ReflectionClass($method->class);

        return !empty($reflectedClass->getAttributes(Transactional::class));
    }
}

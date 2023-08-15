<?php

declare(strict_types=1);

namespace DMP\TransactionalBundle\Aop;

use CG\Proxy\MethodInterceptorInterface;
use CG\Proxy\MethodInvocation;
use Doctrine\ORM\EntityManagerInterface;
use Throwable;

class TransactionalInterceptor implements MethodInterceptorInterface
{

    public function __construct(
        private readonly EntityManagerInterface $em)
    {}

    /**
     * @throws Throwable
     */
    public function intercept(MethodInvocation $invocation): mixed
    {
        $this->em->getConnection()->beginTransaction();
        try {
            $return = $invocation->proceed();
            $this->em->flush();
            $this->em->getConnection()->commit();

            return $return;
        } catch (Throwable $throwable) {
            $this->em->getConnection()->rollBack();

            throw $throwable;
        }
    }
}

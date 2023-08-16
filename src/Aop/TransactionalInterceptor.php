<?php

declare(strict_types=1);

namespace DMP\TransactionalBundle\Aop;

use CG\Proxy\MethodInterceptorInterface;
use CG\Proxy\MethodInvocation;
use DMP\TransactionalBundle\TransactionManager;
use Throwable;

final class TransactionalInterceptor implements MethodInterceptorInterface
{

    public function __construct(
        private readonly TransactionManager $transactionManager)
    {}

    /**
     * @throws Throwable
     */
    public function intercept(MethodInvocation $invocation): mixed
    {
        return $this->transactionManager->run(function () use ($invocation) {
            return $invocation->proceed();
        });
    }
}

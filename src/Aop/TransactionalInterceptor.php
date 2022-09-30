<?php

declare(strict_types=1);

namespace DMP\TransactionalBundle\Aop;

use CG\Proxy\MethodInterceptorInterface;
use CG\Proxy\MethodInvocation;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Throwable;

class TransactionalInterceptor implements MethodInterceptorInterface
{

    public function __construct(
        private readonly EntityManagerInterface $em, private readonly LoggerInterface $logger)
    {}

    /**
     * @throws Throwable
     */
    public function intercept(MethodInvocation $invocation): mixed
    {
        $this->logger->debug('Beginning transaction.', ['method' => $invocation]);
        $this->em->getConnection()->beginTransaction();
        try {
            $return = $invocation->proceed();

            $this->logger->debug('Committing transaction.', ['method' => $invocation]);

            $this->em->flush();
            $this->em->getConnection()->commit();

            return $return;
        } catch (Throwable $throwable) {

            $this->logger->warning('Rolling back transaction.', ['method' => $invocation]);
            $this->em->getConnection()->rollBack();

            throw $throwable;
        }
    }
}

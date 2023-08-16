<?php

declare(strict_types=1);

namespace DMP\TransactionalBundle\Doctrine;

use Closure;
use DMP\TransactionalBundle\TransactionManager;
use Doctrine\ORM\EntityManagerInterface;
use Throwable;
use function var_dump;

abstract class AbstractOrmTransactionManager implements TransactionManager
{
    abstract public function getEntityManager(): EntityManagerInterface;

    public function begin(): void
    {
        $this->getEntityManager()->beginTransaction();
    }

    public function commit(): void
    {
        $this->getEntityManager()->flush();
        $this->getEntityManager()->commit();
    }

    public function rollback(): void
    {
        $this->getEntityManager()->rollback();
        if (!$this->getEntityManager()->getConnection()->isTransactionActive()) {
            $this->getEntityManager()->close();
        }
    }

    /**
     * @throws Throwable
     */
    public function run(Closure $fn): mixed
    {
        $this->begin();
        try {
            $result = $fn();
            $this->commit();
        } catch (Throwable $e) {
            $this->rollBack();
            throw $e;
        }

        return $result;
    }
}

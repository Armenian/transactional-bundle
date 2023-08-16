<?php

declare(strict_types=1);

namespace DMP\TransactionalBundle\Doctrine;

use Doctrine\ORM\EntityManagerInterface;

final class OrmTransactionManager extends AbstractOrmTransactionManager
{
    public function __construct(
        private readonly EntityManagerInterface $em)
    {}

    public function getEntityManager(): EntityManagerInterface
    {
        return $this->em;
    }
}

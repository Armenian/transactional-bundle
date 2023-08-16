<?php

declare(strict_types=1);

namespace DMP\TransactionalBundle;

use Closure;
use Doctrine\ORM\EntityManagerInterface;

interface TransactionManager
{
    public function getEntityManager(): EntityManagerInterface;

    public function begin(): void;

    public function commit(): void;

    public function rollback(): void;

    public function run(Closure $fn): mixed;
}

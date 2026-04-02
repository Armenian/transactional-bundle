<?php

declare(strict_types=1);

namespace DMP\TransactionalBundle;

use DMP\TransactionalBundle\DependencyInjection\TransactionalExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class TransactionalBundle extends Bundle
{
    protected function getContainerExtensionClass(): string
    {
        return TransactionalExtension::class;
    }
}

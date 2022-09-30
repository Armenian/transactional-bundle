<?php

declare(strict_types=1);

namespace DMP\TransactionalBundle;

use DMP\TransactionalBundle\DependencyInjection\TransactionalExtension;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\HttpKernel\KernelInterface;


class TransactionalBundle extends Bundle
{
    protected function getContainerExtensionClass(): string
    {
        AnnotationReader::addGlobalIgnoredName('suppress');
        return TransactionalExtension::class;
    }
}

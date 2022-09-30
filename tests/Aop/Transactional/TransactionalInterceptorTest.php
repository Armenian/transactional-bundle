<?php

declare(strict_types=1);

namespace DMP\TransactionalBundle\Tests\Unit\Aop\Transactional;

use DMP\TransactionalBundle\Aop\TransactionalInterceptor;
use CG\Proxy\MethodInvocation;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Throwable;

class TransactionalInterceptorTest extends TestCase
{
    private const RETURN_VALUE = 42;

    private TransactionalInterceptor $interceptor;
    private EntityManagerInterface|MockObject $em;
    private LoggerInterface|MockObject $logger;
    private MethodInvocation|MockObject $invocation;
    private Connection|MockObject $connection;

    protected function setUp(): void
    {
        $this->em = $this->createMock(EntityManagerInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->invocation = $this->createMock(MethodInvocation::class);
        $this->connection = $this->createMock(Connection::class);

        $this->em->method('getConnection')
            ->willReturn($this->connection);

        $this->interceptor = new TransactionalInterceptor($this->em, $this->logger);
    }

    /**
     * @throws Throwable
     */
    public function testInterceptShouldCommit(): void
    {
        $this->invocation->expects(self::once())
            ->method('proceed')
            ->willReturn(self::RETURN_VALUE);

        $this->em->expects(self::once())
            ->method('flush');

        $this->connection->expects(self::once())
            ->method('beginTransaction');

        $this->connection->expects(self::once())
            ->method('commit');

        $this->assertSame(self::RETURN_VALUE, $this->interceptor->intercept($this->invocation));
    }

    /**
     * @throws Throwable
     */
    public function testInterceptShouldRollback(): void
    {
        $exception = new RuntimeException('Test exception');
        $this->expectExceptionObject($exception);
        $this->invocation->expects(self::once())
            ->method('proceed')
            ->willThrowException($exception);

        $this->connection->expects(self::once())
            ->method('beginTransaction');

        $this->connection->expects(self::once())
            ->method('rollback');

        $this->assertSame(self::RETURN_VALUE, $this->interceptor->intercept($this->invocation));
    }
}

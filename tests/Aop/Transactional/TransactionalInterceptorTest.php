<?php

declare(strict_types=1);

namespace DMP\TransactionalBundle\Tests\Aop\Transactional;

use DMP\TransactionalBundle\Aop\TransactionalInterceptor;
use CG\Proxy\MethodInvocation;
use DMP\TransactionalBundle\TransactionManager;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Throwable;
use function var_dump;

class TransactionalInterceptorTest extends TestCase
{
    private const RETURN_VALUE = 42;

    private TransactionalInterceptor $interceptor;
    private TransactionManager|MockObject $transactionManager;
    private MethodInvocation|MockObject $invocation;

    protected function setUp(): void
    {
        $this->transactionManager = $this->createMock(TransactionManager::class);
        $this->invocation = $this->createMock(MethodInvocation::class);
        $this->connection = $this->createMock(Connection::class);
        $em = $this->createMock(EntityManagerInterface::class);

        $em->method('getConnection')->willReturn($this->connection);
        $this->transactionManager->method('getEntityManager')->willReturn(
            $em
        );

        $this->interceptor = new TransactionalInterceptor($this->transactionManager);
    }

    /**
     * @test
     * @throws Throwable
     */
    public function it_should_commit_on_intercept(): void
    {
        $fn = function () {
            return $this->invocation->proceed();
        };

        $this->transactionManager->expects(self::once())
            ->method('run')
            ->with($fn)
            ->willReturn(self::RETURN_VALUE);

        $this->assertSame(self::RETURN_VALUE, $this->interceptor->intercept($this->invocation));
    }

    /**
     * @test
     * @throws Throwable
     */
    public function it_should_rollback_on_intercept(): void
    {
        $exception = new RuntimeException('Test exception');
        $this->expectExceptionObject($exception);
        $this->transactionManager->expects(self::once())
            ->method('run')
            ->willThrowException($exception);

        $this->assertSame(self::RETURN_VALUE, $this->interceptor->intercept($this->invocation));
    }
}

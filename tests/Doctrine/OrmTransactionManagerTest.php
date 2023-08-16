<?php

declare(strict_types=1);

namespace DMP\TransactionalBundle\Tests\Doctrine;

use DMP\TransactionalBundle\Doctrine\OrmTransactionManager;
use Doctrine\Common\EventManager;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Throwable;

final class OrmTransactionManagerTest extends TestCase
{
    private EntityManagerInterface|MockObject $em;

    private Connection|MockObject $connection;

    private OrmTransactionManager $manager;

    protected function setUp(): void
    {
        $this->connection = $this->createMock(Connection::class);
        $this->eventManager = $this->createMock(EventManager::class);
        $this->em = $this->createMock(EntityManagerInterface::class);
        $this->em
            ->method('getConnection')
            ->willReturn($this->connection)
        ;
        $this->manager = new OrmTransactionManager($this->em);
    }

    /**
     * @test
     */
    public function it_begins_transaction(): void
    {
        $this->em
            ->expects($this->once())
            ->method('beginTransaction')
        ;

        $this->manager->begin();
    }

    /**
     * @test
     */
    public function it_commits_transaction(): void
    {
        $this->em
            ->expects($this->once())
            ->method('commit')
        ;
        $this->em
            ->expects($this->once())
            ->method('flush')
        ;

        $this->manager->commit();
    }

    /**
     * @test
     */
    public function it_rollbacks_transaction(): void
    {
        $this->em
            ->expects($this->once())
            ->method('rollback')
        ;

        $this->manager->rollback();
    }

    /**
     * @test
     * @throws Throwable
     */
    public function it_runs_transaction(): void
    {
        $result = '__result__';

        $fn = static function () use ($result) {
            return $result;
        };

        $this->em->expects($this->once())->method('beginTransaction');
        $this->em->expects($this->once())->method('commit');
        $this->em->expects($this->once())->method('flush');

        $this->assertEquals($result, $this->manager->run($fn));
    }
}

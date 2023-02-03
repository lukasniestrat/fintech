<?php
declare(strict_types = 1);
namespace App\Tests\Integration\Finance;

use App\Entity\Finance\BankAccount;
use App\Entity\Finance\Category;
use App\Entity\Finance\Transaction;
use App\Exception\Finance\TransactionException;
use App\Model\Common\RequestMetaData;
use App\Repository\Finance\TransactionRepository;
use App\Tests\Factory\Common\AssertExceptionTrait;
use App\Tests\Factory\Finance\TransactionFactoryTrait;
use App\Tests\Integration\Common\AbstractFinRepositoryTest;
use DateTime;

class TransactionRepositoryTest extends AbstractFinRepositoryTest
{
    use AssertExceptionTrait,
        TransactionFactoryTrait;

    protected function setUp(): void
    {
        parent::setUp();
        $this->executeFixture(__DIR__ . '/../../_fixtures/Finance/transaction/insert_transactions.sql');
    }

    public function test_it_stores_transaction(): void
    {
        $bankAccount = $this->getEntityManager()->getReference(BankAccount::class, 1);
        $category = $this->getEntityManager()->getReference(Category::class, 1);
        $transaction = $this->getTransactionMock($bankAccount);
        $transaction->setCategory($category);

        $nextInsertId = $this->getCurrentAutoIncForTable('transaction');
        $transaction = $this->getRepository()->storeTransaction($transaction);

        self::assertEquals($nextInsertId, $transaction->getId());
    }

    public function test_it_removes_transaction(): void
    {
        $transaction = $this->getRepository()->getTransactionById(1);
        $this->getRepository()->removeTransaction($transaction);

        $result = $this->connection->executeQuery('SELECT * FROM transaction WHERE id = 1')->fetchAssociative();

        self::assertFalse($result);
    }

    public function test_it_finds_transaction_by_subject(): void
    {
        $transaction = $this->getRepository()->findTransactionsBySubject('EWE GmbH & Co. KG');
        self::assertCount(1, $transaction);

        // returns null if nothing found
        $transaction = $this->getRepository()->findTransactionsBySubject('ABC Bli Bla Blub');
        self::assertNull($transaction);
    }

    public function test_it_gets_transaction_by_id(): void
    {
        $transaction = $this->getRepository()->getTransactionById(1);

        self::assertEquals(1, $transaction->getId());
        self::assertEquals('EWE Stromrechnung', $transaction->getName());

        $this->assertException(function (): void {
            $this->getRepository()->getTransactionById(99999);
        }, TransactionException::class, TransactionException::NOT_FOUND, ['reason' => 'no transaction with id 99999 found']);
    }

    public function test_it_finds_transaction_by_id(): void
    {
        $transaction = $this->getRepository()->findTransactionById(1);

        self::assertEquals(1, $transaction->getId());
        self::assertEquals('EWE Stromrechnung', $transaction->getName());

        $transaction = $this->getRepository()->findTransactionById(9999);

        self::assertNull($transaction);
    }

    public function test_it_gets_all_transactions(): void
    {
        $transactionsList = $this->getRepository()->getTransactions(new RequestMetaData());

        self::assertEquals(5, $transactionsList->getMetaData()->getTotal());
        self::assertCount(5, $transactionsList->getList());
    }

    protected function getRepository(): TransactionRepository
    {
        if (null === $this->repository) {
            $this->repository = self::getContainer()->get(TransactionRepository::class);
        }

        return $this->repository;
    }
}

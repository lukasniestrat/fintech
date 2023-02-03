<?php
declare(strict_types = 1);
namespace App\tests\Unit\Finance\Service;

use App\Entity\Finance\Category;
use App\Model\Common\RequestMetaData;
use App\Service\Finance\TransactionService;
use App\Tests\Factory\Finance\BankAccountFactoryTrait;
use App\Tests\Factory\Finance\CategoryFactoryTrait;
use App\Tests\Factory\Finance\TransactionFactoryTrait;
use App\Tests\Mocks\Finance\Repositories\TransactionRepositoryMock;
use App\Tests\Mocks\Finance\Services\CategoryServiceMock;
use App\Tests\Utils\ReflectionFactory;
use PHPUnit\Framework\TestCase;

class TransactionServiceTest extends TestCase
{
    use BankAccountFactoryTrait,
        TransactionFactoryTrait,
        CategoryFactoryTrait;

    private ?TransactionService $service = null;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new TransactionService(
            new CategoryServiceMock(),
            new TransactionRepositoryMock(),
        );
    }

    public function test_it_stores_transaction(): void
    {
        $bankAccount = $this->getBankAccountMock();
        $transaction = $this->getTransactionMock($bankAccount);

        $this->service->storeTransaction($transaction);

        self::assertEquals(1, TransactionRepositoryMock::$countStoreTransaction);
    }

    public function test_it_removes_transaction(): void
    {
        $bankAccount = $this->getBankAccountMock();
        $transaction = $this->getTransactionMock($bankAccount);

        $this->service->removeTransaction($transaction);

        self::assertEquals(1, TransactionRepositoryMock::$countRemoveTransaction);
    }

    public function test_it_finds_transaction_by_subject(): void
    {
        $this->service->findTransactionsBySubject('test');

        self::assertEquals(1, TransactionRepositoryMock::$countFindTransactionBySubject);
    }

    public function test_it_gets_transaction_by_id(): void
    {
        $this->service->getTransactionById(1);

        self::assertEquals(1, TransactionRepositoryMock::$countGetTransactionById);
    }

    public function test_it_gets_transactions(): void
    {
        $this->service->getTransactions(new RequestMetaData());

        self::assertEquals(1, TransactionRepositoryMock::$countGetTransactions);
    }

    public function test_it_merges_transactions(): void
    {
        $transaction = $this->getTransactionMock(
            $this->getBankAccountMock('Mein Girokonto'),
            'Telekom Rechnung',
            'Deutsche Telekom'
        );
        $updatedTransaction = $this->getTransactionMock(
            $this->getBankAccountMock('Mein zweites Girokonto'),
            'Netflix Monatsabo',
            'Netflix Inc.'
        );

        ReflectionFactory::setPrivateProperty($transaction, 'category', $this->getCategoryMock('Unterhaltung'));
        ReflectionFactory::setPrivateProperty($updatedTransaction, 'category', $this->getCategoryMock('Einkäufe'));

        $mergedTransaction = $this->service->mergeTransactions($transaction, $updatedTransaction);

        self::assertEquals($updatedTransaction->getName(), $mergedTransaction->getName());
        self::assertEquals($updatedTransaction->getSubject(), $mergedTransaction->getSubject());
        self::assertEquals($updatedTransaction->getBankAccount()->getName(), $mergedTransaction->getBankAccount()->getName());
        self::assertEquals($updatedTransaction->getCategory()->getName(), $mergedTransaction->getCategory()->getName());
    }

    public function test_it_finds_category_for_transaction(): void
    {
        $bankAccount = $this->getBankAccountMock();
        $transaction = $this->getTransactionMock($bankAccount);

        $category = ReflectionFactory::callPrivateMethod($this->service, 'getCategoryForTransaction', $transaction);
        self::assertEquals(1, CategoryServiceMock::$countGetCategoriesForTransactionImport);
        self::assertInstanceOf(Category::class, $category);
    }

    public function test_it_tests_if_transaction_exists(): void
    {
        $bankAccount = $this->getBankAccountMock();
        $transaction = $this->getTransactionMock($bankAccount);
        ReflectionFactory::callPrivateMethod($this->service, 'checkIfTransactionExists', $transaction);

        self::assertEquals(1, TransactionRepositoryMock::$countFindTransactionBySubject);
    }
}

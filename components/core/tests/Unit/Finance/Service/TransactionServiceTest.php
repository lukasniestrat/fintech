<?php
declare(strict_types = 1);
namespace App\tests\Unit\Finance\Service;

use App\Entity\Finance\Category;
use App\Model\Common\RequestMetaData;
use App\Repository\Finance\TransactionRepository;
use App\Service\Finance\CategoryService;
use App\Service\Finance\TransactionService;
use App\Tests\Factory\Finance\BankAccountFactoryTrait;
use App\Tests\Factory\Finance\CategoryFactoryTrait;
use App\Tests\Factory\Finance\TransactionFactoryTrait;
use App\Tests\Utils\ReflectionFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class TransactionServiceTest extends TestCase
{
    use BankAccountFactoryTrait,
        TransactionFactoryTrait,
        CategoryFactoryTrait;

    private ?TransactionService $service = null;

    private CategoryService | MockObject $categoryService;

    private TransactionRepository | MockObject $transactionRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->categoryService = $this->getMockBuilder(CategoryService::class)->disableOriginalConstructor()->getMock();
        $this->transactionRepository = $this->getMockBuilder(TransactionRepository::class)->disableOriginalConstructor()->getMock();
    }

    public function test_it_stores_transaction(): void
    {
        $bankAccount = $this->getBankAccountMock();
        $transaction = $this->getTransactionMock($bankAccount);

        $this->transactionRepository->expects(self::once())->method('storeTransaction')->with($transaction);

        $this->getService()->storeTransaction($transaction);
    }

    public function test_it_removes_transaction(): void
    {
        $bankAccount = $this->getBankAccountMock();
        $transaction = $this->getTransactionMock($bankAccount);

        $this->transactionRepository->expects(self::once())->method('removeTransaction')->with($transaction);

        $this->getService()->removeTransaction($transaction);
    }

    public function test_it_finds_transaction_by_subject(): void
    {
        $this->transactionRepository->expects(self::once())->method('findTransactionsBySubject')->with('EWE GmbH & Co. KG');

        $this->getService()->findTransactionsBySubject('EWE GmbH & Co. KG');
    }

    public function test_it_gets_transaction_by_id(): void
    {
        $this->transactionRepository->expects(self::once())->method('getTransactionById')->with(1);

        $this->getService()->getTransactionById(1);
    }

    public function test_it_gets_transactions(): void
    {
        $this->transactionRepository->expects(self::once())->method('getTransactions')->with(new RequestMetaData());

        $this->getService()->getTransactions(new RequestMetaData());
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

        $mergedTransaction = $this->getService()->mergeTransactions($transaction, $updatedTransaction);

        self::assertEquals($updatedTransaction->getName(), $mergedTransaction->getName());
        self::assertEquals($updatedTransaction->getSubject(), $mergedTransaction->getSubject());
        self::assertEquals($updatedTransaction->getBankAccount()->getName(), $mergedTransaction->getBankAccount()->getName());
        self::assertEquals($updatedTransaction->getCategory()->getName(), $mergedTransaction->getCategory()->getName());
    }

    public function test_it_finds_category_for_transaction(): void
    {
        $bankAccount = $this->getBankAccountMock();
        $transaction = $this->getTransactionMock($bankAccount);

        $this->categoryService->expects(self::once())->method('getCategoriesForTransactionImport');

        $category = ReflectionFactory::callPrivateMethod($this->getService(), 'getCategoryForTransaction', $transaction);

        self::assertInstanceOf(Category::class, $category);
    }

    public function test_it_tests_if_transaction_exists(): void
    {
        $bankAccount = $this->getBankAccountMock();
        $transaction = $this->getTransactionMock($bankAccount);

        $this->transactionRepository->expects(self::once())->method('findTransactionsBySubject')->with('EWE GmbH & Co. KG');

        ReflectionFactory::callPrivateMethod($this->getService(), 'checkIfTransactionExists', $transaction);
    }

    private function getService(
        CategoryService | MockObject $categoryService = null,
        TransactionService | MockObject $transactionService = null,
    ): TransactionService {
        return new TransactionService(
            $categoryService ?? $this->categoryService,
            $transactionService ?? $this->transactionRepository,
        );
    }
}

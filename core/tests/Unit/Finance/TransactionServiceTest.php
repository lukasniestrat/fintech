<?php
declare(strict_types = 1);
namespace App\Tests\Unit\Finance;

use App\Entity\Finance\Category;
use App\Service\Finance\TransactionService;
use App\Tests\Factory\Finance\BankAccountFactoryTrait;
use App\Tests\Factory\Finance\TransactionFactoryTrait;
use App\Tests\Mocks\Finance\Repositories\TransactionRepositoryMock;
use App\Tests\Mocks\Finance\Services\CategoryServiceMock;
use App\Tests\Utils\ReflectionFactory;
use PHPUnit\Framework\TestCase;

class TransactionServiceTest extends TestCase
{
    use BankAccountFactoryTrait,
        TransactionFactoryTrait;

    private ?TransactionService $service = null;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new TransactionService(
            new CategoryServiceMock(),
            new TransactionRepositoryMock(),
        );
    }

    public function test_it_finds_transaction_by_subject(): void
    {
        $this->service->findTransactionsBySubject('test');

        self::assertEquals(1, TransactionRepositoryMock::$countFindTransactionBySubject);
    }

    public function test_it_finds_category_for_transaction(): void
    {
        $transaction = $this->getTransactionMock('EWE Stromrechnung', -9.99, 'EWE GmbH', $this->getBankAccountMock('Mein Bankkonto'));

        $category = ReflectionFactory::callPrivateMethod($this->service, 'getCategoryForTransaction', $transaction);
        self::assertEquals(1, CategoryServiceMock::$countGetAllCategories);
        self::assertInstanceOf(Category::class, $category);
    }
}

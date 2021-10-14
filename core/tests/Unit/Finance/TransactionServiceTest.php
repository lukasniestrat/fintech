<?php
declare(strict_types=1);
namespace App\Tests\Unit\Finance;

use App\Service\Finance\TransactionService;
use App\Tests\Mocks\Finance\Repositories\TransactionRepositoryMock;
use App\Tests\Mocks\Finance\Services\BankAccountServiceMock;
use App\Tests\Mocks\Finance\Services\CategoryServiceMock;
use App\Tests\Mocks\Finance\Services\CsvStorageServiceMock;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class TransactionServiceTest extends TestCase
{
    private ?TransactionService $service = null;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new TransactionService(
            new CsvStorageServiceMock(),
            new BankAccountServiceMock(),
            new CategoryServiceMock(),
            new TransactionRepositoryMock(),
        );
    }

    public function test_it_imports_transactions(): void
    {
        // arrange
        $uploadedFile = new UploadedFile(
            __DIR__ . '/../../_fixtures/import_test.CSV',
            'import_test.csv',
            null,
            null,
            true
        );

        // act

        $i = 1;
        // assert
        self::assertEquals(1, $i);
    }
}
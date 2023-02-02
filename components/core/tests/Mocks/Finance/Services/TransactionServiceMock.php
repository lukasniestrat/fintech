<?php
declare(strict_types = 1);
namespace App\Tests\Mocks\Finance\Services;

use App\Entity\Finance\BankAccount;
use App\Entity\Finance\Transaction;
use App\Model\Common\ModelList;
use App\Model\Common\RequestMetaData;
use App\Service\Finance\TransactionService;
use App\Tests\Utils\ReflectionFactory;

class TransactionServiceMock extends TransactionService
{
    public static ?Transaction $transaction = null;

    public static array $transactionsList = [];

    public static int $countImportTransactions = 0;

    public static int $countStoreTransaction = 0;

    public static int $countRemoveTransaction = 0;

    public static int $countFindTransactionBySubject = 0;

    public static int $countGetTransactionById = 0;

    public static int $countGetTransactions = 0;

    public static int $countMergeTransactions = 0;

    /** @noinspection PhpMissingParentConstructorInspection */
    public function __construct()
    {
        self::$transaction = null;
        self::$transactionsList = [];
        self::$countImportTransactions = 0;
        self::$countStoreTransaction = 0;
        self::$countRemoveTransaction = 0;
        self::$countFindTransactionBySubject = 0;
        self::$countGetTransactionById = 0;
        self::$countGetTransactions = 0;
        self::$countMergeTransactions = 0;
    }

    public function importTransactions(string $csvFilePath, BankAccount $bankAccount): ModelList
    {
        self::$countImportTransactions++;

        return new ModelList(self::$transactionsList, new RequestMetaData());
    }

    public function storeTransaction(Transaction $transaction): Transaction
    {
        self::$countStoreTransaction++;

        return self::$transaction ?? ReflectionFactory::createInstanceOfClass(Transaction::class);
    }

    public function removeTransaction(Transaction $transaction): void
    {
        self::$countRemoveTransaction++;
    }

    public function findTransactionsBySubject(string $subject): ?array
    {
        self::$countFindTransactionBySubject++;

        return self::$transactionsList;
    }

    public function getTransactionById(int $id): Transaction
    {
        self::$countGetTransactionById++;

        return self::$transaction ?? ReflectionFactory::createInstanceOfClass(Transaction::class);
    }

    public function getTransactions(RequestMetaData $requestMetaData): ModelList
    {
        self::$countGetTransactions++;

        if ($requestMetaData->getOffset() > count(self::$transactionsList)) {
            return new ModelList([], $requestMetaData);
        }

        return new ModelList(self::$transactionsList, $requestMetaData);
    }

    public function mergeTransactions(Transaction $existingTransaction, Transaction $updateTransaction): Transaction
    {
        self::$countMergeTransactions++;

        return self::$transaction ?? ReflectionFactory::createInstanceOfClass(Transaction::class);
    }
}

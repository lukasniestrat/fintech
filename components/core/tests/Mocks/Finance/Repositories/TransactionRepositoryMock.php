<?php
declare(strict_types = 1);
namespace App\Tests\Mocks\Finance\Repositories;

use App\Entity\Finance\Transaction;
use App\Model\Common\ModelList;
use App\Model\Common\RequestMetaData;
use App\Repository\Finance\TransactionRepository;
use App\Tests\Utils\ReflectionFactory;

class TransactionRepositoryMock extends TransactionRepository
{
    public static ?Transaction $transaction = null;

    public static array $transactionsList = [];

    public static int $countStoreTransaction = 0;

    public static int $countRemoveTransaction = 0;

    public static int $countFindTransactionBySubject = 0;

    public static int $countGetTransactionById = 0;

    public static int $countFindTransactionById = 0;

    public static int $countGetTransactions = 0;

    /** @noinspection PhpMissingParentConstructorInspection */
    public function __construct()
    {
        self::$transaction = null;
        self::$transactionsList = [];
        self::$countStoreTransaction = 0;
        self::$countRemoveTransaction = 0;
        self::$countFindTransactionBySubject = 0;
        self::$countGetTransactionById = 0;
        self::$countFindTransactionById = 0;
        self::$countGetTransactions = 0;
    }

    public function storeTransaction(Transaction $transaction): Transaction
    {
        self::$countStoreTransaction++;

        $transaction = self::$transaction;
        if (null === $transaction) {
            $transaction = ReflectionFactory::createInstanceOfClass(Transaction::class);
        }

        return $transaction;
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

    public function findTransactionById(int $id): ?Transaction
    {
        self::$countFindTransactionById++;

        return self::$transaction;
    }

    public function getTransactions(RequestMetaData $requestMetaData): ModelList
    {
        self::$countGetTransactions++;

        if ($requestMetaData->getOffset() > count(self::$transactionsList)) {
            return new ModelList([], $requestMetaData);
        }

        return new ModelList(self::$transactionsList, $requestMetaData);
    }
}

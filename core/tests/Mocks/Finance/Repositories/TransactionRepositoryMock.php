<?php
declare(strict_types = 1);
namespace App\Tests\Mocks\Finance\Repositories;

use App\Entity\Finance\Transaction;
use App\Repository\Finance\TransactionRepository;

class TransactionRepositoryMock extends TransactionRepository
{
    public static ?Transaction $transaction = null;

    public static array $transactions = [];

    public static int $countStoreTransaction = 0;

    public static int $countFindTransactionBySubject = 0;

    /** @noinspection PhpMissingParentConstructorInspection */
    public function __construct()
    {
        self::$transaction = null;
        self::$transactions = [];
        self::$countStoreTransaction = 0;
        self::$countFindTransactionBySubject = 0;
    }

    public function storeTransaction(Transaction $transaction): Transaction
    {
        self::$countStoreTransaction++;

        return self::$transaction;
    }

    public function findTransactionsBySubject(string $subject): ?array
    {
        self::$countFindTransactionBySubject++;

        return self::$transactions;
    }
}

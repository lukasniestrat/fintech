<?php
declare(strict_types = 1);
namespace App\Tests\Mocks\Finance\Repositories;

use App\Entity\Finance\BankAccount;
use App\Repository\Finance\BankAccountRepository;

class BankAccountRepositoryMock extends BankAccountRepository
{
    public static ?BankAccount $bankAccount = null;

    public static int $countFindBankAccountById = 0;

    public static int $countGetBankAccountById = 0;

    /** @noinspection PhpMissingParentConstructorInspection */
    public function __construct()
    {
        self::$bankAccount = null;
        self::$countFindBankAccountById = 0;
        self::$countGetBankAccountById = 0;
    }

    public function getBankAccountById(int $id): BankAccount
    {
        self::$countGetBankAccountById++;

        return self::$bankAccount;
    }

    public function findBankAccountById(int $id): ?BankAccount
    {
        self::$countFindBankAccountById++;

        return self::$bankAccount;
    }
}

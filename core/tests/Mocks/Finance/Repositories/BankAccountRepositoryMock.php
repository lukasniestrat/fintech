<?php
declare(strict_types=1);
namespace App\Tests\Mocks\Finance\Repositories;

use App\Repository\Finance\BankAccountRepository;
use App\Entity\Finance\BankAccount;

class BankAccountRepositoryMock extends BankAccountRepository
{
    public static ?BankAccount $bankAccount = null;

    public static int $countFindBankAccountById = 0;

    public function __construct()
    {
        self::$bankAccount = null;
        self::$countFindBankAccountById = 0;
    }

    public function findBankAccountById(int $bankAccountId): ?BankAccount
    {
        self::$bankAccountId++;

        return self::$bankAccount;
    }
}
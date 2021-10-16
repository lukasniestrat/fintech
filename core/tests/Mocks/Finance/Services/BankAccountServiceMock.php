<?php
declare(strict_types = 1);
namespace App\Tests\Mocks\Finance\Services;

use App\Entity\Finance\BankAccount;
use App\Service\Finance\BankAccountService;

class BankAccountServiceMock extends BankAccountService
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
        self::$countFindBankAccountById++;

        return self::$bankAccount;
    }
}

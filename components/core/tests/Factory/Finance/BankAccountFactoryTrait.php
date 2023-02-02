<?php
declare(strict_types = 1);
namespace App\Tests\Factory\Finance;

use App\Entity\Finance\BankAccount;
use App\Tests\Utils\ReflectionFactory;

trait BankAccountFactoryTrait
{
    private function getBankAccountMock(string $name = 'Girokonto', bool $isSavingAccount = false): BankAccount
    {
        $bankAccount = ReflectionFactory::createInstanceOfClass(BankAccount::class);
        ReflectionFactory::setPrivateProperty($bankAccount, 'name', $name);
        ReflectionFactory::setPrivateProperty($bankAccount, 'iban', 'DE1234567890');
        ReflectionFactory::setPrivateProperty($bankAccount, 'isSavingAccount', $isSavingAccount);

        return $bankAccount;
    }
}

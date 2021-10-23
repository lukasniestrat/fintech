<?php
declare(strict_types = 1);
namespace App\Tests\Factory\Finance;

use App\Entity\Finance\BankAccount;
use App\Entity\Finance\Transaction;
use App\Tests\Utils\ReflectionFactory;
use DateTime;

trait TransactionFactoryTrait
{
    private function getTransactionMock(
        string $name,
        float $amount,
        string $subject,
        BankAccount $bankAccount,
    ): Transaction {
        $transaction = ReflectionFactory::createInstanceOfClass(Transaction::class);
        ReflectionFactory::setPrivateProperty($transaction, 'name', $name);
        ReflectionFactory::setPrivateProperty($transaction, 'amount', $amount);
        ReflectionFactory::setPrivateProperty($transaction, 'subject', $subject);
        ReflectionFactory::setPrivateProperty($transaction, 'bankAccount', $bankAccount);
        ReflectionFactory::setPrivateProperty($transaction, 'iban', 'DE1234567890');
        ReflectionFactory::setPrivateProperty($transaction, 'bookingDate', new DateTime());

        return $transaction;
    }
}

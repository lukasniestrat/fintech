<?php
declare(strict_types=1);
namespace App\Factory\Finance;

use DateTime;
use App\Entity\Finance\BankAccount;
use App\Entity\Finance\Transaction;

class TransactionFactory
{
    public static function createTransaction(
        string $name,
        string $subject,
        BankAccount $bankAccount,
        float $amount,
        DateTime $bookingDate,
        string $iban
    ): Transaction
    {
        $transaction = new Transaction($name, $subject, $bankAccount);
        $transaction
            ->setAmount($amount)
            ->setBookingDate($bookingDate)
            ->setIban($iban);

        return $transaction;
    }

    public static function createImmutableTransaction(
        string $name,
        string $subject,
        BankAccount $bankAccount,
    ): Transaction
    {
        return new Transaction($name, $subject, $bankAccount);
    }
}
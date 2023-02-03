<?php
declare(strict_types = 1);
namespace Unit\Finance\Factory;

use App\Factory\Finance\TransactionFactory;
use App\Tests\Factory\Finance\BankAccountFactoryTrait;
use DateTime;
use PHPUnit\Framework\TestCase;

class TransactionFactoryTest extends TestCase
{
    use BankAccountFactoryTrait;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_it_creates_transaction(): void
    {
        $bankAccount = $this->getBankAccountMock();
        $bookingDate = new DateTime();
        $transaction = TransactionFactory::createTransaction(
            'PayPal Inc.',
            'Test',
            $bankAccount,
            100.00,
            $bookingDate,
            'DE123456780',
        );

        self::assertEquals('PayPal Inc.', $transaction->getName());
        self::assertEquals('Test', $transaction->getSubject());
        self::assertEquals('Girokonto', $transaction->getBankAccount()->getName());
        self::assertSame(100.00, $transaction->getAmount());
        self::assertEquals($bookingDate, $transaction->getBookingDate());
        self::assertEquals('DE123456780', $transaction->getIban());
    }

    public function test_it_creates_immutable_transaction(): void
    {
        $bankAccount = $this->getBankAccountMock();
        $transaction = TransactionFactory::createImmutableTransaction(
            'PayPal Inc.',
            'Test',
            $bankAccount,
        );

        self::assertEquals('PayPal Inc.', $transaction->getName());
        self::assertEquals('Test', $transaction->getSubject());
        self::assertEquals('Girokonto', $transaction->getBankAccount()->getName());
    }
}

<?php
declare(strict_types = 1);
namespace App\Tests\Integration\Finance;

use App\Exception\Finance\BankAccountException;
use App\Repository\Finance\BankAccountRepository;
use App\Tests\Factory\Common\AssertExceptionTrait;
use App\Tests\Integration\Common\AbstractFinRepositoryTest;

class BankAccountRepositoryTest extends AbstractFinRepositoryTest
{
    use AssertExceptionTrait;

    public function test_it_gets_bank_account_by_id(): void
    {
        $bankAccount = $this->getRepository()->getBankAccountById(1);

        self::assertEquals(1, $bankAccount->getId());
        self::assertEquals('Girokonto', $bankAccount->getName());

        $this->assertException(function (): void {
            $this->getRepository()->getBankAccountById(9999);
        }, BankAccountException::class, BankAccountException::NOT_FOUND, ['reason' => 'no bank account with id 9999 found']);
    }

    public function test_it_finds_bank_account_by_id(): void
    {
        $bankAccount = $this->getRepository()->findBankAccountById(1);

        self::assertEquals(1, $bankAccount->getId());
        self::assertEquals('Girokonto', $bankAccount->getName());

        $bankAccount = $this->getRepository()->findBankAccountById(9999);

        self::assertNull($bankAccount);
    }

    protected function getRepository(): BankAccountRepository
    {
        if (null === $this->repository) {
            $this->repository = self::getContainer()->get(BankAccountRepository::class);
        }

        return $this->repository;
    }
}

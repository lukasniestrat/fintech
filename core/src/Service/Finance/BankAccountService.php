<?php
declare(strict_types=1);
namespace App\Service\Finance;

use App\Entity\Finance\BankAccount;
use App\Repository\Finance\BankAccountRepository;

class BankAccountService
{
    public function __construct(
        private BankAccountRepository $bankAccountRepository
    ) {
    }

    public function findBankAccountById(int $bankAccountId): ?BankAccount
    {
        return $this->bankAccountRepository->findBankAccountById($bankAccountId);
    }
}
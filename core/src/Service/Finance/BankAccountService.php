<?php
declare(strict_types = 1);
namespace App\Service\Finance;

use App\Entity\Finance\BankAccount;
use App\Exception\Finance\BankAccountException;
use App\Repository\Finance\BankAccountRepository;

class BankAccountService
{
    public function __construct(
        private BankAccountRepository $bankAccountRepository
    ) {
    }

    /**
     * @throws BankAccountException
     */
    public function getBankAccountById(int $id): BankAccount
    {
        return $this->bankAccountRepository->getBankAccountById($id);
    }

    public function findBankAccountById(int $id): ?BankAccount
    {
        return $this->bankAccountRepository->findBankAccountById($id);
    }
}

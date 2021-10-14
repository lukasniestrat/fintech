<?php
declare(strict_types=1);
namespace App\Service\Finance;

use App\Entity\Finance\Category;
use App\Entity\Finance\Transaction;
use App\Exception\Finance\BankAccountException;
use App\Model\Common\FinConstants;
use App\Repository\Finance\TransactionRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Exception\Common\UploadFileException;
use DateTime;

class TransactionService
{
    public function __construct(
        private CsvStorageService $csvStorageService,
        private BankAccountService $bankAccountService,
        private CategoryService $categoryService,
        private TransactionRepository $transactionRepository,
    ) {
    }

    /**
     * @throws UploadFileException
     * @throws BankAccountException
     */
    public function importTransactions(UploadedFile $uploadedFile, int $bankAccountId): array
    {
        $fileLink = $this->csvStorageService->storeFile($uploadedFile, 'csv/transactions');
        $bankAccount = $this->bankAccountService->findBankAccountById($bankAccountId);
        if (null === $bankAccount) {
            throw new BankAccountException(BankAccountException::NO_BANK_ACCOUNT_FOUND, ['reason' => 'Bank account not found']);
        }

        $file = fopen($fileLink, 'r');
        $uncategorizedTransactions = [];

        fgetcsv($file, 10000, ";");
        while (($transactionCsv = fgetcsv($file, 10000, ";")) !== false) {
            $transaction = new Transaction();
            $transaction
                ->setBankAccount($bankAccount)
                ->setName($transactionCsv[11])
                ->setSubject($transactionCsv[4])
                ->setAmount(floatval(str_replace(',', '.',$transactionCsv[14])))
                ->setBookingDate(new DateTime($transactionCsv[1]))
                ->setIban($transactionCsv[12]);

            if (false === $this->checkIfTransactionExists($transaction)) {
                $category = $this->getCategoryForTransaction($transaction);
                $transaction->setCategory($category);

                // can't sort any category 1 is default
                if (1 === $category->getId()) {
                    $uncategorizedTransactions[] = $transaction;
                }

                $this->transactionRepository->storeTransaction($transaction);
            }
        }

        return $uncategorizedTransactions;
    }

    public function findTransactionsBySubject(string $subject): ?array
    {
        return $this->transactionRepository->findTransactionsBySubject($subject);
    }

    /**
     * Search for matching category
     */
    private function getCategoryForTransaction(Transaction $transaction): Category
    {
        $categories = $this->categoryService->getAllCategories();
        foreach ($categories as $category) {
            if (null !== $category->getTags()) {
                $categoryKeys = explode(',', str_replace(' ', '', $category->getTags()));

                foreach ($categoryKeys as $categoryKey) {
                    if (str_contains($transaction->getName(), $categoryKey) || str_contains($transaction->getSubject(), $categoryKey)) {
                        return $category;
                    }
                }
            }
        }

        return $this->categoryService->findOneCategoryById(1);
    }

    /**
     * Check if a transaction is already imported
     */
    private function checkIfTransactionExists(Transaction $transaction): bool
    {
        $existingTransactions = $this->findTransactionsBySubject($transaction->getSubject());

        if (null !== $existingTransactions) {
            foreach ($existingTransactions as $existingTransaction) {
                $existingTransactionsBookingDate = $existingTransaction->getBookingDate()->format(FinConstants::DATE_FORMAT_DATE_ONLY);
                $transactionsBookingDate = $transaction->getBookingDate()->format(FinConstants::DATE_FORMAT_DATE_ONLY);

                if ($existingTransactionsBookingDate === $transactionsBookingDate) {
                    return true;
                }
            }
        }

        return false;
    }
}
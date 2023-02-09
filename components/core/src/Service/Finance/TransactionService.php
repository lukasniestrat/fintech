<?php
declare(strict_types = 1);
namespace App\Service\Finance;

use App\Entity\Finance\BankAccount;
use App\Entity\Finance\Category;
use App\Entity\Finance\Transaction;
use App\Exception\Finance\CategoryException;
use App\Exception\Finance\TransactionException;
use App\Factory\Finance\TransactionFactory;
use App\Model\Common\FinConstants;
use App\Model\Common\ModelList;
use App\Model\Common\RequestMetaData;
use App\Repository\Finance\TransactionRepository;
use DateTime;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class TransactionService
{
    public function __construct(
        private readonly CategoryService $categoryService,
        private readonly TransactionRepository $transactionRepository,
    ) {
    }

    /**
     * @throws CategoryException
     */
    public function importTransactions(string $csvFilePath, BankAccount $bankAccount): ModelList
    {
        $file = fopen($csvFilePath, 'rb');
        $list = [];

        if ($file) {
            fgetcsv($file, 10000, Transaction::CSV_SEPERATOR);
            while (($transactionCsv = fgetcsv($file, 10000, Transaction::CSV_SEPERATOR)) !== false) {
                $name = preg_replace('!\s+!', ' ', $transactionCsv[11]);
                $subject = preg_replace('!\s+!', ' ', $transactionCsv[4]);
                $dateArray = explode('.', $transactionCsv[1]);

                $transaction = TransactionFactory::createTransaction(
                    $name,
                    $subject,
                    $bankAccount,
                    (float) str_replace(',', '.', $transactionCsv[14]),
                    new DateTime($dateArray[2] . '-' . $dateArray[1] . '-' . $dateArray[0]),
                    $transactionCsv[12]
                );

                if (false === $this->checkIfTransactionExists($transaction)) {
                    $category = $this->getCategoryForTransaction($transaction);
                    $transaction->setCategory($category);

                    // can't sort any category 1 is default
                    if (1 === $category->getId()) {
                        $list[] = $transaction;
                    }

                    $this->storeTransaction($transaction);
                }
            }

            return new ModelList($list, new RequestMetaData(count($list), 0, 0, 'id', 'asc'));
        }

        throw new FileException('File not found');
    }

    public function storeTransaction(Transaction $transaction): Transaction
    {
        return $this->transactionRepository->storeTransaction($transaction);
    }

    public function removeTransaction(Transaction $transaction): void
    {
        $this->transactionRepository->removeTransaction($transaction);
    }

    public function findTransactionsBySubject(string $subject): ?array
    {
        return $this->transactionRepository->findTransactionsBySubject($subject);
    }

    /**
     * @throws TransactionException
     */
    public function getTransactionById(int $id): Transaction
    {
        return $this->transactionRepository->getTransactionById($id);
    }

    public function getTransactions(RequestMetaData $requestMetaData): ModelList
    {
        return $this->transactionRepository->getTransactions($requestMetaData);
    }

    public function mergeTransactions(Transaction $existingTransaction, Transaction $updateTransaction): Transaction
    {
        $existingTransaction
            ->setName($updateTransaction->getName())
            ->setSubject($updateTransaction->getSubject())
            ->setBankAccount($updateTransaction->getBankAccount())
            ->setCategory($updateTransaction->getCategory());

        return $existingTransaction;
    }

    /**
     * Search for a matching category
     * @throws CategoryException
     * @default id: 1, name: Sonstiges
     */
    private function getCategoryForTransaction(Transaction $transaction): Category
    {
        $categories = $this->categoryService->getCategoriesForTransactionImport();
        foreach ($categories as $category) {
            if (null !== $category->getTags()) {
                $categoryKeys = $category->getTagsAsArray();

                foreach ($categoryKeys as $categoryKey) {
                    if (str_contains($transaction->getName(), $categoryKey) || str_contains($transaction->getSubject(), $categoryKey)) {
                        return $category;
                    }
                }
            }
        }

        return $this->categoryService->getCategoryById(1);
    }

    /**
     * Check if a transaction is already imported
     */
    private function checkIfTransactionExists(Transaction $transaction): bool
    {
        $existingTransactions = $this->findTransactionsBySubject($transaction->getSubject());

        if (null !== $existingTransactions) {
            foreach ($existingTransactions as $existingTransaction) {
                $existingTransactionsBookingDate = $existingTransaction->getBookingDate()?->format(FinConstants::DATE_FORMAT_DATE_ONLY);
                $transactionsBookingDate = $transaction->getBookingDate()?->format(FinConstants::DATE_FORMAT_DATE_ONLY);

                if ($existingTransactionsBookingDate === $transactionsBookingDate) {
                    return true;
                }
            }
        }

        return false;
    }
}

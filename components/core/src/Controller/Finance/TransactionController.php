<?php
declare(strict_types = 1);
namespace App\Controller\Finance;

use App\Controller\Common\AbstractFinController;
use App\Exception\Common\ApiRequestException;
use App\Exception\Common\UploadFileException;
use App\Exception\Finance\BankAccountException;
use App\Exception\Finance\CategoryException;
use App\Exception\Finance\TransactionException;
use App\Serializer\Finance\TransactionSerializer;
use App\Service\Finance\BankAccountService;
use App\Service\Finance\CsvStorageService;
use App\Service\Finance\TransactionService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TransactionController extends AbstractFinController
{
    public function __construct(
        private readonly BankAccountService $bankAccountService,
        private readonly TransactionService $transactionService,
        private readonly TransactionSerializer $transactionSerializer,
        private readonly CsvStorageService $csvStorageService,
    ) {
    }

    /**
     * @throws BankAccountException
     * @throws ApiRequestException
     * @throws CategoryException
     */
    #[Route('/transactions/import', methods: ['POST', 'HEAD'])]
    public function importTransactions(Request $request): JsonResponse
    {
        $data = $this->getData($request);
        $bankAccount = $this->bankAccountService->getBankAccountById((int) $data['bankAccountId']);

        $uncategorizedTransactions = [];
        $uncategorizedTransactionsList = $this->transactionService->importTransactions($data['csvFilePath'], $bankAccount);

        foreach ($uncategorizedTransactionsList->getList() as $uncategorizedTransaction) {
            $uncategorizedTransactions[] = $this->transactionSerializer->serialize($uncategorizedTransaction);
        }

        return new JsonResponse(
            $uncategorizedTransactions,
            Response::HTTP_OK,
            $this->getMetaHeaderData($uncategorizedTransactionsList->getMetaData())
        );
    }

    /**
     * @throws TransactionException
     */
    #[Route('/transactions/{id}', methods: ['DELETE', 'HEAD'])]
    public function removeTransaction(int $id, Request $request): JsonResponse
    {
        $transaction = $this->transactionService->getTransactionById($id);
        $this->transactionService->removeTransaction($transaction);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @throws TransactionException
     */
    #[Route('/transactions/{id}', methods: ['PUT', 'HEAD'])]
    public function updateTransaction(int $id, Request $request): JsonResponse
    {
        $data = $this->getData($request);
        $transaction = $this->transactionService->getTransactionById($id);
        $delta = $this->transactionSerializer->deserialize($data);

        $transaction = $this->transactionService->mergeTransactions($transaction, $delta);
        $transaction = $this->transactionService->storeTransaction($transaction);

        return new JsonResponse(
            $this->transactionSerializer->serialize($transaction),
            Response::HTTP_OK
        );
    }

    #[Route('/transactions', methods: ['GET', 'HEAD'])]
    public function getTransactions(Request $request): JsonResponse
    {
        $metaData = $this->getRequestMetaData($request);
        $transactionList = $this->transactionService->getTransactions($metaData);
        $transactions = [];

        foreach ($transactionList->getList() as $transaction) {
            $transactions[] = $this->transactionSerializer->serialize($transaction);
        }

        return new JsonResponse(
            $transactions,
            Response::HTTP_OK,
            $this->getMetaHeaderData($transactionList->getMetaData())
        );
    }

    /**
     * @throws TransactionException
     */
    #[Route('/transactions/{id}', methods: ['GET', 'HEAD'])]
    public function getTransaction(int $id): JsonResponse
    {
        $transaction = $this->transactionService->getTransactionById($id);

        return new JsonResponse(
            $this->transactionSerializer->serialize($transaction),
            Response::HTTP_OK
        );
    }

    /**
     * @throws UploadFileException
     */
    #[Route('/transactions/uploadcsv', methods: ['POST', 'HEAD'])]
    public function uploadTransactionCsvFile(Request $request): JsonResponse
    {
        $filePath = '';
        $uploadedFile = null;
        if ($request->files->has('transactionsCsv')) {
            $uploadedFile = $request->files->get('transactionsCsv');
            $filePath = $this->csvStorageService->storeFile($uploadedFile);
        }
        if (null === $uploadedFile) {
            throw new UploadFileException(UploadFileException::NO_FILE, ['reason' => 'no file set']);
        }

        return new JsonResponse(
            ['filePath' => $filePath],
            Response::HTTP_OK
        );
    }
}

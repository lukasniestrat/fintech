<?php
declare(strict_types = 1);
namespace App\Controller\Finance;

use App\Controller\Common\AbstractFinController;
use App\Exception\Common\ApiRequestException;
use App\Exception\Common\UploadFileException;
use App\Exception\Finance\BankAccountException;
use App\Exception\Finance\CategoryException;
use App\Service\Finance\BankAccountService;
use App\Service\Finance\CsvStorageService;
use App\Service\Finance\Serializer\TransactionSerializer;
use App\Service\Finance\TransactionService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TransactionController extends AbstractFinController
{
    public function __construct(
        private BankAccountService $bankAccountService,
        private TransactionService $transactionService,
        private TransactionSerializer $transactionSerializer,
        private CsvStorageService $csvStorageService,
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

        return new JsonResponse(['filePath' => $filePath], Response::HTTP_OK);
    }
}

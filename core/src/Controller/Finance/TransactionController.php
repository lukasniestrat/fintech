<?php
declare(strict_types=1);
namespace App\Controller\Finance;

use App\Controller\Common\AbstractFinController;
use App\Exception\Common\UploadFileException;
use App\Service\Finance\Serializer\TransactionSerializer;
use App\Service\Finance\TransactionService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Exception\Finance\BankAccountException;

class TransactionController extends AbstractFinController
{
    public function __construct(
        private TransactionService $transactionService,
        private TransactionSerializer $transactionSerializer,
    ) {
    }

    /**
     * @throws UploadFileException
     * @throws BankAccountException
     */
    #[Route('/transactions/import', methods: ['POST', 'HEAD'])]
    public function importTransactions(Request $request): JsonResponse
    {
        $uploadedFile = null;
        if ($request->files->has('transactions_csv')) {
            $uploadedFile = $request->files->get('transactions_csv');
        }
        if (null === $uploadedFile) {
            throw new UploadFileException(UploadFileException::NO_FILE, ['reason' => 'no file set']);
        }

        $uncategorizedTransactions = [];
        $uncategorizedTransactionsList = $this->transactionService->importTransactions($uploadedFile, (int) $request->get('bankaccount_id'));

        foreach ($uncategorizedTransactionsList as $uncategorizedTransaction) {
            $uncategorizedTransactions[] = $this->transactionSerializer->serialize($uncategorizedTransaction);
        }

        return new JsonResponse(['uncategorizedTransactions' => $uncategorizedTransactions], Response::HTTP_OK);
    }
}

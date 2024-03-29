<?php
declare(strict_types = 1);
namespace App\Serializer\Finance;

use App\Entity\Finance\Transaction;
use App\Exception\Finance\BankAccountException;
use App\Exception\Finance\CategoryException;
use App\Exception\Finance\TransactionException;
use App\Factory\Finance\TransactionFactory;
use App\Model\Common\Serializable;
use App\Service\Common\Serializer\AbstractFinSerializer;
use App\Service\Finance\BankAccountService;
use App\Service\Finance\CategoryService;

class TransactionSerializer extends AbstractFinSerializer
{
    public function __construct(
        private readonly BankAccountService $bankAccountService,
        private readonly CategoryService $categoryService,
    ) {
        static::$expectedExceptionClass = TransactionException::class;
        static::$expectedExceptionType = TransactionException::DESERIALIZATION_FAILED;
    }

    public function serialize(Serializable $transaction): array
    {
        return $transaction->toArray();
    }

    /**
     * @throws CategoryException
     * @throws BankAccountException
     */
    public function deserialize(array $serializedTransaction): Transaction
    {
        static::$expectedTemplate = [
            'name' => '',
            'subject' => '',
            'bankAccount' => ['id' => ''],
            'category' => ['id' => ''],
        ];

        $this->validateStructure($serializedTransaction);

        $bankAccount = $this->bankAccountService->getBankAccountById((int) $serializedTransaction['bankAccount']['id']);
        $category = $this->categoryService->getCategoryById((int) $serializedTransaction['category']['id']);

        $transaction = TransactionFactory::createImmutableTransaction(
            $serializedTransaction['name'],
            $serializedTransaction['subject'],
            $bankAccount,
        );
        $transaction->setCategory($category);

        return $transaction;
    }
}

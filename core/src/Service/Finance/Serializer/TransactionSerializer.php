<?php
declare(strict_types = 1);
namespace App\Service\Finance\Serializer;

use App\Entity\Finance\Transaction;

class TransactionSerializer
{
    public function serialize(Transaction $transaction): array
    {
        return $transaction->toArray();
    }
}

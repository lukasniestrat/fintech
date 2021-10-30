<?php
declare(strict_types = 1);
namespace App\Tests\Unit\Finance;

use App\Model\Common\FinConstants;
use App\Service\Finance\Serializer\TransactionSerializer;
use App\Tests\Factory\Finance\BankAccountFactoryTrait;
use App\Tests\Factory\Finance\CategoryFactoryTrait;
use App\Tests\Factory\Finance\TransactionFactoryTrait;
use App\Tests\Mocks\Finance\Services\BankAccountServiceMock;
use App\Tests\Mocks\Finance\Services\CategoryServiceMock;
use App\Tests\Utils\ReflectionFactory;
use DateTime;
use PHPUnit\Framework\TestCase;

class TransactionSerializerTest extends TestCase
{
    use TransactionFactoryTrait,
        BankAccountFactoryTrait,
        CategoryFactoryTrait;

    private ?TransactionSerializer $serializer = null;

    protected function setUp(): void
    {
        parent::setUp();
        $this->serializer = new TransactionSerializer(
            new BankAccountServiceMock(),
            new CategoryServiceMock(),
        );
    }

    public function test_it_deserializes(): void
    {
        $transactionData = [
            'name' => 'EWE Stromrechnung',
            'subject' => 'EWE GmbH & Co. KG',
            'amount' => -73.92,
            'bookingDate' => '05.10.2021',
            'iban' => 'DE88500700100175526303',
            'category' => [
                'id' => 1,
            ],
            'bankAccount' => [
                'id' => 1,
            ]
        ];

        $transaction = $this->serializer->deserialize($transactionData);

        self::assertNull($transaction->getId());
        self::assertEquals('EWE Stromrechnung', $transaction->getName());
    }

    public function test_it_serializes(): void
    {
        $now = new DateTime();
        $category = $this->getCategoryMock();
        $bankAccount = $this->getBankAccountMock();
        $transaction = $this->getTransactionMock($bankAccount);
        ReflectionFactory::setPrivateProperty($transaction, 'category', $category);
        ReflectionFactory::setPrivateProperty($transaction, 'bookingDate', $now);

        $data = $this->serializer->serialize($transaction);

        $expected = [
            'id' => null,
            'name' => 'EWE Stromrechnung',
            'subject' => 'EWE GmbH & Co. KG',
            'amount' => -19.99,
            'bookingDate' => $now->format(FinConstants::DATE_FORMAT_DATE_ONLY),
            'iban' => 'DE1234567890',
            'category' => [
                'id' => null,
                'name' => 'Sonstiges',
                'tags' => 'EWE, Telekom',
            ],
            'bankAccount' => [
                'id' => null,
                'name' => 'Girokonto',
                'iban' => 'DE1234567890',
                'isSavingAccount' => false,
            ]
        ];

        self::assertEquals($expected, $data);
    }
}

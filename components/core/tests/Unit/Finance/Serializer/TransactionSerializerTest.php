<?php
declare(strict_types = 1);
namespace Unit\Finance\Serializer;

use App\Model\Common\FinConstants;
use App\Serializer\Finance\TransactionSerializer;
use App\Service\Finance\BankAccountService;
use App\Service\Finance\CategoryService;
use App\Tests\Factory\Finance\BankAccountFactoryTrait;
use App\Tests\Factory\Finance\CategoryFactoryTrait;
use App\Tests\Factory\Finance\TransactionFactoryTrait;
use App\Tests\Utils\ReflectionFactory;
use DateTime;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class TransactionSerializerTest extends TestCase
{
    use TransactionFactoryTrait,
        BankAccountFactoryTrait,
        CategoryFactoryTrait;

    private ?TransactionSerializer $serializer = null;

    private BankAccountService | MockObject $bankAccountServiceMock;

    private CategoryService | MockObject $categoryServiceMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->bankAccountServiceMock = $this->getMockBuilder(BankAccountService::class)->disableOriginalConstructor()->getMock();
        $this->categoryServiceMock = $this->getMockBuilder(CategoryService::class)->disableOriginalConstructor()->getMock();
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

        $transaction = $this->getSerializer()->deserialize($transactionData);

        self::assertNull($transaction->getId());
        self::assertEquals('EWE Stromrechnung', $transaction->getName());
    }

    public function test_it_serializes(): void
    {
        $now = new DateTime();
        $category = $this->getCategoryMock('Sonstiges', 'EWE, Telekom');
        $bankAccount = $this->getBankAccountMock();
        $transaction = $this->getTransactionMock($bankAccount);
        ReflectionFactory::setPrivateProperty($transaction, 'category', $category);
        ReflectionFactory::setPrivateProperty($transaction, 'bookingDate', $now);

        $data = $this->getSerializer()->serialize($transaction);

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
                'tags' => [
                    'EWE',
                    'Telekom'
                ],
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

    private function getSerializer(
        BankAccountService | MockObject $bankAccountService = null,
        CategoryService | MockObject $categoryService = null,
    ): TransactionSerializer {
        return new TransactionSerializer(
            $bankAccountService ?? $this->bankAccountServiceMock,
            $categoryService ?? $this->categoryServiceMock,
        );
    }
}

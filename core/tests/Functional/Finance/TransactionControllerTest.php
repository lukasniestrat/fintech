<?php
declare(strict_types = 1);
namespace App\Tests\Functional\Finance;

use App\Tests\Functional\Common\AbstractFinApiTest;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;

class TransactionControllerTest extends AbstractFinApiTest
{
    public function test_it_uploads_transaction_csv(): void
    {
        // arrange
        $fixtureFilePath = __DIR__ . '/../../_fixtures/Finance/csv/import_test.CSV';
        if (false === file_exists($fixtureFilePath)) {
            self::fail('necessary fixtures missing');
        }

        $file = __DIR__ . '/../../_fixtures/Finance/csv/copied_import_test.CSV';
        copy($fixtureFilePath, $file);

        $uploadedFile = new UploadedFile($file, 'import_test.CSV');

        // act
        $response = $this->request(self::HTTP_POST, '/transactions/uploadcsv', [], [], ['transactionsCsv' => $uploadedFile]);

        // assert
        self::assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        self::assertJson($response->getContent());
        $data = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        self::assertArrayHasKey('filePath', $data);

        array_map('unlink', glob(__DIR__ . '/../../../public/uploads/*'));
    }

    public function test_it_imports_transactions(): void
    {
        $csvFilePath = __DIR__ . '/../../_fixtures/Finance/csv/import_test.CSV';
        $data = [
            'bankAccountId' => 1,
            'csvFilePath' => $csvFilePath,
        ];

        $response = $this->request(self::HTTP_POST, '/transactions/import', $data);
        $responseData = $this->getData($response->getContent());

        $expectedResponse = [
            [
                'name' => 'Hans Meyer',
                'subject' => 'Testverwendung',
                'amount' => -38.94,
                'bookingDate' => '14.10.2021',
                'iban' => 'DE86280200501429904400',
                'category' => [
                    'id' => 1,
                    'name' => 'Sonstiges',
                    'tags' => null
                ],
                'bankAccount' => [
                    'id' => 1,
                    'name' => 'Girkonto',
                    'iban' => 'DE***********5065',
                    'isSavingAccount' => false
                ]
            ]
        ];

        self::assertEquals(200, $response->getStatusCode());
        self::assertJson($response->getContent());
        unset($responseData[0]['id']);
        self::assertEquals($expectedResponse, $responseData);
    }

    public function test_it_skips_duplicate_transactions_in_imports(): void
    {
        $csvFilePath = __DIR__ . '/../../_fixtures/Finance/csv/import_test_duplicate_entries.CSV';
        $data = [
            'bankAccountId' => 1,
            'csvFilePath' => $csvFilePath,
        ];

        $response = $this->request(self::HTTP_POST, '/transactions/import', $data);
        $responseData = $this->getData($response->getContent());

        $expectedResponse = [
            [
                'name' => 'Hans Meyer',
                'subject' => 'Testverwendung',
                'amount' => -38.94,
                'bookingDate' => '14.10.2021',
                'iban' => 'DE86280200501429904400',
                'category' => [
                    'id' => 1,
                    'name' => 'Sonstiges',
                    'tags' => null
                ],
                'bankAccount' => [
                    'id' => 1,
                    'name' => 'Girkonto',
                    'iban' => 'DE***********5065',
                    'isSavingAccount' => false
                ]
            ]
        ];

        self::assertEquals(200, $response->getStatusCode());
        self::assertJson($response->getContent());
        unset($responseData[0]['id']);
        self::assertEquals($expectedResponse, $responseData);
    }
}

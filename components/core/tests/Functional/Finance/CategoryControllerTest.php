<?php
declare(strict_types = 1);
namespace App\Tests\Functional\Finance;

use App\Tests\Functional\Common\AbstractFinApiTest;

class CategoryControllerTest extends AbstractFinApiTest
{
    public function test_it_stores_category(): void
    {
        $data = [
            'name' => 'Test',
            'tags' => [
                'test',
                'test',
                'test'
            ]
        ];

        $response = $this->request(self::HTTP_POST, '/categories', $data);
        $responseData = $this->getData($response->getContent());

        $expectedResponse = [
            'name' => 'Test',
            'tags' => [
                'test',
                'test',
                'test'
            ]
        ];

        self::assertEquals(200, $response->getStatusCode());
        self::assertJson($response->getContent());
        unset($responseData['id']);
        self::assertEquals($expectedResponse, $responseData);
    }

    public function test_it_updates_category(): void
    {
        $updateData = [
            'name' => 'Test',
            'tags' => [
                'test',
                'test',
                'test',
            ],
        ];

        $expectedData = [
            'id' => 2,
            'name' => 'Test',
            'tags' => [
                'test',
                'test',
                'test'
            ],
        ];

        $response = $this->request(self::HTTP_PUT, '/categories/2', $updateData);

        self::assertEquals(200, $response->getStatusCode());
        self::assertJson($response->getContent());

        $data = $this->getData($response->getContent());
        self::assertEquals($expectedData, $data);
    }

    public function test_it_dont_update_immutable_categories(): void
    {
        $updateData = [
            'name' => 'Test',
            'tags' => [
                'test',
                'test',
                'test',
            ],
        ];

        $expectedData = [
            'success' => false,
            'code' => 'CategoryException-3',
            'detail' => 'Category is immutable',
        ];

        $response = $this->request(self::HTTP_PUT, '/categories/1', $updateData);

        self::assertEquals(403, $response->getStatusCode());
        self::assertJson($response->getContent());

        $data = $this->getData($response->getContent());
        self::assertEquals($expectedData, $data);
    }

    public function test_it_gets_categories(): void
    {
        $response = $this->request(self::HTTP_GET, '/categories');

        self::assertEquals(200, $response->getStatusCode());
        self::assertJson($response->getContent());
    }

    public function test_it_gets_category(): void
    {
        $expectedData = [
            'id' => 2,
            'name' => 'Wohnen',
            'tags' => [
                'EWE',
                'Telekom',
                'Miete'
            ],
        ];

        $response = $this->request(self::HTTP_GET, '/categories/2');

        self::assertEquals(200, $response->getStatusCode());
        self::assertJson($response->getContent());

        $data = $this->getData($response->getContent());
        self::assertEquals($expectedData, $data);
    }
}

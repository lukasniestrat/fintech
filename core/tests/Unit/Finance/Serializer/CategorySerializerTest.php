<?php
declare(strict_types = 1);
namespace Unit\Finance\Serializer;

use App\Service\Finance\Serializer\CategorySerializer;
use App\Tests\Factory\Finance\CategoryFactoryTrait;
use PHPUnit\Framework\TestCase;

class CategorySerializerTest extends TestCase
{
    use CategoryFactoryTrait;

    private ?CategorySerializer $serializer = null;

    protected function setUp(): void
    {
        parent::setUp();
        $this->serializer = new CategorySerializer();
    }

    public function test_it_deserializes(): void
    {
        $categoryData = [
            'name' => 'Test',
            'tags' => [
                'test',
                'test1',
                'test2',
            ],
        ];

        $category = $this->serializer->deserialize($categoryData);

        self::assertNull($category->getId());
        self::assertEquals('Test', $category->getName());
    }

    public function test_it_serializes(): void
    {
        $category = $this->getCategoryMock('Test', 'test1, test2, test3');

        $data = $this->serializer->serialize($category);

        $expected = [
            'id' => null,
            'name' => 'Test',
            'tags' => [
                'test1',
                'test2',
                'test3'
            ]
        ];

        self::assertEquals($expected, $data);
    }
}

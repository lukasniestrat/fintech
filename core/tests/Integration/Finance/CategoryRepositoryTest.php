<?php
declare(strict_types = 1);
namespace App\Tests\Integration\Finance;

use App\Exception\Finance\CategoryException;
use App\Repository\Finance\CategoryRepository;
use App\Tests\Factory\Common\AssertExceptionTrait;
use App\Tests\Integration\Common\AbstractFinRepositoryTest;

class CategoryRepositoryTest extends AbstractFinRepositoryTest
{
    use AssertExceptionTrait;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_it_gets_categories(): void
    {
        $categories = $this->getRepository()->getCategories();

        self::assertCount(4, $categories);
    }

    public function test_it_gets_category_by_id(): void
    {
        $category = $this->getRepository()->getCategoryById(1);

        self::assertEquals(1, $category->getId());
        self::assertEquals('Sonstiges', $category->getName());

        $this->assertException(function (): void {
            $this->getRepository()->getCategoryById(9999);
        }, CategoryException::class, CategoryException::NOT_FOUND, ['reason' => 'No category with id 9999 found']);
    }

    public function test_it_finds_category_by_id(): void
    {
        $category = $this->getRepository()->findCategoryById(1);

        self::assertEquals(1, $category->getId());
        self::assertEquals('Sonstiges', $category->getName());

        $category = $this->getRepository()->findCategoryById(9999);

        self::assertNull($category);
    }

    protected function getRepository(): CategoryRepository
    {
        if (null === $this->repository) {
            $this->repository = self::getContainer()->get(CategoryRepository::class);
        }

        return $this->repository;
    }
}

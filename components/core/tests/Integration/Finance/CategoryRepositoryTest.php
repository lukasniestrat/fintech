<?php
declare(strict_types = 1);
namespace App\Tests\Integration\Finance;

use App\Entity\Finance\Category;
use App\Exception\Finance\CategoryException;
use App\Model\Common\RequestMetaData;
use App\Repository\Finance\CategoryRepository;
use App\Tests\Factory\Common\AssertExceptionTrait;
use App\Tests\Integration\Common\AbstractFinRepositoryTest;

class CategoryRepositoryTest extends AbstractFinRepositoryTest
{
    use AssertExceptionTrait;

    public function test_it_stores_category(): void
    {
        $category = new Category('Test', 'test, test, test');

        $nextInsertId = $this->getCurrentAutoIncForTable('category');
        $newCategory = $this->getRepository()->storeCategory($category);

        self::assertEquals($nextInsertId, $newCategory->getId());
    }

    public function test_it_gets_categories_for_transaction_import(): void
    {
        $categories = $this->getRepository()->getCategoriesForTransactionImport();

        self::assertCount(4, $categories);
    }

    public function test_it_gets_all_categories(): void
    {
        $categoriesList = $this->getRepository()->getCategories(new RequestMetaData());

        self::assertEquals(4, $categoriesList->getMetaData()->getTotal());
        self::assertCount(4, $categoriesList->getList());
    }

    public function test_it_gets_category_by_id(): void
    {
        $category = $this->getRepository()->getCategoryById(1);

        self::assertEquals(1, $category->getId());
        self::assertEquals('Sonstiges', $category->getName());

        $this->assertException(function (): void {
            $this->getRepository()->getCategoryById(9999);
        }, CategoryException::class, CategoryException::NOT_FOUND, ['reason' => 'no category with id 9999 found']);
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

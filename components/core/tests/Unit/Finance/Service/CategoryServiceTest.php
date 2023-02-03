<?php
declare(strict_types = 1);
namespace App\tests\Unit\Finance\Service;

use App\Model\Common\RequestMetaData;
use App\Service\Finance\CategoryService;
use App\Tests\Factory\Finance\CategoryFactoryTrait;
use App\Tests\Mocks\Finance\Repositories\CategoryRepositoryMock;
use PHPUnit\Framework\TestCase;

class CategoryServiceTest extends TestCase
{
    use CategoryFactoryTrait;

    private ?CategoryService $service = null;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new CategoryService(
            new CategoryRepositoryMock()
        );
    }

    public function test_it_stores_category(): void
    {
        $category = $this->getCategoryMock();

        $this->service->storeCategory($category);

        self::assertEquals(1, CategoryRepositoryMock::$countStoreCategory);
    }

    public function test_it_merge_categories(): void
    {
        $category = $this->getCategoryMock('Haushalt', 'Miete, Strom, Gas');
        $updatedCategory = $this->getCategoryMock('Einkaufen', 'Edeka, Lidl, Aldi');

        $mergedCategory = $this->service->mergeCategories($category, $updatedCategory);

        self::assertEquals($updatedCategory->getName(), $mergedCategory->getName());
        self::assertEquals($updatedCategory->getTags(), $mergedCategory->getTags());
    }

    public function test_it_gets_categories_for_transaction_import(): void
    {
        $this->service->getCategoriesForTransactionImport();

        self::assertEquals(1, CategoryRepositoryMock::$countGetCategoriesForTransactionImport);
    }

    public function test_it_gets_categories(): void
    {
        $this->service->getCategories(new RequestMetaData());

        self::assertEquals(1, CategoryRepositoryMock::$countGetCategories);
    }

    public function test_it_gets_category_by_id(): void
    {
        $this->service->getCategoryById(1);

        self::assertEquals(1, CategoryRepositoryMock::$countGetCategoryById);
    }
}

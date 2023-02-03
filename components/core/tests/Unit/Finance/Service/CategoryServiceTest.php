<?php
declare(strict_types = 1);
namespace App\tests\Unit\Finance\Service;

use App\Model\Common\RequestMetaData;
use App\Repository\Finance\CategoryRepository;
use App\Service\Finance\CategoryService;
use App\Tests\Factory\Finance\CategoryFactoryTrait;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CategoryServiceTest extends TestCase
{
    use CategoryFactoryTrait;

    private ?CategoryService $service = null;

    private CategoryRepository | MockObject $categoryRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->categoryRepository = $this->getMockBuilder(CategoryRepository::class)->disableOriginalConstructor()->getMock();
    }

    public function test_it_stores_category(): void
    {
        $category = $this->getCategoryMock();

        $this->categoryRepository->expects(self::once())->method('storeCategory')->with($category);

        $this->getService()->storeCategory($category);
    }

    public function test_it_merge_categories(): void
    {
        $category = $this->getCategoryMock('Haushalt', 'Miete, Strom, Gas');
        $updatedCategory = $this->getCategoryMock('Einkaufen', 'Edeka, Lidl, Aldi');

        $mergedCategory = $this->getService()->mergeCategories($category, $updatedCategory);

        self::assertEquals($updatedCategory->getName(), $mergedCategory->getName());
        self::assertEquals($updatedCategory->getTags(), $mergedCategory->getTags());
    }

    public function test_it_gets_categories_for_transaction_import(): void
    {
        $this->categoryRepository->expects(self::once())->method('getCategoriesForTransactionImport');

        $this->getService()->getCategoriesForTransactionImport();
    }

    public function test_it_gets_categories(): void
    {
        $this->categoryRepository->expects(self::once())->method('getCategories')->with(new RequestMetaData());

        $this->getService()->getCategories(new RequestMetaData());
    }

    public function test_it_gets_category_by_id(): void
    {
        $this->categoryRepository->expects(self::once())->method('getCategoryById')->with(1);

        $this->getService()->getCategoryById(1);
    }

    private function getService(
        CategoryRepository | MockObject $categoryRepository = null,
    ): CategoryService
    {
        return new CategoryService($categoryRepository ?? $this->categoryRepository);
    }
}

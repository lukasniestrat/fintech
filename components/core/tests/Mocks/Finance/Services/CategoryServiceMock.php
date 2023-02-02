<?php
declare(strict_types = 1);
namespace App\Tests\Mocks\Finance\Services;

use App\Entity\Finance\Category;
use App\Model\Common\ModelList;
use App\Model\Common\RequestMetaData;
use App\Service\Finance\CategoryService;
use App\Tests\Utils\ReflectionFactory;

class CategoryServiceMock extends CategoryService
{
    public static ?Category $category = null;

    public static array $categoriesList = [];

    public static int $countStoreCategory = 0;

    public static int $countMergeCategories = 0;

    public static int $countGetCategories = 0;

    public static int $countFindCategoryById = 0;

    public static int $countGetCategoriesForTransactionImport = 0;

    /** @noinspection PhpMissingParentConstructorInspection */
    public function __construct()
    {
        self::$category = null;
        self::$categoriesList = [];
        self::$countStoreCategory = 0;
        self::$countMergeCategories = 0;
        self::$countGetCategories = 0;
        self::$countFindCategoryById = 0;
        self::$countGetCategoriesForTransactionImport = 0;
    }

    public function storeCategory(Category $category): Category
    {
        self::$countStoreCategory++;

        return self::$category ?? ReflectionFactory::createInstanceOfClass(Category::class);
    }

    public function mergeCategories(Category $existingCategory, Category $updateCategory): Category
    {
        self::$countMergeCategories++;

        return self::$category ?? ReflectionFactory::createInstanceOfClass(Category::class);
    }

    public function getCategoriesForTransactionImport(): array
    {
        self::$countGetCategoriesForTransactionImport++;

        return self::$categoriesList;
    }

    public function getCategories(RequestMetaData $requestMetaData): ModelList
    {
        self::$countGetCategories++;

        return new ModelList(self::$categoriesList, $requestMetaData);
    }

    public function getCategoryById(int $id): Category
    {
        self::$countFindCategoryById++;

        return self::$category ?? ReflectionFactory::createInstanceOfClass(Category::class);
    }
}

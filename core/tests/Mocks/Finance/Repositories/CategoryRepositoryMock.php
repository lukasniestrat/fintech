<?php
declare(strict_types = 1);
namespace App\Tests\Mocks\Finance\Repositories;

use App\Entity\Finance\Category;
use App\Model\Common\ModelList;
use App\Model\Common\RequestMetaData;
use App\Repository\Finance\CategoryRepository;
use App\Tests\Utils\ReflectionFactory;

class CategoryRepositoryMock extends CategoryRepository
{
    public static array $categoriesList = [];

    public static ?Category $category = null;

    public static int $countStoreCategory = 0;

    public static int $countGetCategories = 0;

    public static int $countGetCategoriesForTransactionImport = 0;

    public static int $countGetCategoryById = 0;

    public static int $countFindCategoryById = 0;

    /** @noinspection PhpMissingParentConstructorInspection */
    public function __construct()
    {
        self::$category = null;
        self::$categoriesList = [];
        self::$countStoreCategory = 0;
        self::$countGetCategories = 0;
        self::$countGetCategoriesForTransactionImport = 0;
        self::$countGetCategoryById = 0;
        self::$countFindCategoryById = 0;
    }

    public function storeCategory(Category $category): Category
    {
        self::$countStoreCategory++;

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

        if ($requestMetaData->getOffset() > count(self::$categoriesList)) {
            return new ModelList([], new RequestMetaData());
        }

        return new ModelList(self::$categoriesList, $requestMetaData);
    }

    public function getCategoryById(int $id): Category
    {
        self::$countGetCategoryById++;

        return self::$category ?? ReflectionFactory::createInstanceOfClass(Category::class);
    }

    public function findCategoryById(int $id): ?Category
    {
        self::$countFindCategoryById++;

        return self::$category;
    }
}

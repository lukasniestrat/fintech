<?php
namespace App\Tests\Mocks\Finance\Repositories;

use App\Entity\Finance\Category;
use App\Repository\Finance\CategoryRepository;

class CategoryRepositoryMock extends CategoryRepository
{
    public static array $categories = [];

    public static ?Category $category = null;

    public static int $countGetAllCategories = 0;

    public static int $countGetOneCategoryById = 0;

    public static int $countFindOneCategoryById = 0;

    /** @noinspection PhpMissingParentConstructorInspection */
    public function __construct()
    {
        self::$category = null;
        self::$categories = [];
        self::$countGetAllCategories = 0;
        self::$countGetOneCategoryById = 0;
        self::$countFindOneCategoryById = 0;
    }

    public function getAllCategories(): array
    {
        self::$countGetAllCategories++;

        return self::$categories;
    }

    public function getOneCategoryById(int $categoryId): Category
    {
        self::$countGetOneCategoryById++;

        return self::$category;
    }

    public function findOneCategoryById(int $categoryId): ?Category
    {
        self::$countFindOneCategoryById++;

        return self::$category;
    }
}

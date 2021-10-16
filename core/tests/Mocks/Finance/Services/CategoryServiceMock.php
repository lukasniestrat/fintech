<?php
declare(strict_types = 1);
namespace App\Tests\Mocks\Finance\Services;

use App\Entity\Finance\Category;
use App\Service\Finance\CategoryService;

class CategoryServiceMock extends CategoryService
{
    public static ?Category $category = null;

    public static array $categories = [];

    public static int $countGetAllCategories = 0;

    public static int $countFindOneCategoryById = 0;

    public function __construct()
    {
        self::$category = null;
        self::$categories = [];
        self::$countGetAllCategories = 0;
        self::$countFindOneCategoryById = 0;
    }

    public function getAllCategories(): array
    {
        self::$countGetAllCategories++;

        return self::$categories;
    }

    public function findOneCategoryById(int $categoryId): ?Category
    {
        self::$countFindOneCategoryById++;

        return self::$category;
    }
}

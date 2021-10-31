<?php
declare(strict_types = 1);
namespace App\Tests\Mocks\Finance\Services;

use App\Entity\Finance\Category;
use App\Service\Finance\CategoryService;
use App\Tests\Utils\ReflectionFactory;

class CategoryServiceMock extends CategoryService
{
    public static ?Category $category = null;

    public static array $categories = [];

    public static int $countGetCategories = 0;

    public static int $countFindCategoryById = 0;

    /** @noinspection PhpMissingParentConstructorInspection */
    public function __construct()
    {
        self::$category = null;
        self::$categories = [];
        self::$countGetCategories = 0;
        self::$countFindCategoryById = 0;
    }

    public function getCategories(): array
    {
        self::$countGetCategories++;

        return self::$categories;
    }

    public function getCategoryById(int $id): Category
    {
        self::$countFindCategoryById++;

        $category = self::$category;
        if (null === $category) {
            $category = ReflectionFactory::createInstanceOfClass(Category::class);
        }

        return $category;
    }
}

<?php
declare(strict_types=1);
namespace App\Factory\Finance;

use App\Entity\Finance\Category;

class CategoryFactory
{
    public static function createCategory(string $name, string $tags): Category
    {
        return new Category($name, $tags);
    }
}
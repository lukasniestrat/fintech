<?php
declare(strict_types = 1);
namespace App\Tests\Factory\Finance;

use App\Entity\Finance\Category;
use App\Tests\Utils\ReflectionFactory;

trait CategoryFactoryTrait
{
    public function getCategoryMock(
        string $name = 'Sonstiges',
        ?string $tags = null
    ): Category {
        $category = ReflectionFactory::createInstanceOfClass(Category::class);
        ReflectionFactory::setPrivateProperty($category, 'name', $name);
        ReflectionFactory::setPrivateProperty($category, 'tags', $tags);

        return $category;
    }
}

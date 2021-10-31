<?php
declare(strict_types = 1);
namespace App\Service\Finance\Serializer;

use App\Entity\Finance\Category;
use App\Model\Common\Serializable;
use App\Service\Common\Serializer\AbstractFinSerializer;

class CategorySerializer extends AbstractFinSerializer
{
    public function serialize(Serializable $category): array
    {
        return $category->toArray();
    }

    public function deserialize(array $serializedCategory): Category
    {
        static::$expectedTemplate = [
            'name' => '',
            'tags' => [''],
        ];

        $this->validateStructure($serializedCategory);

        $tagList = '';
        $tagCount = count($serializedCategory['tags']);

        foreach ($serializedCategory['tags'] as $key => $value) {
            if (($key + 1) !== $tagCount) {
                $tagList .= $value . ', ';
            } else {
                $tagList .= $value;
            }
        }

        return new Category($serializedCategory['name'], $tagList);
    }
}

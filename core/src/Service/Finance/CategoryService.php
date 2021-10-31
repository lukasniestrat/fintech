<?php
declare(strict_types = 1);
namespace App\Service\Finance;

use App\Entity\Finance\Category;
use App\Exception\Finance\CategoryException;
use App\Model\Common\ModelList;
use App\Model\Common\RequestMetaData;
use App\Repository\Finance\CategoryRepository;

class CategoryService
{
    public function __construct(
        private CategoryRepository $categoryRepository
    ) {
    }

    public function storeCategory(Category $category): Category
    {
        return $this->categoryRepository->storeCategory($category);
    }

    public function mergeCategories(Category $existingCategory, Category $updateCategory): Category
    {
        $existingCategory
            ->setName($updateCategory->getName())
            ->setTags($updateCategory->getTags());

        return $existingCategory;
    }

    /**
     * @throws CategoryException
     */
    public function getCategoriesForTransactionImport(): array
    {
        return $this->categoryRepository->getCategoriesForTransactionImport();
    }

    public function getCategories(RequestMetaData $requestMetaData): ModelList
    {
        return $this->categoryRepository->getCategories($requestMetaData);
    }

    /**
     * @throws CategoryException
     */
    public function getCategoryById(int $id): Category
    {
        return $this->categoryRepository->getCategoryById($id);
    }
}

<?php
declare(strict_types = 1);
namespace App\Service\Finance;

use App\Entity\Finance\Category;
use App\Exception\Finance\CategoryException;
use App\Repository\Finance\CategoryRepository;

class CategoryService
{
    public function __construct(
        private CategoryRepository $categoryRepository
    ) {
    }

    /**
     * @throws CategoryException
     */
    public function getAllCategories(): array
    {
        return $this->categoryRepository->getAllCategories();
    }

    /**
     * @throws CategoryException
     */
    public function getOneCategoryById(int $categoryId): Category
    {
        return $this->categoryRepository->getOneCategoryById($categoryId);
    }
}

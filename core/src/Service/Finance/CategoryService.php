<?php
declare(strict_types = 1);
namespace App\Service\Finance;

use App\Entity\Finance\Category;
use App\Repository\Finance\CategoryRepository;

class CategoryService
{
    public function __construct(
        private CategoryRepository $categoryRepository
    ) {
    }

    public function getAllCategories(): array
    {
        return $this->categoryRepository->getAllCategories();
    }

    public function findOneCategoryById(int $categoryId): ?Category
    {
        return $this->categoryRepository->findOneCategoryById($categoryId);
    }
}

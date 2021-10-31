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
    public function getCategories(): array
    {
        return $this->categoryRepository->getCategories();
    }

    /**
     * @throws CategoryException
     */
    public function getCategoryById(int $id): Category
    {
        return $this->categoryRepository->getCategoryById($id);
    }
}

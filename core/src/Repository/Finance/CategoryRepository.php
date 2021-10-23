<?php
declare(strict_types = 1);
namespace App\Repository\Finance;

use App\Entity\Finance\Category;
use App\Exception\Finance\CategoryException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * @throws CategoryException
     */
    public function getAllCategories(): array
    {
        $categories = $this->getEntityManager()->getRepository(Category::class)->findAll();

        if (0 === count($categories)) {
            throw new CategoryException(CategoryException::NO_CATEGORIES_SET, ['reason' => 'There are no categories in the database']);
        }

        return $categories;
    }

    /**
     * @throws CategoryException
     */
    public function getOneCategoryById(int $categoryId): Category
    {
        $category = $this->findOneCategoryById($categoryId);
        if (null === $category) {
            throw new CategoryException(CategoryException::NOT_FOUND, ['reason' => 'No category with ' . $categoryId . ' found']);
        }

        return $category;
    }

    public function findOneCategoryById(int $categoryId): ?Category
    {
        return $this->getEntityManager()->getRepository(Category::class)->findOneBy(['id' => $categoryId]);
    }
}

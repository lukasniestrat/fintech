<?php
declare(strict_types=1);
namespace App\Repository\Finance;

use App\Entity\Finance\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function getAllCategories(): array
    {
        return $this->getEntityManager()->getRepository(Category::class)->findAll();
    }

    public function findOneCategoryById(int $categoryId): ?Category
    {
        return $this->getEntityManager()->getRepository(Category::class)->findOneBy(['id' => $categoryId]);
    }
}

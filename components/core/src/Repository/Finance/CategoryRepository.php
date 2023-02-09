<?php
declare(strict_types = 1);
namespace App\Repository\Finance;

use App\Entity\Finance\Category;
use App\Exception\Finance\CategoryException;
use App\Model\Common\ModelList;
use App\Model\Common\RequestMetaData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function storeCategory(Category $category): Category
    {
        $this->getEntityManager()->persist($category);
        $this->getEntityManager()->flush();

        return $category;
    }

    /**
     * @throws CategoryException
     */
    public function getCategoriesForTransactionImport(): array
    {
        $categories = $this->getEntityManager()
            ->getRepository(Category::class)
            ->findAll();
        if (0 === count($categories)) {
            throw new CategoryException(CategoryException::NO_CATEGORIES_SET, ['reason' => 'there are no categories in the database']);
        }

        return $categories;
    }

    public function getCategories(RequestMetaData $requestMetaData): ModelList
    {
        $query = $this->getEntityManager()->createQueryBuilder()
            ->select('category')
            ->from(Category::class, 'category')
            ->setFirstResult($requestMetaData->getOffset())
            ->setMaxResults($requestMetaData->getLimit())
            ->addOrderBy('category.' . $requestMetaData->getOrderBy(), $requestMetaData->getOrderSequence())
            ->getQuery()
            ->setHydrationMode(AbstractQuery::HYDRATE_OBJECT);

        $paginator = new Paginator($query);
        $paginator->setUseOutputWalkers(false);

        $result = $paginator->getIterator()->getArrayCopy();
        $requestMetaData->setTotal($paginator->count());

        return new ModelList($result, $requestMetaData);
    }

    /**
     * @throws CategoryException
     */
    public function getCategoryById(int $id): Category
    {
        $category = $this->findCategoryById($id);
        if (null === $category) {
            throw new CategoryException(CategoryException::NOT_FOUND, ['reason' => sprintf('no category with id %s found', $id)]);
        }

        return $category;
    }

    public function findCategoryById(int $id): ?Category
    {
        return $this->getEntityManager()
            ->getRepository(Category::class)
            ->find($id);
    }
}

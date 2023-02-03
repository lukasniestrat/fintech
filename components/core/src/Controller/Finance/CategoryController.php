<?php
declare(strict_types = 1);
namespace App\Controller\Finance;

use App\Controller\Common\AbstractFinController;
use App\Exception\Finance\CategoryException;
use App\Serializer\Finance\CategorySerializer;
use App\Service\Finance\CategoryService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractFinController
{
    public function __construct(
        private readonly CategoryService $categoryService,
        private readonly CategorySerializer $categorySerializer,
    ) {
    }

    #[Route('/categories', methods: ['POST', 'HEAD'])]
    public function storeCategory(Request $request): JsonResponse
    {
        $data = $this->getData($request);
        $category = $this->categorySerializer->deserialize($data);
        $newCategory = $this->categoryService->storeCategory($category);

        return new JsonResponse(
            $this->categorySerializer->serialize($newCategory),
            Response::HTTP_OK
        );
    }

    /**
     * @throws CategoryException
     */
    #[Route('/categories/{id}', methods: ['PUT', 'HEAD'])]
    public function updateCategory(int $id, Request $request): JsonResponse
    {
        $data = $this->getData($request);
        $category = $this->categoryService->getCategoryById($id);

        if (1 === $category->getId()) {
            throw new CategoryException(CategoryException::IMMUTABLE, ['reason' => sprintf('category with id %s is immutable because it is the standard category', $id)]);
        }

        $delta = $this->categorySerializer->deserialize($data);

        $category = $this->categoryService->mergeCategories($category, $delta);
        $category = $this->categoryService->storeCategory($category);

        return new JsonResponse(
            $this->categorySerializer->serialize($category),
            Response::HTTP_OK
        );
    }

    #[Route('/categories', methods: ['GET', 'HEAD'])]
    public function getCategories(Request $request): JsonResponse
    {
        $metaData = $this->getRequestMetaData($request);
        $categoriesList = $this->categoryService->getCategories($metaData);
        $categories = [];

        foreach ($categoriesList->getList() as $category) {
            $categories[] = $this->categorySerializer->serialize($category);
        }

        return new JsonResponse(
            $categories,
            Response::HTTP_OK,
            $this->getMetaHeaderData($categoriesList->getMetaData())
        );
    }

    /**
     * @throws CategoryException
     */
    #[Route('/categories/{id}', methods: ['GET', 'HEAD'])]
    public function getCategory(int $id): JsonResponse
    {
        $category = $this->categoryService->getCategoryById($id);

        return new JsonResponse(
            $this->categorySerializer->serialize($category),
            Response::HTTP_OK
        );
    }
}

<?php
namespace App\Controller\Common;

use App\Exception\Common\ApiRequestException;
use App\Model\Common\RequestMetaData;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractFinController
{
    private ?ContainerInterface $container = null;

    public function setContainer(ContainerInterface $container): ?ContainerInterface
    {
        $previous = $this->container;
        $this->container = $container;

        return $previous;
    }

    /**
     * @throws ApiRequestException
     */
    protected function getData(Request $request): array
    {
        if (is_resource($request->getContent())) {
            throw new ApiRequestException(ApiRequestException::INVALID_JSON_DATA);
        }
        $result = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        if (null === $result) {
            throw new ApiRequestException(ApiRequestException::INVALID_JSON_DATA);
        }

        return $result;
    }

    protected function getMetaHeaderData(RequestMetaData $metaData): array
    {
        return [
            'FIN-Meta-Total' => $metaData->getTotal(),
            'FIN-Meta-Limit' => $metaData->getLimit(),
            'FIN-Meta-Offset' => $metaData->getOffset(),
            'FIN-Meta-OrderBy' => $metaData->getOrderBy(),
            'FIN-Meta-OrderSequence' => $metaData->getOrderSequence(),
            'FIN-Meta-Search' => $metaData->getSearch(),
        ];
    }
}

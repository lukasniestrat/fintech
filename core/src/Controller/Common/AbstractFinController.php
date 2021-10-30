<?php
declare(strict_types = 1);
namespace App\Controller\Common;

use App\Exception\Common\ApiRequestException;
use App\Model\Common\RequestMetaData;
use JsonException;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractFinController
{
    protected const STANDARD_LIMIT = 10;

    protected const STANDARD_OFFSET = 0;

    protected const STANDARD_ORDER_COLUMN = 'id';

    protected const STANDARD_ORDER_SEQUENCE = 'desc';

    private ?ContainerInterface $container = null;

    public function setContainer(ContainerInterface $container): ?ContainerInterface
    {
        $previous = $this->container;
        $this->container = $container;

        return $previous;
    }

    /**
     * @throws ApiRequestException
     * @throws JsonException
     */
    final protected function getData(Request $request): array
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

    final protected function getMetaHeaderData(RequestMetaData $metaData): array
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

    final protected function getRequestMetaData(Request $request): RequestMetaData
    {
        $limit = $request->query->getInt('limit', static::STANDARD_LIMIT);
        $offset = max($request->query->getInt('offset', static::STANDARD_OFFSET), 0);
        $orderBy = (string) $request->query->get('orderBy', static::STANDARD_ORDER_COLUMN);
        $orderSequence = (string) $request->query->get('orderSequence', static::STANDARD_ORDER_SEQUENCE);

        return new RequestMetaData(0, $limit, $offset, $orderBy, $orderSequence, '');
    }
}

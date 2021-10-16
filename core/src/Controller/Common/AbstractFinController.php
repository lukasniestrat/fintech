<?php
namespace App\Controller\Common;

use App\Exception\Common\ApiRequestException;
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
        $result = json_decode($request->getContent(), true);
        if (null === $result) {
            throw new ApiRequestException(ApiRequestException::INVALID_JSON_DATA);
        }

        return $result;
    }
}

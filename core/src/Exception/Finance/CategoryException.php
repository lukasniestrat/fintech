<?php
declare(strict_types = 1);
namespace App\Exception\Finance;

use App\Exception\Common\FinException;
use Symfony\Component\HttpFoundation\Response;

class CategoryException extends FinException
{
    public const NO_CATEGORIES_SET = 1;

    public const NOT_FOUND = 2;

    public const IMMUTABLE = 3;

    public function __construct(int $type = FinException::UNKNOWN, array $context = [])
    {
        parent::__construct('Unkown error', $context);
        $this->httpStatusCode = 500;
        $this->type = $type;

        switch ($type) {
            case self::NO_CATEGORIES_SET:
                parent::__construct('No categories set', $context);
                $this->httpStatusCode = Response::HTTP_FORBIDDEN;
                break;
            case self::NOT_FOUND:
                parent::__construct('No category found', $context);
                $this->httpStatusCode = Response::HTTP_FORBIDDEN;
                break;
            case self::IMMUTABLE:
                parent::__construct('Category is immutable', $context);
                $this->httpStatusCode = Response::HTTP_FORBIDDEN;
                break;
        }
    }
}

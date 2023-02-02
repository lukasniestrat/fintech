<?php
declare(strict_types = 1);
namespace App\Exception\Finance;

use App\Exception\Common\FinException;
use Symfony\Component\HttpFoundation\Response;

class BankAccountException extends FinException
{
    public const NOT_FOUND = 1;

    public function __construct(int $type = FinException::UNKNOWN, array $context = [])
    {
        parent::__construct('Unkown error', $context);
        $this->httpStatusCode = 500;
        $this->type = $type;

        switch ($type) {
            case self::NOT_FOUND:
                parent::__construct('No bank account found', $context);
                $this->httpStatusCode = Response::HTTP_FORBIDDEN;
                break;
        }
    }
}

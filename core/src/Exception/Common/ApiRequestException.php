<?php

namespace App\Exception\Common;

use JetBrains\PhpStorm\Pure;

class ApiRequestException extends FinException
{
    public const INVALID_JSON_DATA = 1;

    public function __construct(int $type = FinException::UNKNOWN, array $context = [])
    {
        parent::__construct('Unkown error', $context);
        $this->httpStatusCode = 500;
        $this->type = $type;

        switch ($type) {
            case self::INVALID_JSON_DATA:
                parent::__construct('Invalid json data', $context);
                $this->httpStatusCode = 400;
                break;
        }
    }
}
<?php

namespace App\Exception\Common;

use Exception;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\HttpFoundation\Response;

class FinException extends Exception
{
    public const UNKNOWN = -1;

    protected int $type = self::UNKNOWN;

    protected int $httpStatusCode = Response::HTTP_INTERNAL_SERVER_ERROR;

    protected array $context = [];

    private string $detail;

    public function __construct(string $message, array $context)
    {
        $this->detail = $message;
        $this->context = $context;

        $serializedContext = '';
        if (!empty($context)) {
            $serializedContext .= ', Context: [';
            $entries = [];
            foreach ($context as $key => $value) {
                if (in_array(gettype($value), ['object', 'array'])) {
                    $value = gettype($value);
                }
                $entries[] = "{$key}: {$value}";
            }
            $serializedContext .= join(', ', $entries);
            $serializedContext .= ']';
        }

        parent::__construct($message . $serializedContext);
    }

    public function getContext(): array
    {
        return $this->context;
    }

    public function setContext(array $context): void
    {
        $this->context = $context;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function getHttpStatusCode(): int
    {
        return $this->httpStatusCode;
    }

    public function getDetail(): string
    {
        return $this->detail;
    }
}
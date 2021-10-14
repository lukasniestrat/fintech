<?php

namespace App\Exception\Common;

class UploadFileException extends FinException
{
    public const INVALID_FILE_EXTENSION = 1;

    public const NO_FILE = 2;

    public function __construct(int $type = FinException::UNKNOWN, array $context = [])
    {
        parent::__construct('Unkown error', $context);
        $this->httpStatusCode = 500;
        $this->type = $type;

        switch ($type) {
            case self::INVALID_FILE_EXTENSION:
                parent::__construct('Invalid file extension', $context);
                $this->httpStatusCode = 400;
                break;
            case self::NO_FILE:
                parent::__construct('No file set', $context);
                $this->httpStatusCode = 400;
                break;
        }
    }
}
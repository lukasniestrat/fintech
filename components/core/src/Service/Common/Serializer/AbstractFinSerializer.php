<?php
declare(strict_types = 1);
namespace App\Service\Common\Serializer;

use App\Model\Common\Serializable;

abstract class AbstractFinSerializer
{
    public static array $expectedTemplate = [];

    public static string $expectedExceptionClass;

    public static int $expectedExceptionType;

    abstract public function serialize(Serializable $object): array;

    abstract public function deserialize(array $array): object;

    public function validateStructure(array $data): void
    {
        if (count(array_diff_key(self::$expectedTemplate, $data)) > 0) {
            throw new self::$expectedExceptionClass(self::$expectedExceptionType, [
                'reason' => 'data does not match expected template structure',
            ]);
        }

        $this->validateDataByTemplate($data, self::$expectedTemplate);
    }

    private function validateDataByTemplate(array $data, array $template, string $parentKeys = ''): void
    {
        foreach ($template as $key => $value) {
            $message = $key;
            if ('' !== $parentKeys) {
                $message = $parentKeys . '.' . $message;
            }
            if (false === array_key_exists($key, $data)) {
                throw new self::$expectedExceptionClass(self::$expectedExceptionType, [
                    'reason' => sprintf('missing attribute "%s"', $message)
                ]);
            }

            $expectedValueType = gettype($template[$key]);
            $expectedNullMatch = null === $template[$key] && '' === $data[$key];
            $expectedEmptyStringMatch = '' === $template[$key] && null === $data[$key];

            if ($expectedNullMatch
                || $expectedEmptyStringMatch
                || $expectedValueType === gettype($data[$key])
                || ('string' === $expectedValueType && is_numeric($data[$key]))) {
                if (null === $data[$key] || '' === $data[$key] || (is_string($data[$key]) && '' === trim($data[$key]))) {
                    throw new self::$expectedExceptionClass(self::$expectedExceptionType, [
                        'reason' => sprintf('missing value for attribute "%s"', $message)
                    ]);
                }
                if ('array' === $expectedValueType) {
                    if ('' !== $parentKeys) {
                        $parentKeys .= '.';
                    }
                    $parentKeys .= $key;
                    $this->validateDataByTemplate($data[$key], $template[$key], $parentKeys);
                }
                $parentKeys = substr($parentKeys, 0, (int) strpos($parentKeys, '.'));
            } elseif (null !== $template[$key]) {
                throw new self::$expectedExceptionClass(self::$expectedExceptionType, [
                    'reason' => sprintf('invalid attribute "%s" %s expected, but found %s', $message, $expectedValueType, gettype($data[$key]))
                ]);
            }
        }
    }
}

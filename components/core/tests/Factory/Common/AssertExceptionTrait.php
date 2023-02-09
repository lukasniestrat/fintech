<?php
declare(strict_types = 1);
namespace App\Tests\Factory\Common;

use App\Exception\Common\FinException;
use Exception;
use PHPUnit\Framework\Assert;

trait AssertExceptionTrait
{
    public function assertException($act, string $expectedClass, int $exceptionType, array $context = null): void
    {
        $expectedException = null;
        try {
            $act();
        } catch (Exception $e) {
            $expectedException = $e;
        } finally {
            Assert::assertNotNull($expectedException);
            Assert::assertInstanceOf(FinException::class, $expectedException);
            Assert::assertInstanceOf($expectedClass, $expectedException);
            /** @var FinException $expectedException */
            Assert::assertEquals($exceptionType, $expectedException->getType());
            if ($context) {
                Assert::assertEquals($context, $expectedException->getContext());
            }
        }
    }
}

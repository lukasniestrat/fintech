<?php
declare(strict_types = 1);
namespace App\Tests\Integration\Common;

abstract class AbstractFinRepositoryTest extends AbstractFinIntegrationTest
{
    protected $repository;

    protected function tearDown(): void
    {
        if (null !== $this->repository) {
            $this->repository = null;
        }

        parent::tearDown();
    }

    abstract protected function getRepository();
}

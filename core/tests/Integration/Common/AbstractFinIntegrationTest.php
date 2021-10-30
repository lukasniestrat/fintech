<?php
declare(strict_types = 1);
namespace App\Tests\Integration\Common;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class AbstractFinIntegrationTest extends KernelTestCase
{
    /** @var Connection $connection */
    protected ?object $connection;

    private ?EntityManagerInterface $entityManager = null;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->setContainerMocks();

        $this->connection = static::$container->get('doctrine.dbal.default_connection');
        if (false === $this->connection->isConnected()) {
            $this->connection->connect();
        }
        if (false === $this->connection->isTransactionActive()) {
            $this->connection->beginTransaction();
        }
    }

    protected function tearDown(): void
    {
        if (null !== $this->connection) {
            $this->connection->rollBack();
            $this->connection->close();
            $this->connection = null;
        }
        if (null !== $this->entityManager) {
            $this->entityManager->clear();
            $this->entityManager->close();
            $this->entityManager = null;
        }

        parent::tearDown();
    }

    protected function executeFixture(string $pathToFixture, bool $removeCommentIndicators = true): void
    {
        $sqlComments = '@(([\'"]).*?[^\\\]\2)|((?:\#|--).*?$|/\*(?:[^/*]|/(?!\*)|\*(?!/)|(?R))*\*\/)\s*|(?<=;)\s+@ms';
        $statement = file_get_contents($pathToFixture);
        foreach (explode(';', $statement) as $subStatement) {
            if ($removeCommentIndicators) {
                $cleanStatement = trim(preg_replace($sqlComments, '$1', $subStatement));
            } else {
                $cleanStatement = trim($subStatement);
            }
            if (empty($cleanStatement)) {
                continue;
            }
            $this->connection->executeStatement($cleanStatement);
        }
    }

    protected function getCurrentAutoIncForTable(string $tableName): ?int
    {
        $result = $this->connection->executeQuery(
            'SELECT `AUTO_INCREMENT` FROM  INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = DATABASE() AND  TABLE_NAME = :tableName',
            [':tableName' => $tableName]
        )->fetchColumn();

        return '' === $result ? null : (int) $result;
    }

    protected function getEntityManager(): EntityManagerInterface
    {
        if (null === $this->entityManager) {
            $this->entityManager = static::$container->get('doctrine.orm.entity_manager');
        }

        return $this->entityManager;
    }

    protected function setContainerMocks(): void
    {
        if (null === static::$container) {
            throw new RuntimeException('For replacing services with mocks the container must be set!');
        }
    }
}

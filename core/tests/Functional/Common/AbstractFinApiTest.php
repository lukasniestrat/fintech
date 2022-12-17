<?php
declare(strict_types = 1);
namespace App\Tests\Functional\Common;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractFinApiTest extends WebTestCase
{
    protected const HTTP_GET = 'GET';

    protected const HTTP_POST = 'POST';

    protected const HTTP_PUT = 'PUT';

    protected const HTTP_DELETE = 'DELETE';

    /** @var Connection $connection */
    protected ?object $connection = null;

    protected KernelBrowser $client;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->client = self::createClient([], ['HTTP_User_Agent' => 'Linux']);
        $this->client->disableReboot();

        $this->setContainerMocks();

        $this->connection = static::getContainer()->get('doctrine.dbal.default_connection');
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

        parent::tearDown();
    }

    public function getData(string $jsonContent): array
    {
        return json_decode($jsonContent, true, 512, JSON_THROW_ON_ERROR);
    }

    protected function request(
        $httpVerb,
        $route,
        $data = [],
        $parameters = [],
        $files = []
    ): Response {
        $this->client->request(
            $httpVerb,
            $route,
            $parameters,
            $files,
            [],
            json_encode($data, JSON_THROW_ON_ERROR)
        );

        return $this->client->getResponse();
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

    protected function setContainerMocks(): void
    {
        if (null === static::getContainer()) {
            throw new RuntimeException('For replacing services with mocks the container must be set!');
        }
    }
}

<?php
declare(strict_types = 1);
namespace App\Tests\Functional\Common;

use RuntimeException;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class AbstractFinApiTest extends WebTestCase
{
    protected const HTTP_GET = 'GET';

    protected const HTTP_POST = 'POST';

    protected const HTTP_PUT = 'PUT';

    protected const HTTP_DELETE = 'DELETE';

    protected KernelBrowser $client;

    protected $connection;

    protected function setUp(): void
    {
        $this->client = self::createClient([], ['HTTP_User_Agent' => 'Linux']);
        $this->client->disableReboot();

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

        parent::tearDown();
    }

    public function getData(string $jsonContent): array
    {
        return json_decode($jsonContent, true, 512, JSON_THROW_ON_ERROR);
    }

    protected function setContainerMocks(): void
    {
        if (null === static::$container) {
            throw new RuntimeException('For replacing services with mocks the container must be set!');
        }
    }

    protected function request(
        $httpVerb,
        $route,
        $data = [],
        $parameters = [],
        $files = []
    ) {
        $this->client->request(
            $httpVerb,
            $route,
            $parameters,
            $files,
            [],
            json_encode($data)
        );

        return $this->client->getResponse();
    }
}

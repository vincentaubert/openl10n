<?php

namespace Openl10n\Bundle\ApiBundle\Test;

use JsonSchema;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class WebTestCase extends BaseWebTestCase
{
    protected $client;

    public function setUp()
    {
        parent::setUp();

        $client = $this->getClient();
        $client->setCredentials('user', 'userpass');
    }

    public function tearDown()
    {
        //parent::tearDown();

        if (null !== $this->client) {
            $this->client->stopIsolation();
        }
    }

    public function getClient()
    {
        if (null === $this->client) {
            $this->client = static::createClient();
            $this->client->startIsolation();
        }

        return $this->client;
    }

    /**
     * @param string $serviceName
     */
    public function get($serviceName)
    {
        return $this->getClient()->getContainer()->get($serviceName);
    }

    /**
     * @param string $schemaName
     */
    protected function assertJsonResponse($response, $statusCode = Response::HTTP_OK, $schemaName = null)
    {
        // Assert HTTP response status code
        $this->assertEquals(
            $statusCode, $response->getStatusCode(),
            $response->getContent()
        );

        $content = $response->getContent();
        $data = null;

        if ($content) {
            // Assert response is JSON content-type (unless response content is empty)
            $this->assertTrue(
                $response->headers->contains('Content-Type', 'application/json'),
                $response->headers
            );

            // Parse the response body
            $data = json_decode($response->getContent());
        }

        // Validate JSON data with given schema
        if (null !== $schemaName) {
            $schemaUri = 'file://'.realpath(__DIR__.'/../Resources/json_schemas/'.$schemaName.'.json');

            $retriever = new JsonSchema\Uri\UriRetriever;
            $schema = $retriever->retrieve($schemaUri);

            $validator = new JsonSchema\Validator();
            $validator->check($data, $schema);

            if (!$validator->isValid()) {
                $errorMessage = 'JSON response does not validate the schema';
                foreach ($validator->getErrors() as $error) {
                    $errorMessage .= sprintf("\n[%s] %s", $error['property'], $error['message']);
                }

                $this->fail($errorMessage);
            }
        }

        return $data;
    }
}

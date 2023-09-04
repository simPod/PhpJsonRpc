<?php

declare(strict_types=1);

namespace SimPod\JsonRpc\Extractor;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use PsrMock\Psr17\RequestFactory;
use PsrMock\Psr17\StreamFactory;
use PsrMock\Psr18\Client;
use PsrMock\Psr7\Response;

#[CoversClass(ResponseExtractor::class)]
final class ResponseExtractorTest extends TestCase
{
    public function testResponseResult(): void
    {
        $responseBody = <<<'EOD'
{
  "id": "test",
  "jsonrpc": "2.0",
  "result": {
    "key": "value"
  }
}
EOD;

        $requestFactory = new RequestFactory();
        $request = $requestFactory->createRequest('GET', 'http://example.com/');

        $streamFactory = new StreamFactory();
        $responseStream = $streamFactory->createStream($responseBody);

        $response = (new Response())->withBody($responseStream);

        $client = new Client();
        $client->addResponse(
            'GET',
            'http://example.com/',
            $response,
        );

        $response = $client->sendRequest($request);

        $sut = new ResponseExtractor($response);

        /** @var array<string, mixed> $result */
        $result = $sut->getResult();

        self::assertArrayHasKey('key', $result);
        self::assertEquals('value', $result['key']);
    }

    public function testResponse(): void
    {
        $responseBody = <<<'EOD'
{
  "id": "test",
  "jsonrpc": "2.0",
  "result": {
    "key": "value"
  }
}
EOD;

        $requestFactory = new RequestFactory();
        $request = $requestFactory->createRequest('GET', 'http://example.com/');

        $streamFactory = new StreamFactory();
        $responseStream = $streamFactory->createStream($responseBody);

        $response = (new Response())->withBody($responseStream);

        $client = new Client();
        $client->addResponse(
            'GET',
            'http://example.com/',
            $response,
        );

        $response = $client->sendRequest($request);

        $sut = new ResponseExtractor($response);

        self::assertEquals('test', $sut->getId());
        self::assertEquals('2.0', $sut->getVersion());
    }
}

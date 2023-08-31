<?php

declare(strict_types=1);

namespace SimPod\JsonRpc;

use PHPUnit\Framework\TestCase;
use PsrMock\Psr17\RequestFactory;
use PsrMock\Psr17\StreamFactory;

use function Safe\preg_replace;

/** @coversDefaultClass \SimPod\JsonRpc\HttpJsonRpcRequestFactory */
class HttpJsonRpcRequestFactoryTest extends TestCase
{
    /**
     * @covers ::request
     * @covers ::createRequest
     */
    public function testRequestBody(): void
    {
        $messageFactory = new RequestFactory();
        $streamFactory = new StreamFactory();

        $sut = new HttpJsonRpcRequestFactory($messageFactory, $streamFactory);

        $result = $sut->request('test', 'GET', ['key' => 'value']);

        $expected = <<<'EOD'
{
	"jsonrpc": "2.0",
	"method": "GET",
	"id": "test",
	"params": {
		"key": "value"
	}
}
EOD;

        self::assertEquals(
            preg_replace('/[\s\n]*/', '', $expected),
            $result->getBody()->getContents(),
        );
    }

    /**
     * @covers ::request
     * @covers ::createRequest
     */
    public function testRequestHeader(): void
    {
        $messageFactory = new RequestFactory();
        $streamFactory = new StreamFactory();

        $sut = new HttpJsonRpcRequestFactory($messageFactory, $streamFactory);

        $result = $sut->request('test', 'GET', ['key' => 'value']);

        self::assertArrayHasKey('Content-Type', $result->getHeaders());

        self::assertEquals(
            'application/json',
            $result->getHeaders()['Content-Type'][0],
        );
    }
}

<?php

declare(strict_types=1);

namespace SimPod\JsonRpc\Extractor;

use Generator;
use JsonException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use PsrMock\Psr17\StreamFactory;
use PsrMock\Psr7\Response;

#[CoversClass(ResponseExtractor::class)]
#[CoversClass(MalformedResponse::class)]
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

        $sut = new ResponseExtractor($this->response($responseBody));

        self::assertSame('test', $sut->getId());
        self::assertSame('2.0', $sut->getVersion());
        self::assertSame(['key' => 'value'], $sut->getResult());
        self::assertNull($sut->getErrorCode());
        self::assertNull($sut->getErrorMessage());
        self::assertNull($sut->getErrorData());
    }

    public function testResponseErrorWithPartialResult(): void
    {
        $responseBody = <<<'EOD'
{
  "id": null,
  "jsonrpc": "2.0",
  "method": false,
  "error": {
    "code": -32603,
    "message": "Some commands failed",
    "data": [null, false, {"details": [42, "failure"]}],
    "extra": true
  },
  "result": [{"output": "completed"}, null]
}
EOD;

        $sut = new ResponseExtractor($this->response($responseBody));

        self::assertNull($sut->getId());
        self::assertSame(-32603, $sut->getErrorCode());
        self::assertSame('Some commands failed', $sut->getErrorMessage());
        self::assertSame([null, false, ['details' => [42, 'failure']]], $sut->getErrorData());
        self::assertSame([['output' => 'completed'], null], $sut->getResult());
    }

    public function testResponseWithNullError(): void
    {
        $sut = new ResponseExtractor($this->response('{"id":42,"jsonrpc":"2.0","error":null,"result":false}'));

        self::assertSame(42, $sut->getId());
        self::assertFalse($sut->getResult());
        self::assertNull($sut->getErrorCode());
        self::assertNull($sut->getErrorMessage());
        self::assertNull($sut->getErrorData());
    }

    #[DataProvider('provideMalformedResponse')]
    public function testMalformedResponse(string $body, string $message): void
    {
        $this->expectException(MalformedResponse::class);
        $this->expectExceptionMessage($message);

        new ResponseExtractor($this->response($body));
    }

    /** @phpstan-return Generator<string, array{string, string}> */
    public static function provideMalformedResponse(): Generator
    {
        yield 'null root' => ['null', 'Response must be a JSON object.'];
        yield 'scalar root' => ['"response"', 'Response must be a JSON object.'];
        yield 'batch root' => ['[{"jsonrpc":"2.0","result":1}]', 'Response must contain a string jsonrpc field.'];
        yield 'missing version' => ['{}', 'Response must contain a string jsonrpc field.'];
        yield 'invalid version' => ['{"jsonrpc":2}', 'Response must contain a string jsonrpc field.'];
        yield 'boolean id' => ['{"jsonrpc":"2.0","id":false}', 'Response id must be a string, integer or null.'];
        yield 'fractional id' => ['{"jsonrpc":"2.0","id":1.5}', 'Response id must be a string, integer or null.'];
        yield 'structured id' => ['{"jsonrpc":"2.0","id":{}}', 'Response id must be a string, integer or null.'];
        yield 'scalar error' => ['{"jsonrpc":"2.0","error":"failure"}', 'Response error must be a JSON object.'];

        yield 'missing error code' => [
            '{"jsonrpc":"2.0","error":{"message":"failure"}}',
            'Response error must contain an integer code.',
        ];

        yield 'string error code' => [
            '{"jsonrpc":"2.0","error":{"code":"1","message":"failure"}}',
            'Response error must contain an integer code.',
        ];

        yield 'fractional error code' => [
            '{"jsonrpc":"2.0","error":{"code":1.5,"message":"failure"}}',
            'Response error must contain an integer code.',
        ];

        yield 'missing error message' => [
            '{"jsonrpc":"2.0","error":{"code":1}}',
            'Response error must contain a string message.',
        ];

        yield 'invalid error message' => [
            '{"jsonrpc":"2.0","error":{"code":1,"message":false}}',
            'Response error must contain a string message.',
        ];
    }

    public function testInvalidJson(): void
    {
        $this->expectException(JsonException::class);

        new ResponseExtractor($this->response('{'));
    }

    private function response(string $body): Response
    {
        return (new Response())->withBody(new StreamFactory()->createStream($body));
    }
}

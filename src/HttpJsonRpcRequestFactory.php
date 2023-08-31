<?php

declare(strict_types=1);

namespace SimPod\JsonRpc;

use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamFactoryInterface;

use function Safe\json_encode;

final class HttpJsonRpcRequestFactory implements JsonRpcRequestFactory
{
    private const V20 = '2.0';

    public function __construct(
        private RequestFactoryInterface $messageFactory,
        private StreamFactoryInterface $streamFactory,
    ) {
    }

    public function notification(string $method, array|null $params = null): RequestInterface
    {
        return $this->createRequest(
            [
                'jsonrpc' => self::V20,
                'method' => $method,
                'params' => $params,
            ],
        );
    }

    public function request(string|int|null $id, string $method, array|null $params = null): RequestInterface
    {
        $body = [
            'jsonrpc' => self::V20,
            'method' => $method,
            'id' => $id,
        ];

        if ($params !== null) {
            $body['params'] = $params;
        }

        return $this->createRequest($body);
    }

    /** @param mixed[] $body */
    private function createRequest(array $body = []): RequestInterface
    {
        return $this->messageFactory->createRequest('POST', '')
            ->withHeader('Content-Type', 'application/json')
            ->withBody($this->streamFactory->createStream(json_encode($body)));
    }
}

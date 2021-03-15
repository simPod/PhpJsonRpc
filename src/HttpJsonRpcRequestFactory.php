<?php

declare(strict_types=1);

namespace SimPod\JsonRpc;

use Nyholm\Psr7\Stream;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;

use function Safe\json_encode;

final class HttpJsonRpcRequestFactory implements JsonRpcRequestFactory
{
    private const V2_0 = '2.0';

    private RequestFactoryInterface $messageFactory;

    public function __construct(RequestFactoryInterface $messageFactory)
    {
        $this->messageFactory = $messageFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function notification(string $method, ?array $params = null) : RequestInterface
    {
        return $this->createRequest(
            [
                'jsonrpc' => self::V2_0,
                'method' => $method,
                'params' => $params,
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function request($id, string $method, ?array $params = null) : RequestInterface
    {
        $body = [
            'jsonrpc' => self::V2_0,
            'method' => $method,
            'id' => $id,
        ];

        if ($params !== null) {
            $body['params'] = $params;
        }

        return $this->createRequest($body);
    }

    /** @param mixed[] $body */
    private function createRequest(array $body = []) : RequestInterface
    {
        return $this->messageFactory->createRequest('POST', '')
            ->withHeader('Content-Type', 'application/json')
            ->withBody(Stream::create(json_encode($body)));
    }
}

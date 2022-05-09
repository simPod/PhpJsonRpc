<?php

declare(strict_types=1);

namespace SimPod\JsonRpc;

use Psr\Http\Message\RequestInterface;

interface JsonRpcRequestFactory
{
    public const METHOD_REQUEST = 'REQUEST';
    public const METHOD_NOTIFICATION = 'NOTIFICATION';

    /**
     * @link   http://www.jsonrpc.org/specification#notification
     *
     * @param mixed[]|null $params
     */
    public function notification(string $method, ?array $params = null): RequestInterface;

    /**
     * @link   http://www.jsonrpc.org/specification#request_object
     *
     * @param string|int|null $id
     * @param mixed[]|null    $params
     */
    public function request($id, string $method, ?array $params = null): RequestInterface;
}

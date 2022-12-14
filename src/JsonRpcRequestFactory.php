<?php

declare(strict_types=1);

namespace SimPod\JsonRpc;

use Psr\Http\Message\RequestInterface;

interface JsonRpcRequestFactory
{
    public const MethodRequest = 'REQUEST';
    public const MethodNotification = 'NOTIFICATION';

    /**
     * @link   http://www.jsonrpc.org/specification#notification
     *
     * @param mixed[]|null $params
     */
    public function notification(string $method, array|null $params = null): RequestInterface;

    /**
     * @link   http://www.jsonrpc.org/specification#request_object
     *
     * @param mixed[]|null    $params
     */
    public function request(string|int|null $id, string $method, array|null $params = null): RequestInterface;
}

<?php

declare(strict_types=1);

namespace SimPod\JsonRpc\Extractor;

abstract class Extractor
{
    /** @var array{id: string, jsonrpc: string, error?: array{code: int, message: string, data?: mixed}, method: string, params?: array<string, mixed>} */
    protected array $messageContents;

    /** @param array{id: string, jsonrpc: string, error?: array{code: int, message: string, data?: mixed}, method: string, params?: array<string, mixed>} $messageContents */
    public function __construct(array $messageContents)
    {
        $this->messageContents = $messageContents;
    }
}

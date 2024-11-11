<?php

declare(strict_types=1);

namespace SimPod\JsonRpc\Extractor;

use Psr\Http\Message\MessageInterface;

use function json_decode;

use const JSON_THROW_ON_ERROR;

abstract class Extractor
{
    /** @var array{id: string, jsonrpc: string, error?: array{code: int, message: string, data?: mixed}, method: string, params?: array<string, mixed>, result?: mixed} */
    protected array $messageContents;

    public function __construct(MessageInterface $message)
    {
        /** @var array{id: string, jsonrpc: string, error?: array{code: int, message: string, data?: mixed}, method: string, params?: array<string, mixed>, result?: mixed} $contents */
        $contents = json_decode((string) $message->getBody(), true, flags: JSON_THROW_ON_ERROR);
        $this->messageContents = $contents;
    }
}

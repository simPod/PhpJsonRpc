<?php

declare(strict_types=1);

namespace SimPod\JsonRpc\Extractor;

use Psr\Http\Message\MessageInterface;

use function Safe\json_decode;

abstract class Extractor
{
    /** @var array{id: string, jsonrpc: string, error?: array{code: int, message: string, data?: mixed}, method: string, params?: array<string, mixed>} */
    protected array $messageContents;

    public function __construct(MessageInterface $message)
    {
        /** @var array{id: string, jsonrpc: string, error?: array{code: int, message: string, data?: mixed}, method: string, params?: array<string, mixed>} $contents */
        $contents = json_decode((string) $message->getBody(), true);
        $this->messageContents = $contents;
    }
}

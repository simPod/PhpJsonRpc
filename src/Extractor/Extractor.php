<?php

declare(strict_types=1);

namespace SimPod\JsonRpc\Extractor;

use Psr\Http\Message\MessageInterface;

use function json_decode;

use const JSON_THROW_ON_ERROR;

/**
 * @template TContents of array<array-key, mixed>
 * @phpstan-type MessageContents array{
 *     id: string|int,
 *     jsonrpc: string,
 *     error?: array{code: int, message: string, data?: mixed},
 *     method: string,
 *     params?: array<string, mixed>,
 *     result?: mixed
 * }
 */
abstract class Extractor
{
    /** @phpstan-var TContents */
    protected array $messageContents;

    public function __construct(MessageInterface $message)
    {
        $this->messageContents = $this->extractContents((string) $message->getBody());
    }

    /** @phpstan-return TContents */
    protected function extractContents(string $body): array
    {
        /** @phpstan-var TContents $contents */
        $contents = json_decode($body, true, flags: JSON_THROW_ON_ERROR);

        return $contents;
    }
}

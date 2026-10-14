<?php

declare(strict_types=1);

namespace SimPod\JsonRpc\Extractor;

use JsonException;
use Psr\Http\Message\MessageInterface;

use function is_array;
use function is_int;
use function is_string;
use function json_decode;

use const JSON_THROW_ON_ERROR;

/**
 * @phpstan-type ResponseContents array{
 *     id?: string|int|null,
 *     jsonrpc: string,
 *     error?: array{code: int, message: string, data?: mixed}|null,
 *     result?: mixed
 * }
 * @extends Extractor<ResponseContents>
 */
final class ResponseExtractor extends Extractor
{
    /**
     * @throws JsonException When the body is not valid JSON.
     * @throws MalformedResponse When response fields cannot be safely extracted.
     */
    public function __construct(MessageInterface $message)
    {
        parent::__construct($message);
    }

    public function getErrorCode(): int|null
    {
        $error = $this->messageContents['error'] ?? null;

        return $error === null ? null : $error['code'];
    }

    public function getErrorMessage(): string|null
    {
        $error = $this->messageContents['error'] ?? null;

        return $error === null ? null : $error['message'];
    }

    public function getErrorData(): mixed
    {
        $error = $this->messageContents['error'] ?? null;

        return $error === null ? null : ($error['data'] ?? null);
    }

    public function getId(): string|int|null
    {
        return $this->messageContents['id'] ?? null;
    }

    public function getResult(): mixed
    {
        return $this->messageContents['result'] ?? null;
    }

    public function getVersion(): string
    {
        return $this->messageContents['jsonrpc'];
    }

    /** @phpstan-return ResponseContents */
    protected function extractContents(string $body): array
    {
        $contents = json_decode($body, true, flags: JSON_THROW_ON_ERROR);

        if (! is_array($contents)) {
            throw new MalformedResponse('Response must be a JSON object.');
        }

        if (! isset($contents['jsonrpc']) || ! is_string($contents['jsonrpc'])) {
            throw new MalformedResponse('Response must contain a string jsonrpc field.');
        }

        $id = $contents['id'] ?? null;
        if ($id !== null && ! is_string($id) && ! is_int($id)) {
            throw new MalformedResponse('Response id must be a string, integer or null.');
        }

        if (isset($contents['error'])) {
            $error = $contents['error'];
            if (! is_array($error)) {
                throw new MalformedResponse('Response error must be a JSON object.');
            }

            if (! isset($error['code']) || ! is_int($error['code'])) {
                throw new MalformedResponse('Response error must contain an integer code.');
            }

            if (! isset($error['message']) || ! is_string($error['message'])) {
                throw new MalformedResponse('Response error must contain a string message.');
            }
        }

        /** @phpstan-var ResponseContents $contents */
        return $contents;
    }
}

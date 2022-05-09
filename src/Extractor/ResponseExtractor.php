<?php

declare(strict_types=1);

namespace SimPod\JsonRpc\Extractor;

final class ResponseExtractor extends Extractor
{
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
}

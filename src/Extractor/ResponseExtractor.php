<?php

declare(strict_types=1);

namespace SimPod\JsonRpc\Extractor;

final class ResponseExtractor extends Extractor
{
    public function getErrorCode(): ?int
    {
        $error = $this->messageContents['error'] ?? null;

        return $error === null ? null : $error['code'];
    }

    public function getErrorMessage(): ?string
    {
        $error = $this->messageContents['error'] ?? null;

        return $error === null ? null : $error['message'];
    }

    /** @return mixed */
    public function getErrorData()
    {
        $error = $this->messageContents['error'] ?? null;

        return $error === null ? null : ($error['data'] ?? null);
    }

    /** @return string|int|null */
    public function getId()
    {
        return $this->messageContents['id'] ?? null;
    }

    /** @return mixed */
    public function getResult()
    {
        return $this->messageContents['result'] ?? null;
    }

    public function getVersion(): string
    {
        return $this->messageContents['jsonrpc'];
    }
}

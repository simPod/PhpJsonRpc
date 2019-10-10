<?php

declare(strict_types=1);

namespace SimPod\JsonRpc\Extractor;

final class ResponseExtractor extends Extractor
{
    public function getErrorCode() : ?int
    {
        return $this->messageContents['error']['code'] ?? null;
    }

    public function getErrorMessage() : ?string
    {
        return $this->messageContents['error']['message'] ?? null;
    }

    /**
     * @return mixed
     */
    public function getErrorData()
    {
        return $this->messageContents['error']['data'] ?? null;
    }

    /**
     * @return string|int|null
     */
    public function getId()
    {
        return $this->messageContents['id'] ?? null;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->messageContents['result'] ?? null;
    }

    public function getVersion() : string
    {
        return $this->messageContents['jsonrpc'];
    }
}

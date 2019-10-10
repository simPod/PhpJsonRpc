<?php

declare(strict_types=1);

namespace SimPod\JsonRpc\Extractor;

final class RequestExtractor extends Extractor
{
    /**
     * @return string|int|null
     */
    public function getId()
    {
        return $this->messageContents['id'] ?? null;
    }

    public function getMethod() : string
    {
        return $this->messageContents['method'];
    }

    /**
     * @return mixed[]|null
     */
    public function getParams() : ?array
    {
        return $this->messageContents['params'] ?? null;
    }

    public function getVersion() : string
    {
        return $this->messageContents['jsonrpc'];
    }
}

<?php

declare(strict_types=1);

namespace SimPod\JsonRpc\Extractor;

use Psr\Http\Message\MessageInterface;
use function Safe\json_decode;

abstract class Extractor
{
    /** @var mixed[] */
    protected $messageContents;

    public function __construct(MessageInterface $message)
    {
        $body                  = $message->getBody();
        $this->messageContents = json_decode($body->getContents(), true);

        if (! $body->isSeekable()) {
            return;
        }

        $body->rewind();
    }
}

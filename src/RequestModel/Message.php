<?php

declare(strict_types=1);

namespace Mzk\ZiskejApi\RequestModel;

final class Message
{
    /**
     * Message text
     */
    public readonly string $text;

    public function __construct(string $text)
    {
        $this->text = $text;
    }

    /**
     * @return array<string>
     */
    public function toArray(): array
    {
        return [
            'text' => $this->text,
        ];
    }
}

<?php

declare(strict_types=1);

namespace Mzk\ZiskejApi\RequestModel;

final class Messages
{
    public readonly bool $read;

    public function __construct(bool $read)
    {
        $this->read = $read;
    }

    /**
     * @return array<bool>
     */
    public function toArray(): array
    {
        return [
            'unread' => !$this->read,
        ];
    }
}

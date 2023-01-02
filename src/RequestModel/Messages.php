<?php

declare(strict_types=1);

namespace Mzk\ZiskejApi\RequestModel;

final class Messages
{
    /**
     * @var bool
     */
    private bool $read;

    public function __construct(bool $read)
    {
        $this->read = $read;
    }

    /**
     * @return array<mixed>
     */
    public function toArray(): array
    {
        return [
            'unread' => !$this->read,
        ];
    }
}

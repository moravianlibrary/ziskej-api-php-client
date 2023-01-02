<?php

declare(strict_types=1);

namespace Mzk\ZiskejApi\ResponseModel;

use SmartEmailing\Types\Arrays;

final class MessageCollection
{
    /**
     * @var array<\Mzk\ZiskejApi\ResponseModel\Message>
     */
    private array $items = [];

    /**
     * @param array<array<string>> $data
     *
     * @return \Mzk\ZiskejApi\ResponseModel\MessageCollection
     *
     * @throws \Exception
     */
    public static function fromArray(array $data): MessageCollection
    {
        $self = new self();
        foreach ($data as $subarray) {
            if (Arrays::fromOrNull($subarray, true)) {
                $self->addMessage(Message::fromArray($subarray));
            }
        }
        return $self;
    }

    public function addMessage(Message $message): void
    {
        $this->items[] = $message;
    }

    /**
     * @return array<\Mzk\ZiskejApi\ResponseModel\Message>
     */
    public function getAll(): array
    {
        return $this->items;
    }
}

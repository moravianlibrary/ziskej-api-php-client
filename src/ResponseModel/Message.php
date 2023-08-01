<?php

declare(strict_types=1);

namespace Mzk\ZiskejApi\ResponseModel;

use DateTimeImmutable;
use SmartEmailing\Types\BoolType;
use SmartEmailing\Types\StringType;

final class Message
{
    public function __construct(
        public readonly string $sender,
        public readonly DateTimeImmutable $createdAt,
        public readonly bool $read,
        public readonly string $text
    ) {
    }

    /**
     * @param array<string> $data
     *
     * @return Message
     *
     * @throws \Exception
     */
    public static function fromArray(array $data): Message
    {
        return new self(
            StringType::extract($data, 'sender'),
            new DateTimeImmutable(StringType::extract($data, 'created_datetime')),
            !BoolType::extract($data, 'unread'),
            StringType::extract($data, 'text')
        );
    }
}

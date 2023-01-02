<?php

declare(strict_types=1);

namespace Mzk\ZiskejApi\ResponseModel;

use DateTimeImmutable;
use SmartEmailing\Types\BoolType;
use SmartEmailing\Types\StringType;

final class Message
{
    /**
     * @var string
     */
    private string $sender;

    /**
     * @var \DateTimeImmutable
     */
    private DateTimeImmutable $createdAt;

    /**
     * @var bool
     */
    private bool $read;

    /**
     * @var string
     */
    private string $text;

    /**
     * Message constructor.
     *
     * @param string $sender
     * @param \DateTimeImmutable $date
     * @param bool $read
     * @param string $text
     */
    public function __construct(
        string $sender,
        DateTimeImmutable $date,
        bool $read,
        string $text
    ) {
        $this->sender = $sender;
        $this->createdAt = $date;
        $this->read = $read;
        $this->text = $text;
    }

    /**
     * @param array<string> $data
     *
     * @return \Mzk\ZiskejApi\ResponseModel\Message
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

    public function getSender(): string
    {
        return $this->sender;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function isRead(): bool
    {
        return $this->read;
    }

    public function getText(): string
    {
        return $this->text;
    }
}

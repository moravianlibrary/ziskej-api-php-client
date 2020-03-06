<?php declare(strict_types = 1);

namespace Mzk\ZiskejApi\ResponseModel;

use DateTimeImmutable;
use SmartEmailing\Types\DatesImmutable;
use SmartEmailing\Types\PrimitiveTypes;

class Message
{

    /**
     * @var string
     */
    private $sender;

    /**
     * @var \DateTimeImmutable
     */
    private $date;

    /**
     * @var bool
     */
    private $read;

    /**
     * @var string
     */
    private $text;

    /**
     * @param string[] $data
     * @return \Mzk\ZiskejApi\ResponseModel\Message
     */

    public static function fromArray(array $data): Message
    {
        $self = new self();
        $self->sender = PrimitiveTypes::extractString($data, 'sender');
        $self->date = DatesImmutable::extract($data, 'date');
        $self->read = !PrimitiveTypes::extractBool($data, 'unread');
        $self->text = PrimitiveTypes::extractString($data, 'text');
        return $self;
    }

    /**
     * @return mixed[]
     */
    public function toArray(): array
    {
        return [
            'sender' => $this->sender,
            'date' => $this->date->format('Y-m-d'),
            'read' => $this->read,
            'text' => $this->text,
        ];
    }

    public function getSender(): string
    {
        return $this->sender;
    }

    public function setSender(string $sender): void
    {
        $this->sender = $sender;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(DateTimeImmutable $date): void
    {
        $this->date = $date;
    }

    public function isRead(): bool
    {
        return $this->read;
    }

    public function setRead(bool $read): void
    {
        $this->read = $read;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): void
    {
        $this->text = $text;
    }

}

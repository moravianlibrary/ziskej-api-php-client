<?php

declare(strict_types=1);

namespace Mzk\ZiskejApi\ResponseModel;

use DateTimeImmutable;
use Mzk\ZiskejApi\TestCase;
use SmartEmailing\Types\InvalidTypeException;

final class MessageTest extends TestCase
{
    /**
     * @var array<mixed>
     */
    private array $input = [
        'sender' => 'reader',
        'created_datetime' => '2020-02-04T12:32:44+01:00',
        'unread' => false,
        'text' => 'čistý text bez formátování s novými řádky typu unix',
    ];

    /**
     * @throws \Exception
     */
    public function testCreateFromArrayFull(): void
    {
        $message = Message::fromArray($this->input);

        $this->assertInstanceOf(Message::class, $message);

        $this->assertSame($this->input['sender'], $message->sender);
        $this->assertSame($this->input['unread'], !$message->read);
        $this->assertInstanceOf(DateTimeImmutable::class, $message->createdAt);
        $this->assertSame($this->input['created_datetime'], $message->createdAt->format('Y-m-d\TH:i:sP'));
        $this->assertSame($this->input['text'], $message->text);
    }

    /**
     * @throws \Exception
     */
    public function testCreateFromArrayEmpty(): void
    {
        $this->expectException(InvalidTypeException::class);
        Message::fromArray([]);
    }
}

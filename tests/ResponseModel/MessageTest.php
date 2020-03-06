<?php declare(strict_types = 1);

namespace Mzk\ZiskejApi\ResponseModel;

use DateTimeImmutable;
use Mzk\ZiskejApi\TestCase;

final class MessageTest extends TestCase
{

    /**
     * @var mixed[]
     */
    private $input = [
        "sender" => "reader",
        "date" => "2019-01-23",
        "unread" => false,
        "text" => "čistý text bez formátování s novými řádky typu unix",
    ];

    public function testCreateFromArray(): void
    {
        $message = Message::fromArray($this->input);

        $this->assertInstanceOf(Message::class, $message);

        $this->assertEquals($this->input['sender'], $message->getSender());
        $this->assertEquals($this->input['unread'], !$message->isRead());
        $this->assertEquals($this->input['date'], $message->getDate()->format('Y-m-d'));
        $this->assertInstanceOf(DateTimeImmutable::class, $message->getDate());
        $this->assertEquals($this->input['text'], $message->getText());

        $array = $message->toArray();
        $this->assertEquals($this->input['sender'], $array['sender']);
        $this->assertEquals($this->input['date'], $array['date']);
        $this->assertEquals($this->input['unread'], !$array['read']);
        $this->assertEquals($this->input['text'], $array['text']);
    }

    public function testCreateEmpty(): void
    {
        $this->expectException(\SmartEmailing\Types\InvalidTypeException::class);
        Message::fromArray([]);
    }

}

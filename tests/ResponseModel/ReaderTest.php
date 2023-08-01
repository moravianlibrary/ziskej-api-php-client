<?php

declare(strict_types=1);

namespace Mzk\ZiskejApi\ResponseModel;

use Mzk\ZiskejApi\TestCase;

final class ReaderTest extends TestCase
{
    /**
     * @var array<string>
     */
    private array $userDataMin = [
        'reader_id' => 'ID12345',
    ];

    /**
     * @var array<mixed>
     */
    private array $userDataFull = [
        'reader_id' => 'ID12345',
        'is_active' => true,
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john.doe@example.com',
        'notification_enabled' => true,
        'sigla' => 'AAA123',
        'is_gdpr_reg' => true,
        'is_gdpr_data' => true,
        'count_tickets' => 10,
        'count_tickets_open' => 6,
        'count_messages' => 20,
        'count_messages_unread' => 12,
    ];

    public function testCreateFromArrayMin(): void
    {
        $reader = Reader::fromArray($this->userDataMin);

        $this->assertInstanceOf(Reader::class, $reader);

        $this->assertSame($this->userDataMin['reader_id'], $reader->id);
        $this->assertSame(false, $reader->isActive);
        $this->assertSame('', $reader->firstName);
        $this->assertSame('', $reader->lastName);
        $this->assertSame('', $reader->email);
        $this->assertSame(false, $reader->isNotificationEnabled);
        $this->assertSame('', $reader->sigla);
        $this->assertSame(false, $reader->isGdprReg);
        $this->assertSame(false, $reader->isGdprData);
        $this->assertSame(0, $reader->countTickets);
        $this->assertSame(0, $reader->countTicketsOpen);
        $this->assertSame(0, $reader->countMessages);
        $this->assertSame(0, $reader->countMessagesUnread);
    }

    public function testCreateFromArrayFull(): void
    {
        $reader = Reader::fromArray($this->userDataFull);

        $this->assertInstanceOf(Reader::class, $reader);

        $this->assertSame($this->userDataFull['reader_id'], $reader->id);
        $this->assertSame($this->userDataFull['is_active'], $reader->isActive);
        $this->assertSame($this->userDataFull['first_name'], $reader->firstName);
        $this->assertSame($this->userDataFull['last_name'], $reader->lastName);
        $this->assertSame($this->userDataFull['email'], $reader->email);
        $this->assertSame($this->userDataFull['notification_enabled'], $reader->isNotificationEnabled);
        $this->assertSame($this->userDataFull['sigla'], $reader->sigla);
        $this->assertSame($this->userDataFull['is_gdpr_reg'], $reader->isGdprReg);
        $this->assertSame($this->userDataFull['is_gdpr_data'], $reader->isGdprData);
        $this->assertSame($this->userDataFull['count_tickets'], $reader->countTickets);
        $this->assertSame($this->userDataFull['count_tickets_open'], $reader->countTicketsOpen);
        $this->assertSame($this->userDataFull['count_messages'], $reader->countMessages);
        $this->assertSame($this->userDataFull['count_messages_unread'], $reader->countMessagesUnread);
    }
}

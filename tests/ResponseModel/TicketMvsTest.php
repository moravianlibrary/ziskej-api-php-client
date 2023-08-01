<?php

declare(strict_types=1);

namespace Mzk\ZiskejApi\ResponseModel;

use DateTimeImmutable;
use Mzk\ZiskejApi\Enum\TicketType;
use Mzk\ZiskejApi\TestCase;

final class TicketMvsTest extends TestCase
{
    /**
     * @var array<mixed>
     */
    private array $input = [
        'ticket_id' => 'abc0000000000001',
        'ticket_type' => 'mvs',
        'hid' => '000001',
        'sigla' => 'BOA001',
        'doc_id' => 'mzk.MZK01-000000001',
        'status_reader' => 'created',
        'status_reader_history' => [
            [
                'date' => '2020-03-09',
                'id' => 'created',
            ],
            [
                'date' => '2020-03-10',
                'id' => 'accepted',
            ],
        ],
        'is_open' => true,
        'payment_id' => '662d6dcc-50bb-43b0-8fb8-a30854737d62',
        'payment_url' => 'https://ziskej-test.techlib.cz/platebator/662d6dcc-50bb-43b0-8fb8-a30854737d62',
        'date_created' => '2020-01-01',
        'date_requested' => '2020-12-31',
        'date_return' => null,
        'count_messages' => 5,
        'count_messages_unread' => 2,
        'created_datetime' => '2020-01-01T12:32:44+01:00',
        'updated_datetime' => '2020-12-31T15:18:20+01:00',
    ];

    /**
     * @throws \Exception
     */
    public function testCreateEmptyObject(): void
    {
        /** @var TicketMvs $ticket */
        $ticket = TicketMvs::fromArray([
            'ticket_type' => TicketType::MVS->value,
            'ticket_id' => 'ticket_1',
            'created_datetime' => '2019-12-31 13:30:00',
        ]);

        $this->assertSame('mvs', $ticket->type->value);
        $this->assertSame('ticket_1', $ticket->id);
        $this->assertSame('2019-12-31 13:30:00', $ticket->createdAt->format('Y-m-d H:i:s'));

        $this->assertSame(null, $ticket->hid);
        $this->assertSame(null, $ticket->sigla);
        $this->assertSame(null, $ticket->documentId);
        $this->assertSame(null, $ticket->status);
        $this->assertSame(false, $ticket->isOpen);
        $this->assertSame(null, $ticket->paymentId);
        $this->assertSame(null, $ticket->paymentUrl);
        $this->assertSame(null, $ticket->requestedAt);
        $this->assertSame(null, $ticket->returnAt);
        $this->assertSame(0, $ticket->countMessages);
        $this->assertSame(0, $ticket->countMessagesUnread);
    }

    /**
     * @throws \Exception
     */
    public function testCreateFromArray(): void
    {
        $ticket = TicketMvs::fromArray($this->input);

        $this->assertSame($this->input['ticket_id'], $ticket->id);
        $this->assertSame($this->input['ticket_type'], $ticket->type->value);
        $this->assertSame($this->input['hid'], $ticket->hid);
        $this->assertSame($this->input['sigla'], $ticket->sigla);
        $this->assertSame($this->input['doc_id'], $ticket->documentId);
        $this->assertSame($this->input['status_reader'], $ticket->status->value);
        $this->assertSame($this->input['is_open'], $ticket->isOpen);
        $this->assertSame($this->input['payment_id'], $ticket->paymentId);
        $this->assertSame($this->input['payment_url'], (string) $ticket->paymentUrl);

        $this->assertSame($this->input['created_datetime'], $ticket->createdAt->format("Y-m-d\TH:i:sP"));
        $this->assertSame($this->input['updated_datetime'], $ticket->updatedAt->format("Y-m-d\TH:i:sP"));
        $this->assertSame($this->input['date_requested'], $ticket->requestedAt->format('Y-m-d'));
        $this->assertSame($this->input['date_created'], $ticket->createdAt->format('Y-m-d'));
        $this->assertSame($this->input['date_requested'], $ticket->requestedAt->format('Y-m-d'));
        $this->assertSame(null, $this->input['date_return']);
        $this->assertSame($this->input['count_messages'], $ticket->countMessages);
        $this->assertSame($this->input['count_messages_unread'], $ticket->countMessagesUnread);

        $this->assertCount(2, $ticket->statusHistory);
        $this->assertInstanceOf(Status::class, $ticket->statusHistory[0]);
    }
}

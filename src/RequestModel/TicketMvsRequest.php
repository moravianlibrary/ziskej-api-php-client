<?php

declare(strict_types=1);

namespace Mzk\ZiskejApi\RequestModel;

use DateTimeImmutable;
use Mzk\ZiskejApi\Enum\TicketType;

final class TicketMvsRequest extends TicketRequest
{
    /**
     * Ticket type
     */
    private const TICKET_TYPE = TicketType::MVS;

    /**
     * Ticket MVS constructor
     *
     * @param string $documentId Document ID
     * @param array<string> $documentAltIds Alternative document IDs
     * @param string|null $readerNote Reader's note to librarian
     * @param \DateTimeImmutable|null $dateRequested Requested date
     */
    public function __construct(
        public readonly string $documentId,
        public readonly ?array $documentAltIds = [],
        public readonly ?string $readerNote = null,
        public readonly ?DateTimeImmutable $dateRequested = null
    ) {
    }

    /**
     * @return array<string, array<string>|string>
     */
    public function toArray(): array
    {
        $return = [
            'ticket_type' => self::TICKET_TYPE->value,
            'doc_id' => $this->documentId,
        ];

        if (count($this->documentAltIds)) {
            $return['doc_alt_ids'] = $this->documentAltIds;
        }

        if ($this->readerNote !== null) {
            $return['reader_note'] = $this->readerNote;
        }

        if ($this->dateRequested !== null) {
            $return['date_requested'] = $this->dateRequested->format('Y-m-d');
        }

        return $return;
    }
}

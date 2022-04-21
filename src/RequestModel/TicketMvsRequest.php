<?php

declare(strict_types=1);

namespace Mzk\ZiskejApi\RequestModel;

use DateTimeImmutable;
use Mzk\ZiskejApi\Enum\TicketType;

class TicketMvsRequest extends TicketRequest
{

    /**
     * Ticket type
     *
     * @see \Mzk\ZiskejApi\Enum\TicketType
     */
    private const TICKET_TYPE = TicketType::MVS;

    /**
     * Document ID
     *
     * @var string
     */
    protected string $documentId;

    /**
     * Requested date
     *
     * @var \DateTimeImmutable|null
     */
    protected ?DateTimeImmutable $dateRequested = null;

    /**
     * Alternative document IDs
     *
     * @var string[]
     */
    protected array $documentAltIds = [];

    /**
     * Reader's note to librarian
     *
     * @var string|null
     */
    protected ?string $readerNote = null;

    /**
     * Ticket MVS constructor
     *
     * @param string $documentId
     * @param \DateTimeImmutable|null $dateRequested
     * @param array $documentAltIds
     * @param string|null $readerNote
     */
    public function __construct(
        string $documentId,
        ?DateTimeImmutable $dateRequested = null,
        array $documentAltIds = [],
        ?string $readerNote = null
    ) {
        $this->documentId = $documentId;
        $this->dateRequested = $dateRequested;
        $this->documentAltIds = $documentAltIds;
        $this->readerNote = $readerNote;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $return = [
            'ticket_type' => self::TICKET_TYPE,
            'doc_id' => $this->documentId,
        ];

        if (!empty($this->documentAltIds)) {
            $return['doc_alt_ids'] = $this->documentAltIds;
        }

        if (!empty($this->readerNote)) {
            $return['reader_note'] = $this->readerNote;
        }

        if (!empty($this->dateRequested)) {
            $return['date_requested'] = $this->dateRequested->format('Y-m-d');
        }

        return $return;
    }
}

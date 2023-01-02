<?php

declare(strict_types=1);

namespace Mzk\ZiskejApi\RequestModel;

use DateTimeImmutable;
use Mzk\ZiskejApi\Enum\TicketType;

final class TicketMvsRequest extends TicketRequest
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
     * @var array<string>
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
     * @param array<string> $documentAltIds
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
     * @return array<string, array<string>|string>
     */
    public function toArray(): array
    {
        $return = [
            'ticket_type' => self::TICKET_TYPE,
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

    /**
     * @return string
     */
    public function getDocumentId(): string
    {
        return $this->documentId;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getDateRequested(): ?DateTimeImmutable
    {
        return $this->dateRequested;
    }

    /**
     * @return array<string>
     */
    public function getDocumentAltIds(): array
    {
        return $this->documentAltIds;
    }

    /**
     * @param array<string> $documentAltIds
     */
    public function setDocumentAltIds(array $documentAltIds): void
    {
        $this->documentAltIds = $documentAltIds;
    }

    /**
     * @return string|null
     */
    public function getNote(): ?string
    {
        return $this->readerNote;
    }

    /**
     * @param string|null $readerNote
     * @todo remove setter
     */
    public function setNote(?string $readerNote): void
    {
        $this->readerNote = $readerNote;
    }
}

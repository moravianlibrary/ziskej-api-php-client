<?php

declare(strict_types=1);

namespace Mzk\ZiskejApi\ResponseModel;

use DateTimeImmutable;
use Mzk\ZiskejApi\Enum\StatusName;
use Mzk\ZiskejApi\Enum\TicketEddDocDataSource;
use Mzk\ZiskejApi\Enum\TicketEddSubtype;
use Mzk\ZiskejApi\Enum\TicketType;
use SmartEmailing\Types\Arrays;
use SmartEmailing\Types\DatesImmutable;
use SmartEmailing\Types\PrimitiveTypes;

class Ticket
{

    /**
     * Ticket id
     *
     * @var string
     */
    private string $id;

    /**
     * Ticket type (mvs, edd)
     *
     * @var string
     * @see \Mzk\ZiskejApi\Enum\TicketType
     */
    private string $type;

    /**
     * Edd ticket create type (auto, manual)
     *
     * @var ?string
     * @see \Mzk\ZiskejApi\Enum\TicketEddDocDataSource
     */
    private ?string $ticketDocDataSource = null;

    /**
     * Edd ticket subtype (article, selection)
     *
     * @var string|null
     * @see \Mzk\ZiskejApi\Enum\TicketEddSubtype
     */
    private ?string $eddSubtype = null;

    /**
     * Human-readable ticket ID
     *
     * @var string|null
     */
    private ?string $hid = null;

    /**
     * Sigla of main library
     *
     * @var string|null
     */
    private ?string $sigla = null;

    /**
     * Is ticket open
     *
     * @var bool|null
     */
    private ?bool $isOpen = null;

    /**
     * Status
     *
     * @var string|null
     * @see \Mzk\ZiskejApi\Enum\StatusName
     */
    private ?string $status = null;

    /**
     * History of ticket statuses
     *
     * @var \Mzk\ZiskejApi\ResponseModel\Status[]
     */
    private array $statusHistory = [];

    /**
     * @var string|null
     */
    private ?string $statusLabel = null;

    /**
     * Created datetime
     *
     * @var \DateTimeImmutable
     */
    private DateTimeImmutable $createdAt;

    /**
     * Last updated datetime
     *
     * @var \DateTimeImmutable|null
     */
    private ?DateTimeImmutable $updatedAt = null;

    /**
     * Date to return
     *
     * @var \DateTimeImmutable|null
     */
    private ?DateTimeImmutable $returnAt = null;

    /**
     * Delivery to date
     *
     * @var \DateTimeImmutable|null
     */
    private ?DateTimeImmutable $requestedAt = null;

    /**
     * Number of ticket's messagess
     *
     * @var int
     */
    private int $countMessages = 0;

    /**
     * Number of unread ticket's messagess
     *
     * @var int
     */
    private int $countMessagesUnread = 0;

    /**
     * CPK document ID
     *
     * @var string|null
     */
    private ?string $documentId = null;

    /**
     * @var string|null
     */
    private ?string $docTitleIn = null;

    /**
     * @var string|null
     */
    private ?string $docTitle = null;

    /**
     * @var string|null
     */
    private ?string $docVolume = null;

    /**
     * @var string|null
     */
    private ?string $docNumberYear = null;

    /**
     * @var string|null
     */
    private ?string $docNumberPyear = null;

    /**
     * @var string|null
     */
    private ?string $docNumberPnumber = null;

    /**
     * @var string|null
     */
    private ?string $docAuthor = null;

    /**
     * @var string|null
     */
    private ?string $docIssuer = null;

    /**
     * @var string|null
     */
    private ?string $docIsbn = null;

    /**
     * @var string|null
     */
    private ?string $docIssn = null;

    /**
     * @var string|null
     */
    private ?string $docCitation = null;

    /**
     * @var string|null
     */
    private ?string $docNote = null;

    /**
     * @var int|null
     */
    private ?int $pagesFrom = null;

    /**
     * @var int|null
     */
    private ?int $pagesTo = null;

    /**
     * Payment ID
     *
     * @var string|null
     */
    private ?string $paymentId = null;

    /**
     * Link to payment URL
     *
     * @var string|null  //@todo url type
     */
    private ?string $paymentUrl = null;

    private ?string $downloadUrl = null;

    /**
     * @throws \Consistence\Enum\InvalidEnumValueException
     */
    public function __construct(
        string $type,
        string $id,
        DateTimeImmutable $createdAt
    ) {
        TicketType::checkValue($type);
        $this->type = $type;
        $this->id = $id;
        $this->createdAt = $createdAt;
    }

    /**
     * @param string[] $data
     * @return \Mzk\ZiskejApi\ResponseModel\Ticket
     *
     * @throws \Exception
     */
    public static function fromArray(array $data): Ticket
    {
        $ticket = new self(
            PrimitiveTypes::extractString($data, 'ticket_type'),
            PrimitiveTypes::extractString($data, 'ticket_id'),
            new DateTimeImmutable(PrimitiveTypes::extractString($data, 'created_datetime'))
        );
        $ticket->ticketDocDataSource = PrimitiveTypes::extractStringOrNull($data, 'ticket_doc_data_source', true);
        if (!is_null($ticket->ticketDocDataSource)) {
            TicketEddDocDataSource::checkValue($ticket->ticketDocDataSource);
        }
        $ticket->eddSubtype = PrimitiveTypes::extractStringOrNull($data, 'edd_subtype', true);
        if (!is_null($ticket->eddSubtype)) {
            TicketEddSubtype::checkValue($ticket->eddSubtype);
        }
        $ticket->hid = PrimitiveTypes::extractStringOrNull($data, 'hid', true);
        $ticket->sigla = PrimitiveTypes::extractStringOrNull($data, 'sigla', true);
        $ticket->isOpen = PrimitiveTypes::extractBoolOrNull($data, 'is_open', true);
        $ticket->status = PrimitiveTypes::extractStringOrNull($data, 'status_reader', true);
        if (!is_null($ticket->status)) {
            StatusName::checkValue($ticket->status);
        }
        foreach (Arrays::extractArray($data, 'status_reader_history') as $statusHistory) {
            $ticket->statusHistory[] = Status::fromArray($statusHistory);
        }
        $ticket->statusLabel = PrimitiveTypes::extractStringOrNull($data, 'status_label', true);
        $ticket->updatedAt = PrimitiveTypes::extractStringOrNull($data, 'updated_datetime', true)
            ? new DateTimeImmutable(PrimitiveTypes::extractStringOrNull($data, 'updated_datetime', true))
            : null;
        $ticket->requestedAt = DatesImmutable::extractOrNull($data, 'date_requested', true);
        $ticket->returnAt = DatesImmutable::extractOrNull($data, 'date_return', true);
        $ticket->countMessages = PrimitiveTypes::extractIntOrNull($data, 'count_messages', true) ?? 0;
        $ticket->countMessagesUnread = PrimitiveTypes::extractIntOrNull($data, 'count_messages_unread', true) ?? 0;
        $ticket->documentId = PrimitiveTypes::extractStringOrNull($data, 'doc_id', true);
        $ticket->docTitleIn = PrimitiveTypes::extractStringOrNull($data, 'doc_title_in', true);
        $ticket->docTitle = PrimitiveTypes::extractStringOrNull($data, 'doc_title', true);
        $ticket->docVolume = PrimitiveTypes::extractStringOrNull($data, 'doc_volume', true);
        $ticket->docNumberYear = PrimitiveTypes::extractStringOrNull($data, 'doc_number_year', true);
        $ticket->docNumberPyear = PrimitiveTypes::extractStringOrNull($data, 'doc_number_pyear', true);
        $ticket->docNumberPnumber = PrimitiveTypes::extractStringOrNull($data, 'doc_number_pnumber', true);
        $ticket->docAuthor = PrimitiveTypes::extractStringOrNull($data, 'doc_author', true);
        $ticket->docIssuer = PrimitiveTypes::extractStringOrNull($data, 'doc_issuer', true);
        $ticket->docIsbn = PrimitiveTypes::extractStringOrNull($data, 'doc_isbn', true);
        $ticket->docIssn = PrimitiveTypes::extractStringOrNull($data, 'doc_issn', true);
        $ticket->docCitation = PrimitiveTypes::extractStringOrNull($data, 'doc_citation', true);
        $ticket->docNote = PrimitiveTypes::extractStringOrNull($data, 'doc_note', true);
        $ticket->paymentId = PrimitiveTypes::extractStringOrNull($data, 'payment_id', true);
        $ticket->paymentUrl = PrimitiveTypes::extractStringOrNull($data, 'payment_url', true);
        $ticket->downloadUrl = PrimitiveTypes::extractStringOrNull($data, 'edd_reader_url', true);
        $ticket->pagesFrom = PrimitiveTypes::extractIntOrNull($data, 'pages_from', true);
        $ticket->pagesTo = PrimitiveTypes::extractIntOrNull($data, 'pages_to', true);
        return $ticket;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getTicketDocDataSource(): ?string
    {
        return $this->ticketDocDataSource;
    }

    public function getEddSubtype(): ?string
    {
        return $this->eddSubtype;
    }

    public function getHid(): ?string
    {
        return $this->hid;
    }

    public function getSigla(): ?string
    {
        return $this->sigla;
    }

    public function isOpen(): ?bool
    {
        return $this->isOpen;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @return \Mzk\ZiskejApi\ResponseModel\Status[]
     */
    public function getStatusHistory(): array
    {
        return $this->statusHistory;
    }

    public function getStatusLabel(): ?string
    {
        return $this->statusLabel;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function getReturnAt(): ?DateTimeImmutable
    {
        return $this->returnAt;
    }

    public function getRequestedAt(): ?DateTimeImmutable
    {
        return $this->requestedAt;
    }

    public function getCountMessages(): int
    {
        return $this->countMessages;
    }

    public function getCountMessagesUnread(): int
    {
        return $this->countMessagesUnread;
    }

    public function getDocumentId(): ?string
    {
        return $this->documentId;
    }

    public function getDocTitleIn(): ?string
    {
        return $this->docTitleIn;
    }

    public function getDocTitle(): ?string
    {
        return $this->docTitle;
    }

    public function getDocVolume(): ?string
    {
        return $this->docVolume;
    }

    public function getDocNumberYear(): ?string
    {
        return $this->docNumberYear;
    }

    public function getDocNumberPyear(): ?string
    {
        return $this->docNumberPyear;
    }

    public function getDocNumberPnumber(): ?string
    {
        return $this->docNumberPnumber;
    }

    public function getDocAuthor(): ?string
    {
        return $this->docAuthor;
    }

    public function getDocIssuer(): ?string
    {
        return $this->docIssuer;
    }

    public function getDocIsbn(): ?string
    {
        return $this->docIsbn;
    }

    public function getDocIssn(): ?string
    {
        return $this->docIssn;
    }

    public function getDocCitation(): ?string
    {
        return $this->docCitation;
    }

    public function getDocNote(): ?string
    {
        return $this->docNote;
    }

    public function getPagesFrom(): ?int
    {
        return $this->pagesFrom;
    }

    public function getPagesTo(): ?int
    {
        return $this->pagesTo;
    }

    public function getPaymentId(): ?string
    {
        return $this->paymentId;
    }

    public function getPaymentUrl(): ?string
    {
        return $this->paymentUrl;
    }

    /**
     * @return string|null
     */
    public function getDownloadUrl(): ?string
    {
        return $this->downloadUrl;
    }
}

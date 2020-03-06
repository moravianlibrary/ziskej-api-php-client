<?php declare(strict_types = 1);

namespace Mzk\ZiskejApi\ResponseModel;

use DateTimeImmutable;
use SmartEmailing\Types\DatesImmutable;
use SmartEmailing\Types\PrimitiveTypes;

class Ticket
{

    /**
     * Ticket id
     *
     * @var string|null
     */
    private $id = null;

    /**
     * Ticket type
     *
     * @var string|null
     */
    private $type = null;

    /**
     * Human readable ticket ID
     *
     * @var string|null
     */
    private $hid = null;

    /**
     * Sigla of main library
     *
     * @var string|null
     */
    private $sigla = null;

    /**
     * CPK document ID
     *
     * @var string|null
     */
    private $documentId = null;

    /**
     * Status
     *
     * @var string|null
     */
    private $status = null;

    /**
     * Is ticket open
     *
     * @var bool|null
     */
    private $isOpen = null;

    /**
     * Payment ID
     *
     * @var string|null
     */
    private $paymentId = null;

    /**
     * Link to payment URL
     *
     * @var string|null  //@todo url type
     */
    private $paymentUrl = null;

    /**
     * Ticket creation date
     *
     * @var \DateTimeImmutable|null
     */
    private $dateCreated = null;

    /**
     * Latest delivery date
     *
     * @var \DateTimeImmutable|null
     */
    private $dateRequested = null;

    /**
     * Return date
     *
     * @var \DateTimeImmutable|null
     */
    private $dateReturn = null;

    /**
     * Number of ticket's messagess
     * @var int
     */
    private $countMessages = 0;

    /**
     * Number of unread ticket's messagess
     *
     * @var int
     */
    private $countMessagesUnread = 0;

    /**
     * @param string[] $data
     * @return \Mzk\ZiskejApi\ResponseModel\Ticket
     */
    public static function fromArray(array $data): Ticket
    {
        $ticket = new self();
        $ticket->id = PrimitiveTypes::extractStringOrNull($data, 'ticket_id', true);
        $ticket->type = PrimitiveTypes::extractStringOrNull($data, 'ticket_type', true);
        $ticket->hid = PrimitiveTypes::extractStringOrNull($data, 'hid', true);
        $ticket->sigla = PrimitiveTypes::extractStringOrNull($data, 'sigla', true);
        $ticket->documentId = PrimitiveTypes::extractStringOrNull($data, 'doc_id', true);
        $ticket->status = PrimitiveTypes::extractStringOrNull($data, 'status_reader', true);
        $ticket->isOpen = PrimitiveTypes::extractBoolOrNull($data, 'is_open', true);
        $ticket->paymentId = PrimitiveTypes::extractStringOrNull($data, 'payment_id', true);
        $ticket->paymentUrl = PrimitiveTypes::extractStringOrNull($data, 'payment_url', true);
        $ticket->dateCreated = DatesImmutable::extractOrNull($data, 'date_created', true);
        $ticket->dateRequested = DatesImmutable::extractOrNull($data, 'date_requested', true);
        $ticket->dateReturn = DatesImmutable::extractOrNull($data, 'date_return', true);
        $ticket->countMessages
            = PrimitiveTypes::extractIntOrNull($data, 'count_messages', true)
            ?? 0;
        $ticket->countMessagesUnread
            = PrimitiveTypes::extractIntOrNull($data, 'count_messages_unread', true)
            ?? 0;
        return $ticket;
    }

    /**
     * @return mixed[]
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'type' => $this->getType(),
            'hid' => $this->getHid(),
            'sigla' => $this->getSigla(),
            'document_id' => $this->getDocumentId(),
            'status' => $this->getStatus(),
            'is_open' => $this->isOpen(),
            'payment_id' => $this->getPaymentId(),
            'payment_url' => $this->getPaymentUrl(),
            'date_created' => !empty($this->getDateCreated())
                ? $this->getDateCreated()->format('Y-m-d H:i:s')
                : null,
            'date_requested' => !empty($this->getDateRequested())
                ? $this->getDateRequested()->format('Y-m-d H:i:s')
                : null,
            'date_return' => !empty($this->getDateReturn())
                ? $this->getDateReturn()->format('Y-m-d H:i:s')
                : null,
            'count_messages' => $this->getCountMessages(),
            'count_messages_unread' => $this->getCountMessagesUnread(),
        ];
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getHid(): ?string
    {
        return $this->hid;
    }

    public function getSigla(): ?string
    {
        return $this->sigla;
    }

    public function getDocumentId(): ?string
    {
        return $this->documentId;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function isOpen(): ?bool
    {
        return $this->isOpen;
    }

    public function getPaymentId(): ?string
    {
        return $this->paymentId;
    }

    public function getPaymentUrl(): ?string
    {
        return $this->paymentUrl;
    }

    public function getDateCreated(): ?DateTimeImmutable
    {
        return $this->dateCreated;
    }

    public function getDateRequested(): ?DateTimeImmutable
    {
        return $this->dateRequested;
    }

    public function getDateReturn(): ?DateTimeImmutable
    {
        return $this->dateReturn;
    }

    public function getCountMessages(): int
    {
        return $this->countMessages;
    }

    public function getCountMessagesUnread(): int
    {
        return $this->countMessagesUnread;
    }

}

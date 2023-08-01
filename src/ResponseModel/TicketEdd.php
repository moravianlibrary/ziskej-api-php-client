<?php

declare(strict_types=1);

namespace Mzk\ZiskejApi\ResponseModel;

use DateTimeImmutable;
use Mzk\ZiskejApi\Enum\StatusName;
use Mzk\ZiskejApi\Enum\TicketDataSource;
use Mzk\ZiskejApi\Enum\TicketEddSubtype;
use Mzk\ZiskejApi\Enum\TicketType;
use SmartEmailing\Types\Arrays;
use SmartEmailing\Types\BoolType;
use SmartEmailing\Types\DatesImmutable;
use SmartEmailing\Types\IntType;
use SmartEmailing\Types\StringType;
use SmartEmailing\Types\UrlType;

final class TicketEdd extends Ticket
{
    /**
     * @param \Mzk\ZiskejApi\Enum\TicketType $type Ticket type (mvs, edd)
     * @param string $id Ticket id
     * @param \DateTimeImmutable $createdAt Created datetime
     * @param \Mzk\ZiskejApi\Enum\TicketDataSource $ticketDocDataSource Ticket create type (auto, manual)
     * @param \Mzk\ZiskejApi\Enum\TicketEddSubtype|null $eddSubtype Edd ticket subtype (article, selection)
     * @param string|null $hid Human-readable ticket ID
     * @param string|null $sigla Sigla of main library
     * @param bool $isOpen Is ticket open
     * @param \Mzk\ZiskejApi\Enum\StatusName|null $status Status
     * @param array<\Mzk\ZiskejApi\ResponseModel\Status> $statusHistory History of ticket statuses
     * @param string|null $statusLabel Status label
     * @param \DateTimeImmutable|null $updatedAt Last updated datetime
     * @param \DateTimeImmutable|null $returnAt
     * @param \DateTimeImmutable|null $requestedAt Delivery to date
     * @param int $countMessages Number of ticket's messagess
     * @param int $countMessagesUnread Number of unread ticket's messagess
     * @param string|null $documentId CPK document ID
     * @param string|null $docTitleIn
     * @param string|null $docTitle
     * @param string|null $docVolume
     * @param string|null $docNumberYear
     * @param string|null $docNumberPyear
     * @param string|null $docNumberPnumber
     * @param string|null $docAuthor
     * @param string|null $docIssuer
     * @param string|null $docIsbn
     * @param string|null $docIssn
     * @param string|null $docCitation
     * @param string|null $docNote
     * @param int $pagesFrom
     * @param int $pagesTo
     * @param string|null $paymentId
     * @param \SmartEmailing\Types\UrlType|null $paymentUrl
     *
     * Ticket specific page with PDF download, payment gateway and complaint for reader (edd_reader_url)
     * @param \SmartEmailing\Types\UrlType|null $downloadUrl
     *
     * Show link to ticket specific page with PDF download, payment gateway and complaint for reader (edd_reader_url)
     * @param bool $showPdf
     *
     * @param bool $canComplaint Reader can complaint
     *
     * Reader can complaint or ticket specific page contain some info about already existing complaint
     * @param bool $showComplaint
     */
    public function __construct(
        public readonly TicketType $type,
        public readonly string $id,
        public readonly DateTimeImmutable $createdAt,
        public readonly TicketDataSource $ticketDocDataSource,
        public readonly ?TicketEddSubtype $eddSubtype,
        public readonly ?string $hid,
        public readonly ?string $sigla,
        public readonly bool $isOpen,
        public readonly ?StatusName $status,
        public readonly array $statusHistory,
        public readonly ?string $statusLabel,
        public readonly ?DateTimeImmutable $updatedAt,
        public readonly ?DateTimeImmutable $returnAt,
        public readonly ?DateTimeImmutable $requestedAt,
        public readonly int $countMessages,
        public readonly int $countMessagesUnread,
        public readonly ?string $documentId,
        public readonly ?string $docTitleIn,
        public readonly ?string $docTitle,
        public readonly ?string $docVolume,
        public readonly ?string $docNumberYear,
        public readonly ?string $docNumberPyear,
        public readonly ?string $docNumberPnumber,
        public readonly ?string $docAuthor,
        public readonly ?string $docIssuer,
        public readonly ?string $docIsbn,
        public readonly ?string $docIssn,
        public readonly ?string $docCitation,
        public readonly ?string $docNote,
        public readonly int $pagesFrom,
        public readonly int $pagesTo,
        public readonly ?string $paymentId,
        public readonly ?UrlType $paymentUrl,
        public readonly ?UrlType $downloadUrl,
        public readonly bool $showPdf,
        public readonly bool $canComplaint,
        public readonly bool $showComplaint
    ) {
    }

    /**
     * @param array<mixed> $data
     *
     * @return \Mzk\ZiskejApi\ResponseModel\TicketEdd
     *
     * @throws \Exception
     */
    public static function fromArray(array $data): TicketEdd
    {
        return new self(
            type: TicketType::from(StringType::extract($data, 'ticket_type')),
            id: StringType::extract($data, 'ticket_id'),
            createdAt: new DateTimeImmutable(StringType::extract($data, 'created_datetime')),
            ticketDocDataSource: TicketDataSource::from(StringType::extract($data, 'ticket_doc_data_source')),
            eddSubtype: TicketEddSubtype::tryFrom(StringType::extractOrNull($data, 'edd_subtype', true)),
            hid: StringType::extractOrNull($data, 'hid', true),
            sigla: StringType::extractOrNull($data, 'sigla', true),
            isOpen: (bool) BoolType::extractOrNull($data, 'is_open', true),
            status: StatusName::tryFrom((string) StringType::extractOrNull($data, 'status_reader', true)),
            statusHistory: self::setStatusHistory(Arrays::extract($data, 'status_reader_history')),
            statusLabel: StringType::extractOrNull($data, 'status_label', true),
            updatedAt: StringType::extractOrNull($data, 'updated_datetime', true)
                ? new DateTimeImmutable(StringType::extractOrNull($data, 'updated_datetime', true))
                : null,
            returnAt: DatesImmutable::extractOrNull($data, 'date_return', true),
            requestedAt: DatesImmutable::extractOrNull($data, 'date_requested', true),
            countMessages: IntType::extractOrNull($data, 'count_messages', true) ?? 0,
            countMessagesUnread: IntType::extractOrNull($data, 'count_messages_unread', true) ?? 0,
            documentId: StringType::extractOrNull($data, 'doc_id', true),
            docTitleIn: StringType::extractOrNull($data, 'doc_title_in', true),
            docTitle: StringType::extractOrNull($data, 'doc_title', true),
            docVolume: StringType::extractOrNull($data, 'doc_volume', true),
            docNumberYear: StringType::extractOrNull($data, 'doc_number_year', true),
            docNumberPyear: StringType::extractOrNull($data, 'doc_number_pyear', true),
            docNumberPnumber: StringType::extractOrNull($data, 'doc_number_pnumber', true),
            docAuthor: StringType::extractOrNull($data, 'doc_author', true),
            docIssuer: StringType::extractOrNull($data, 'doc_issuer', true),
            docIsbn: StringType::extractOrNull($data, 'doc_isbn', true),
            docIssn: StringType::extractOrNull($data, 'doc_issn', true),
            docCitation: StringType::extractOrNull($data, 'doc_citation', true),
            docNote: StringType::extractOrNull($data, 'doc_note', true),
            pagesFrom: (int) IntType::extractOrNull($data, 'pages_from', true),
            pagesTo: (int) IntType::extractOrNull($data, 'pages_to', true),
            paymentId: StringType::extractOrNull($data, 'payment_id', true),
            paymentUrl: UrlType::extractOrNull($data, 'payment_url', true),
            downloadUrl: UrlType::extractOrNull($data, 'edd_reader_url', true),
            showPdf: (bool) BoolType::extractOrNull($data, 'show_pdf', true),
            canComplaint: (bool) BoolType::extractOrNull($data, 'can_complaint', true),
            showComplaint: (bool) BoolType::extractOrNull($data, 'show_complaint', true),
        );
    }
}

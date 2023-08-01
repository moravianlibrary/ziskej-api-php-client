<?php

declare(strict_types=1);

namespace Mzk\ZiskejApi\RequestModel;

use DateTimeImmutable;
use Mzk\ZiskejApi\Enum\TicketDataSource;
use Mzk\ZiskejApi\Enum\TicketEddSubtype;
use Mzk\ZiskejApi\Enum\TicketType;

final class TicketEddRequest extends TicketRequest
{
    /**
     * Ticket type
     */
    private const TICKET_TYPE = TicketType::EDD;

    /**
     * @param TicketDataSource $ticketDocDataSource Typ vytvoření objednávky (automatická, manuální)
     * @param TicketEddSubtype $eddSubtype article (pro článek), selection (pro výňatek z monografie a periodika)
     * @param string $docTitleIn Název časopisu
     * @param string $docTitle Název článku
     * @param string|null $documentId Document ID
     * @param ?array<string> $documentAltIds Alternative document IDs
     * @param string|null $docIdIn Parent document ID
     * @param string|null $readerNote Reader's note to librarian
     * @param string|null $docNumberYear Rok
     * @param string|null $docNumberPyear Ročník
     * @param string|null $docNumberPnumber Číslo
     * @param string|null $docVolume Svazek
     * @param int|null $pagesFrom Rozsah stran, číslo první požadované strany
     * @param int|null $pagesTo Rozsah stran, číslo poslední požadované strany
     * @param string|null $docAuthor Autor
     * @param string|null $docIssuer Místo a rok vydání
     * @param string|null $docISSN ISSN
     * @param string|null $docISBN ISBN
     * @param string|null $docCitation Citace
     * @param string|null $docNote Poznámka k objednávce
     * @param \DateTimeImmutable|null $dateRequested Requested date
     */
    public function __construct(
        public readonly TicketDataSource $ticketDocDataSource,
        public readonly TicketEddSubtype $eddSubtype,
        public readonly string $docTitleIn,
        public readonly string $docTitle,
        public readonly ?string $documentId = null,
        public readonly ?array $documentAltIds = [],
        public readonly ?string $docIdIn = null,
        public readonly ?string $readerNote = null,
        public readonly ?string $docNumberYear = null,
        public readonly ?string $docNumberPyear = null,
        public readonly ?string $docNumberPnumber = null,
        public readonly ?string $docVolume = null,
        public readonly ?int $pagesFrom = 0,
        public readonly ?int $pagesTo = 0,
        public readonly ?string $docAuthor = null,
        public readonly ?string $docIssuer = null,
        public readonly ?string $docISSN = null,
        public readonly ?string $docISBN = null,
        public readonly ?string $docCitation = null,
        public readonly ?string $docNote = null,
        public readonly ?DateTimeImmutable $dateRequested = null
    ) {
    }

    /**
     * Convert object data to array for API
     *
     * @return array<string>
     */
    public function toArray(): array
    {
        $return = [
            'ticket_type' => self::TICKET_TYPE->value,
            'ticket_doc_data_source' => $this->ticketDocDataSource,
            'edd_subtype' => $this->eddSubtype,
            'doc_title_in' => $this->docTitleIn,
            'doc_title' => $this->docTitle,
            'doc_id' => $this->documentId,
        ];

        $nullable = [
            'doc_alt_ids' => 'documentAltIds',
            'doc_id_in' => 'docIdIn',
            'doc_number_year' => 'docNumberYear',
            'doc_number_pyear' => 'docNumberPyear',
            'doc_number_pnumber' => 'docNumberPnumber',
            'doc_volume' => 'docVolume',
            'pages_from' => 'pagesFrom',
            'pages_to' => 'pagesTo',
            'doc_author' => 'docAuthor',
            'doc_issuer' => 'docIssuer',
            'doc_issn' => 'docISSN',
            'doc_isbn' => 'docISBN',
            'doc_citation' => 'docCitation',
            'doc_note' => 'docNote',
            'reader_note' => 'readerNote',
        ];
        foreach ($nullable as $key => $property) {
            if ($this->$property !== null) {
                $return[$key] = $this->$property;
            }
        }

        if ($this->dateRequested !== null) {
            $return['date_requested'] = $this->dateRequested->format('Y-m-d');
        }

        return $return;
    }
}

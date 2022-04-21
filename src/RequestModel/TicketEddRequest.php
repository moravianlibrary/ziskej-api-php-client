<?php declare(strict_types = 1);

namespace Mzk\ZiskejApi\RequestModel;

use DateTimeImmutable;
use Mzk\ZiskejApi\Enum\TicketEddDocDataSource;
use Mzk\ZiskejApi\Enum\TicketEddSubtype;
use Mzk\ZiskejApi\Enum\TicketType;

class TicketEddRequest extends TicketRequest
{

    /**
     * Ticket type
     *
     * @see \Mzk\ZiskejApi\Enum\TicketType
     */
    private const TICKET_TYPE = TicketType::EDD;

    /**
     * Typ vytvoření objednávky (automatická, manuální)
     *
     * @var string
     * @see \Mzk\ZiskejApi\Enum\TicketEddDocDataSource
     */
    protected string $ticketDocDataSource;

    /**
     * article (pro článek), selection (pro výňatek z monografie a periodika)
     *
     * @var string
     * @see \Mzk\ZiskejApi\Enum\TicketEddSubtype
     */
    protected string $eddSubtype;

    /**
     * Název časopisu
     *
     * @var string
     */
    protected string $docTitleIn;

    /**
     * Název článku
     *
     * @var string
     */
    protected string $docTitle;

    /**
     * Document ID
     *
     * @var ?string
     */
    protected string $documentId;

    /**
     * Alternative document IDs
     *
     * @var string[]
     */
    protected array $documentAltIds = [];

    /**
     * ???
     * @var string|null
     */
    protected ?string $docIdIn = null;

    /**
     * Reader's note to librarian
     *
     * @var string|null
     */
    protected ?string $readerNote = null;

    /**
     * Rok
     *
     * @var string|null
     */
    protected ?string $docNumberYear = null;

    /**
     * Ročník
     *
     * @var string|null
     */
    protected ?string $docNumberPyear = null;

    /**
     * Číslo
     *
     * @var string|null
     */
    protected ?string $docNumberPnumber = null;

    /**
     * Svazek
     *
     * @var string|null
     */
    protected ?string $docVolume = null;

    /**
     * Rozsah stran, číslo první požadované strany
     *
     * @var int|null
     */
    protected ?int $pagesFrom = null;

    /**
     * Rozsah stran, číslo poslední požadované strany
     *
     * @var int|null
     */
    protected ?int $pagesTo = null;

    /**
     * Autor
     *
     * @var string|null
     */
    protected ?string $docAuthor = null;

    /**
     * Místo a rok vydání
     *
     * @var string|null
     */
    protected ?string $docIssuer = null;

    /**
     * ISSN
     *
     * @var string|null
     */
    protected ?string $docISSN = null;

    /**
     * ISBN
     *
     * @var string|null
     */
    protected ?string $docISBN = null;

    /**
     * Citace
     *
     * @var string|null
     */
    protected ?string $docCitation = null;

    /**
     * Poznámka k objednávce
     *
     * @var string|null
     */
    protected ?string $docNote = null;

    /**
     * Requested date
     *
     * @var \DateTimeImmutable|null
     */
    protected ?DateTimeImmutable $dateRequested = null;

    /**
     * Ticket EDD constructor.
     *
     * @param string $ticketDocDataSource
     * @param string $eddSubtype
     * @param string $docTitleIn
     * @param string $docTitle
     * @param string|null $documentId
     * @throws \Consistence\Enum\InvalidEnumValueException
     * @throws \Consistence\InvalidArgumentTypeException
     */
    public function __construct(
        string $ticketDocDataSource,
        string $eddSubtype,
        string $docTitleIn,
        string $docTitle,
        ?string $documentId
    ) {
        TicketEddDocDataSource::checkValue($ticketDocDataSource);
        TicketEddSubtype::checkValue($eddSubtype);

        if ($ticketDocDataSource === TicketEddDocDataSource::AUTO) {
            if (!is_string($documentId)) {
                throw new \Consistence\InvalidArgumentTypeException($documentId, 'string');
            }
        }

        $this->ticketDocDataSource = $ticketDocDataSource;
        $this->eddSubtype = $eddSubtype;
        $this->docTitleIn = $docTitleIn;
        $this->docTitle = $docTitle;
        $this->documentId = $documentId;
    }

    /**
     * Convert object data to array for API
     *
     * @return string[]
     */
    public function toArray(): array
    {
        $return = [
            'ticket_type' => self::TICKET_TYPE,
            'ticket_doc_data_source' => $this->ticketDocDataSource,
            'edd_subtype' => $this->eddSubtype,
            'doc_title_in' => $this->docTitleIn,
            'doc_title' => $this->docTitle,
            'doc_id' => $this->documentId,
        ];

        if (!empty($this->documentAltIds)) {
            $return['doc_alt_ids'] = $this->documentAltIds;
        }

        if (!empty($this->docIdIn)) {
            $return['doc_id_in'] = $this->docIdIn;
        }

        if (!empty($this->docNumberYear)) {
            $return['doc_number_year'] = $this->docNumberYear;
        }

        if (!empty($this->docNumberPyear)) {
            $return['doc_number_pyear'] = $this->docNumberPyear;
        }

        if (!empty($this->docNumberPnumber)) {
            $return['doc_number_pnumber'] = $this->docNumberPnumber;
        }

        if (!empty($this->docVolume)) {
            $return['doc_volume'] = $this->docVolume;
        }

        if (!empty($this->pagesFrom)) {
            $return['pages_from'] = $this->pagesFrom;
        }

        if (!empty($this->pagesTo)) {
            $return['pages_to'] = $this->pagesTo;
        }

        if (!empty($this->docAuthor)) {
            $return['doc_author'] = $this->docAuthor;
        }

        if (!empty($this->docIssuer)) {
            $return['doc_issuer'] = $this->docIssuer;
        }

        if (!empty($this->docISSN)) {
            $return['doc_issn'] = $this->docISSN;
        }

        if (!empty($this->docISBN)) {
            $return['doc_isbn'] = $this->docISBN;
        }

        if (!empty($this->docCitation)) {
            $return['doc_citation'] = $this->docCitation;
        }

        if (!empty($this->docNote)) {
            $return['doc_note'] = $this->docNote;
        }

        if (!empty($this->readerNote)) {
            $return['reader_note'] = $this->readerNote;
        }

        if (!empty($this->dateRequested)) {
            $return['date_requested'] = $this->dateRequested->format('Y-m-d');
        }

        return $return;
    }

    public function setDocumentAltIds(array $documentAltIds): void
    {
        $this->documentAltIds = $documentAltIds;
    }

    public function setDocIdIn(?string $docIdIn): void
    {
        $this->docIdIn = $docIdIn;
    }

    public function setReaderNote(?string $readerNote): void
    {
        $this->readerNote = $readerNote;
    }

    public function setDocNumberYear(?string $docNumberYear): void
    {
        $this->docNumberYear = $docNumberYear;
    }

    public function setDocNumberPyear(?string $docNumberPyear): void
    {
        $this->docNumberPyear = $docNumberPyear;
    }

    public function setDocNumberPnumber(?string $docNumberPnumber): void
    {
        $this->docNumberPnumber = $docNumberPnumber;
    }

    public function setDocVolume(?string $docVolume): void
    {
        $this->docVolume = $docVolume;
    }

    public function setPagesFrom(?int $pagesFrom): void
    {
        $this->pagesFrom = $pagesFrom;
    }

    public function setPagesTo(?int $pagesTo): void
    {
        $this->pagesTo = $pagesTo;
    }

    public function setDocAuthor(?string $docAuthor): void
    {
        $this->docAuthor = $docAuthor;
    }

    public function setDocIssuer(?string $docIssuer): void
    {
        $this->docIssuer = $docIssuer;
    }

    public function setDocISSN(?string $docISSN): void
    {
        $this->docISSN = $docISSN;
    }

    public function setDocISBN(?string $docISBN): void
    {
        $this->docISBN = $docISBN;
    }

    public function setDocCitation(?string $docCitation): void
    {
        $this->docCitation = $docCitation;
    }

    public function setDocNote(?string $docNote): void
    {
        $this->docNote = $docNote;
    }

    public function setDateRequested(?DateTimeImmutable $dateRequested): void
    {
        $this->dateRequested = $dateRequested;
    }

}
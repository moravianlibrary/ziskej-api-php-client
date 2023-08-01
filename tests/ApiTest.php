<?php

declare(strict_types=1);

namespace Mzk\ZiskejApi;

use DateTimeImmutable;
use DevCoder\DotEnv;
use Http\Adapter\Guzzle7\Client;
use Http\Message\Authentication\Bearer;
use Monolog\Logger;
use Mzk\ZiskejApi\Enum\TicketDataSource;
use Mzk\ZiskejApi\Enum\TicketEddSubtype;
use Mzk\ZiskejApi\Exception\ApiResponseException;
use Mzk\ZiskejApi\ResponseModel\EddEstimate;
use Mzk\ZiskejApi\ResponseModel\LibraryCollection;
use Mzk\ZiskejApi\ResponseModel\MessageCollection;
use Mzk\ZiskejApi\ResponseModel\TicketEdd;
use Mzk\ZiskejApi\ResponseModel\TicketMvs;
use Mzk\ZiskejApi\ResponseModel\TicketCollection;
use Psr\Log\LoggerInterface;

final class ApiTest extends TestCase
{
    /**
     * Test base url
     */
    private string $baseUrl;

    /**
     * Test eppn of active reader
     */
    private string $eppnActive = '1185@mzk.cz';

    /**
     * Test eppn of nonexistent reader
     */
    private string $eppnNotExists = '0@mzk.cz';

    /**
     * Test eppn of dDeactivated reader
     */
    private string $eppnDeactivated = '1184@mzk.cz';

    /**
     * Document id
     */
    private string $docId = 'mzk.MZK01-001579506';

    /**
     * Alternative document ids
     *
     * @var array<string>
     */
    private array $docAltIds = [
        'caslin.SKC01-007434977',
        'nkp.NKC01-002901834',
    ];

    /**
     * MVS ticket ID
     */
    private string $ticketIdMvs = '0b6e062f83f24ad0';

    /**
     * EDD ticket ID
     */
    private string $ticketIdEdd = '03f584fe6f0744d6';

    private string $note = 'This is a note';

    private string $messageText = 'This is my new message';

    private string $date = '2019-12-31';

    /**
     * Test wrong token
     */
    private string $tokenWrong = '';

    /**
     * Logger
     */
    private LoggerInterface $logger;

    protected function setUp(): void
    {
        parent::setUp();

        (new DotEnv(__DIR__ . '/.env'))->load();
        $this->baseUrl = (string) getenv('APP_API_URL');

        $this->logger = new Logger('ZiskejApi');
    }

    /* LIBRARIES */

    /**
     * @throws \Mzk\ZiskejApi\Exception\ApiResponseException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function testApiGetLibrary(): void
    {
        $apiClient = new ApiClient(null, $this->baseUrl, null, $this->logger);
        $api = new Api($apiClient);

        $library = $api->getLibrary('BOA001');

        $this->assertInstanceOf(ResponseModel\Library::class, $library);
    }

    /**
     * @throws \Mzk\ZiskejApi\Exception\ApiResponseException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function testApiGetLibraryNull(): void
    {
        $apiClient = new ApiClient(null, $this->baseUrl, null, $this->logger);
        $api = new Api($apiClient);

        $library = $api->getLibrary('XYZ001');

        $this->assertNull($library);
    }

    /**
     * @throws \Mzk\ZiskejApi\Exception\ApiResponseException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function testApiGetLibrariesAll(): void
    {
        $guzzleClient = Client::createWithConfig([
            'connect_timeout' => 10,
        ]);

        $apiClient = new ApiClient($guzzleClient, $this->baseUrl, null, $this->logger);
        $api = new Api($apiClient);

        $libraryCollection = $api->getLibrariesAll();

        $this->assertInstanceOf(LibraryCollection::class, $libraryCollection);
        $this->assertNotEmpty($libraryCollection->getAll());
    }

    /**
     * @return void
     *
     * @throws \Mzk\ZiskejApi\Exception\ApiResponseException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function testApiGetLibrariesMvsActive(): void
    {
        $guzzleClient = Client::createWithConfig([
            'connect_timeout' => 10,
        ]);

        $apiClient = new ApiClient($guzzleClient, $this->baseUrl, null, $this->logger);
        $api = new Api($apiClient);

        $libraryCollection = $api->getLibrariesMvsActive();

        $this->assertInstanceOf(LibraryCollection::class, $libraryCollection);
        $this->assertNotEmpty($libraryCollection->getAll());
    }

    /**
     * @return void
     * @throws \Mzk\ZiskejApi\Exception\ApiResponseException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function testApiGetLibrariesEddActive(): void
    {
        $guzzleClient = Client::createWithConfig([
            'connect_timeout' => 10,
        ]);

        $apiClient = new ApiClient($guzzleClient, $this->baseUrl, null, $this->logger);
        $api = new Api($apiClient);

        $output = $api->getLibrariesEddActive();

        $this->assertInstanceOf(LibraryCollection::class, $output);
        $this->assertNotEmpty($output->getAll());
    }

    /* READERS */

    /**
     * @throws \Mzk\ZiskejApi\Exception\ApiResponseException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function testApiIsReaderTrue(): void
    {
        $api = ApiFactory::createApi();

        $reader = $api->getReader($this->eppnActive);

        $this->assertInstanceOf(ResponseModel\Reader::class, $reader);

        $this->assertSame(true, $reader->isActive);
    }

    /**
     * @return void
     *
     * @throws \Mzk\ZiskejApi\Exception\ApiResponseException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function testApiIsReaderTrueDeactivated(): void
    {
        $api = ApiFactory::createApi();

        $reader = $api->getReader($this->eppnDeactivated);

        $this->assertInstanceOf(ResponseModel\Reader::class, $reader);

        $this->assertSame(true, $reader->isActive);
    }

    /**
     * @throws \Mzk\ZiskejApi\Exception\ApiResponseException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function testApiIsReaderFalse(): void
    {
        $api = ApiFactory::createApi();

        $reader = $api->getReader($this->eppnNotExists);

        $this->assertNull($reader);
    }

    /**
     * @throws \Mzk\ZiskejApi\Exception\ApiResponseException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function testApiIsReaderActiveTrue(): void
    {
        $api = ApiFactory::createApi();

        $reader = $api->getReader($this->eppnActive);

        $this->assertTrue($reader->isActive);
    }

    /**
     * @throws \Mzk\ZiskejApi\Exception\ApiResponseException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function testApiGetReader200(): void
    {
        $api = ApiFactory::createApi();

        $reader = $api->getReader($this->eppnActive);

        $this->assertInstanceOf(ResponseModel\Reader::class, $reader);

        $this->assertSame(true, $reader->isActive);
    }

    /**
     * @return void
     *
     * @throws \Mzk\ZiskejApi\Exception\ApiResponseException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function testApiGetReader401Unauthorized(): void
    {
        $this->expectException(ApiResponseException::class);
        $this->expectExceptionCode(401);

        $api = new Api(new ApiClient(null, $this->baseUrl, new Bearer($this->tokenWrong), $this->logger));

        $reader = $api->getReader($this->eppnActive);

        $this->assertInstanceOf(ResponseModel\Reader::class, $reader);
    }

    /**
     * @throws \Mzk\ZiskejApi\Exception\ApiResponseException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function testApiGetReader404NotFound(): void
    {
        $api = ApiFactory::createApi();

        $reader = $api->getReader($this->eppnNotExists);

        $this->assertNull($reader);
    }

    /**
     * @throws \Http\Client\Exception
     * @throws \Mzk\ZiskejApi\Exception\ApiResponseException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function testApiCreateReader200(): void
    {
        $api = ApiFactory::createApi();

        $inputReader = new RequestModel\Reader(
            'Jakub',
            'Novák',
            'jakub.novak@example.com',
            'BOA001',
            true,
            true
        );

        $outputReader = $api->createReader($this->eppnActive, $inputReader);

        $this->assertInstanceOf(ResponseModel\Reader::class, $outputReader);
        $this->assertIsString($outputReader->id);

        $this->assertSame($inputReader->email, $outputReader->email);
        $this->assertSame($inputReader->firstName, $outputReader->firstName);
        $this->assertSame($inputReader->lastName, $outputReader->lastName);
        $this->assertSame($inputReader->isGdprData, $outputReader->isGdprData);
        $this->assertSame($inputReader->isGdprReg, $outputReader->isGdprReg);
        $this->assertSame($inputReader->isNotificationEnabled, $outputReader->isNotificationEnabled);
        $this->assertSame($inputReader->sigla, $outputReader->sigla);
    }

    /**
     * @return void
     *
     * @throws \Http\Client\Exception
     * @throws \Mzk\ZiskejApi\Exception\ApiResponseException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function testApiCreateReader422(): void
    {
        $this->expectException(ApiResponseException::class);
        $this->expectExceptionCode(422);

        $api = ApiFactory::createApi();

        $reader = new RequestModel\Reader(
            'Jakub',
            'Novák',
            'jakub.novak@example.com',
            'XXX001',
            true,
            true
        );

        $output = $api->createReader($this->eppnActive, $reader);

        $this->assertIsArray($output);
        $this->assertEmpty($output);
    }

    /**
     * @return void
     *
     * @throws \Http\Client\Exception
     * @throws \Mzk\ZiskejApi\Exception\ApiResponseException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function testApiCreateReader401(): void
    {
        $this->expectException(ApiResponseException::class);
        $this->expectExceptionCode(401);

        $authentication = new Bearer($this->tokenWrong);
        $apiClient = new ApiClient(null, $this->baseUrl, $authentication, $this->logger);
        $api = new Api($apiClient);

        $reader = new RequestModel\Reader(
            'Jakub',
            'Novák',
            'jakub.novak@example.com',
            'BOA001',
            true,
            true
        );

        $api->createReader($this->eppnActive, $reader);
    }

    /**
     * @throws \Http\Client\Exception
     * @throws \Mzk\ZiskejApi\Exception\ApiResponseException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function testApiUpdateReader200(): void
    {
        $api = ApiFactory::createApi();

        $inputReader = new RequestModel\Reader(
            'Jakub',
            'Novák',
            'jakub.novak@example.com',
            'BOA001',
            true,
            true
        );

        $outputReader = $api->updateReader($this->eppnActive, $inputReader);

        $this->assertInstanceOf(ResponseModel\Reader::class, $outputReader);
        $this->assertIsString($outputReader->id);

        $this->assertSame($inputReader->email, $outputReader->email);
        $this->assertSame($inputReader->firstName, $outputReader->firstName);
        $this->assertSame($inputReader->lastName, $outputReader->lastName);
        $this->assertSame($inputReader->isGdprData, $outputReader->isGdprData);
        $this->assertSame($inputReader->isGdprReg, $outputReader->isGdprReg);
        $this->assertSame($inputReader->isNotificationEnabled, $outputReader->isNotificationEnabled);
        $this->assertSame($inputReader->sigla, $outputReader->sigla);
    }

    /**
     * @return void
     *
     * @throws \Http\Client\Exception
     * @throws \Mzk\ZiskejApi\Exception\ApiResponseException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function testApiUpdateReader422(): void
    {
        $this->expectException(ApiResponseException::class);
        $this->expectExceptionCode(422);

        $api = ApiFactory::createApi();

        $reader = new RequestModel\Reader(
            'Jakub',
            'Novák',
            'jakub.novak@example.com',
            'XXX001',
            true,
            true
        );

        $output = $api->updateReader($this->eppnActive, $reader);

        $this->assertIsArray($output);
        $this->assertEmpty($output);
    }

    /**
     * @return void
     *
     * @throws \Http\Client\Exception
     * @throws \Mzk\ZiskejApi\Exception\ApiResponseException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function testApiUpdateReader401(): void
    {
        $this->expectException(ApiResponseException::class);
        $this->expectExceptionCode(401);

        $authentication = new Bearer($this->tokenWrong);
        $apiClient = new ApiClient(null, $this->baseUrl, $authentication, $this->logger);
        $api = new Api($apiClient);

        $reader = new RequestModel\Reader(
            'Jakub',
            'Novák',
            'jakub.novak@example.com',
            'BOA001',
            true,
            true
        );

        $api->updateReader($this->eppnActive, $reader);
    }

    /* TICKETS */

    /**
     * @throws \Mzk\ZiskejApi\Exception\ApiResponseException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function testApiGetTicketsList(): void
    {
        $api = ApiFactory::createApi();

        $output = $api->getTicketsList($this->eppnActive);

        $this->assertIsArray($output);
    }

    /**
     * @throws \Mzk\ZiskejApi\Exception\ApiResponseException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function testApiGetTicketsDetails(): void
    {
        $api = ApiFactory::createApi();

        $output = $api->getTickets($this->eppnActive);

        $this->assertInstanceOf(TicketCollection::class, $output);
    }

    /**
     * @throws \Http\Client\Exception
     * @throws \Mzk\ZiskejApi\Exception\ApiException
     * @throws \Mzk\ZiskejApi\Exception\ApiResponseException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function testApiCreateTicketMvs(): void
    {
        $api = ApiFactory::createApi();

        $ticket = new RequestModel\TicketMvsRequest($this->docId);

        $output = $api->createTicket($this->eppnActive, $ticket);

        $this->assertInstanceOf(TicketMvs::class, $output);
    }

    /**
     * @throws \Http\Client\Exception
     * @throws \Mzk\ZiskejApi\Exception\ApiException
     * @throws \Mzk\ZiskejApi\Exception\ApiResponseException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @throws \Exception
     */
    public function testApiCreateTicketMvsFull(): void
    {
        $api = ApiFactory::createApi();

        $ticket = new RequestModel\TicketMvsRequest(
            $this->docId,
            $this->docAltIds,
            $this->note,
            new DateTimeImmutable($this->date)
        );

        $output = $api->createTicket($this->eppnActive, $ticket);

        $this->assertInstanceOf(TicketMvs::class, $output);
    }

    /**
     * @throws \Http\Client\Exception
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @throws \Mzk\ZiskejApi\Exception\ApiResponseException
     * @throws \Mzk\ZiskejApi\Exception\ApiException
     */
    public function testApiCreateTicketEddAutoArticleMin(): void
    {
        $api = ApiFactory::createApi();

        $ticket = new RequestModel\TicketEddRequest(
            TicketDataSource::AUTO,
            TicketEddSubtype::ARTICLE,
            'Zahrádkář. -- ISSN 0139-7761. -- Roč. 54, č. 1 (2022), s. 4-5',
            'Okrasná zahrada',
            'anl.ANL01-001899088'
        );

        $output = $api->createTicket($this->eppnActive, $ticket);

        $this->assertInstanceOf(TicketEdd::class, $output);
    }

    /**
     * @throws \Http\Client\Exception
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @throws \Mzk\ZiskejApi\Exception\ApiResponseException
     * @throws \Mzk\ZiskejApi\Exception\ApiException
     */
    public function testApiCreateTicketEddAutoArticleFull(): void
    {
        $api = ApiFactory::createApi();

        $ticket = new RequestModel\TicketEddRequest(
            ticketDocDataSource: TicketDataSource::AUTO,
            eddSubtype: TicketEddSubtype::ARTICLE,
            docTitleIn: 'Zahrádkář. -- ISSN 0139-7761. -- Roč. 54, č. 1 (2022), s. 4-5',
            docTitle: 'Okrasná zahrada',
            documentId: 'anl.ANL01-001899088',
            documentAltIds: [],
            docIdIn: null,
            readerNote: 'Zpráva od čtenáře pro knihovníka',
            docNumberYear: '2022',
            docNumberPyear: '2022',
            docNumberPnumber: '1',
            docVolume: null,
            pagesFrom: 1,
            pagesTo: 5,
            docAuthor: 'Josef Černý',
            docIssuer: 'Praha 1969',
            docISSN: 'ISSN 0139-7761',
            docISBN: null,
            docCitation: 'ČERNÝ, Josef. Okrasná zahrada. Zahrádkář. 2022, 54(1), 4-5. ISSN 0139-7761.',
            docNote: 'Poznámka k objednávce',
            dateRequested: new DateTimeImmutable('+3 day')
        );

        $output = $api->createTicket($this->eppnActive, $ticket);

        $this->assertInstanceOf(TicketEdd::class, $output);
    }

    /**
     * @throws \Http\Client\Exception
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @throws \Mzk\ZiskejApi\Exception\ApiResponseException
     * @throws \Mzk\ZiskejApi\Exception\ApiException
     */
    public function testApiCreateTicketEddAutoSelectionMin(): void
    {
        $api = ApiFactory::createApi();

        $ticket = new RequestModel\TicketEddRequest(
            TicketDataSource::AUTO,
            TicketEddSubtype::SELECTION,
            'Reflex : CS - Společenský týdeník',
            'Hula hoop!',
            'vkol.SVK01-000489187'
        );

        $output = $api->createTicket($this->eppnActive, $ticket);

        $this->assertInstanceOf(TicketEdd::class, $output);
    }

    /**
     * @throws \Http\Client\Exception
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @throws \Mzk\ZiskejApi\Exception\ApiResponseException
     * @throws \Mzk\ZiskejApi\Exception\ApiException
     */
    public function testApiCreateTicketEddAutoSelectionFull(): void
    {
        $api = ApiFactory::createApi();

        $ticket = new RequestModel\TicketEddRequest(
            ticketDocDataSource: TicketDataSource::AUTO,
            eddSubtype: TicketEddSubtype::SELECTION,
            docTitleIn: 'Reflex : CS - Společenský týdeník',
            docTitle: 'Hula hoop!',
            documentId: 'vkol.SVK01-000489187',
            documentAltIds: [],
            readerNote: 'Zpráva od čtenáře pro knihovníka',
            docNumberYear: '2022',
            docNumberPyear: '2022',
            docNumberPnumber: '13',
            pagesFrom: 38,
            pagesTo: 40,
            docAuthor: 'Veronika Bednářová',
            docIssuer: 'Praha 2022',
            docISSN: '0862-6634',
            docISBN: '0862-6634',
            docCitation: 'Reflex: CS - Společenský týdeník. Praha: Ringier ČR, 1990-. ISSN 0862-6634.',
            docNote: 'Poznámka k objednávce',
            dateRequested: new DateTimeImmutable('+3 day')
        );

        $output = $api->createTicket($this->eppnActive, $ticket);

        $this->assertInstanceOf(TicketEdd::class, $output);
    }

    /**
     * @throws \Mzk\ZiskejApi\Exception\ApiResponseException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function testApiGetTicketMvs(): void
    {
        $api = ApiFactory::createApi();

        $output = $api->getTicket($this->eppnActive, $this->ticketIdMvs);

        $this->assertInstanceOf(TicketMvs::class, $output);

        $this->assertSame($this->ticketIdMvs, $output->id);
    }

    /**
     * @throws \Mzk\ZiskejApi\Exception\ApiResponseException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function testApiGetTicketEdd(): void
    {
        $api = ApiFactory::createApi();

        $output = $api->getTicket($this->eppnActive, $this->ticketIdEdd);

        $this->assertInstanceOf(TicketEdd::class, $output);

        $this->assertSame($this->ticketIdEdd, $output->id);
    }

    /* MESSAGES */

    /**
     * @throws \Mzk\ZiskejApi\Exception\ApiResponseException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function testApiGetMessages(): void
    {
        $api = ApiFactory::createApi();

        $output = $api->getMessages($this->eppnActive, $this->ticketIdMvs);

        $this->assertInstanceOf(MessageCollection::class, $output);
    }

    /**
     * @throws \Mzk\ZiskejApi\Exception\ApiResponseException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function testApiCreateMessage(): void
    {
        $api = ApiFactory::createApi();

        $message = new RequestModel\Message($this->messageText);

        $output = $api->createMessage($this->eppnActive, $this->ticketIdMvs, $message);

        $this->assertIsBool($output);
        $this->assertSame(true, $output);
    }

    /**
     * @throws \Mzk\ZiskejApi\Exception\ApiResponseException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function testApiReadMessages(): void
    {
        $api = ApiFactory::createApi();

        $messages = new RequestModel\Messages(true);

        $output = $api->updateMessages($this->eppnActive, $this->ticketIdMvs, $messages);

        $this->assertIsBool($output);
        $this->assertSame(true, $output);
    }

    /* ESTIMATE */

    /**
     * @throws \Mzk\ZiskejApi\Exception\ApiResponseException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function testApiGetServiceEddEstimate(): void
    {
        $api = ApiFactory::createApi();

        $output = $api->getEddEstimateFee(10, TicketEddSubtype::ARTICLE);

        $this->assertInstanceOf(EddEstimate::class, $output);
        $this->assertSame(true, $output->isValid);
        $this->assertSame(71.0, $output->fee);
    }
}

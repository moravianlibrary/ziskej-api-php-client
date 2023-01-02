<?php

declare(strict_types=1);

namespace Mzk\ZiskejApi;

use DateTimeImmutable;
use DevCoder\DotEnv;
use Http\Adapter\Guzzle7\Client;
use Http\Message\Authentication\Bearer;
use Monolog\Logger;
use Mzk\ZiskejApi\Enum\TicketEddDocDataSource;
use Mzk\ZiskejApi\Enum\TicketEddSubtype;
use Mzk\ZiskejApi\Exception\ApiResponseException;
use Mzk\ZiskejApi\ResponseModel\EddEstimate;
use Mzk\ZiskejApi\ResponseModel\LibraryCollection;
use Mzk\ZiskejApi\ResponseModel\MessageCollection;
use Mzk\ZiskejApi\ResponseModel\Ticket;
use Mzk\ZiskejApi\ResponseModel\TicketsCollection;
use Psr\Log\LoggerInterface;

final class ApiTest extends TestCase
{
    /**
     * Test base url
     *
     * @var string
     */
    private string $baseUrl;
    /**
     * Test eppn of active reader
     *
     * @var string
     */
    private string $eppnActive = '1185@mzk.cz';

    /**
     * Test eppn of nonexistent reader
     *
     * @var string
     */
    private string $eppnNotExists = '0@mzk.cz';

    /**
     * Test eppn of dDeactivated reader
     *
     * @var string
     */
    private string $eppnDeactivated = '1184@mzk.cz';

    /**
     * Document id
     *
     * @var string
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
     * @var string
     */
    private string $ticketIdMvs = '160455000cf24524';

    /**
     * @var string
     */
    private string $ticketIdEdd = '160455000cf24524';

    /**
     * @var string
     */
    private string $note = 'This is a note';

    /**
     * @var string
     */
    private string $messageText = 'This is my new message';

    /**
     * @var string
     */
    private string $date = '2019-12-31';

    /**
     * Test wrong token
     *
     * @var string
     */
    private string $tokenWrong = '';

    /**
     * Logger
     *
     * @var \Psr\Log\LoggerInterface
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

        $output = $api->getLibrariesAll();

        $this->assertInstanceOf(LibraryCollection::class, $output);
        $this->assertNotEmpty($output->getAll());
    }

    /**
     * @throws \Mzk\ZiskejApi\Exception\ApiResponseException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function testApiGetLibrariesActive(): void
    {
        $guzzleClient = Client::createWithConfig([
            'connect_timeout' => 10,
        ]);

        $apiClient = new ApiClient($guzzleClient, $this->baseUrl, null, $this->logger);
        $api = new Api($apiClient);

        $output = $api->getLibrariesActive();

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

        if ($reader !== null) {
            $this->assertSame(true, $reader->isActive());
        }
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

        if ($reader !== null) {
            $this->assertSame(true, $reader->isActive());
        }
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

        if ($reader) {
            $this->assertTrue($reader->isActive());
        } else {
            $this->assertNull($reader);
        }
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

        if ($reader !== null) {
            $this->assertSame(true, $reader->isActive());
        }
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

//    public function testApiGetReader422DeactivatedReader(): void
//    {
//        $api = ApiFactory::createApi();
//
//        $reader = $api->getReader($this->eppnDeactivated);
//
//        $this->assertInstanceOf(ResponseModel\Reader::class, $reader);
//
//        if ($reader) {
//            $this->assertSame(false, $reader->isActive());
//        }
//    }

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

        if ($outputReader) {
            $this->assertInstanceOf(ResponseModel\Reader::class, $outputReader);
            $this->assertIsString($outputReader->getReaderId());

            $this->assertSame($inputReader->getEmail(), $outputReader->getEmail());
            $this->assertSame($inputReader->getFirstName(), $outputReader->getFirstName());
            $this->assertSame($inputReader->getLastName(), $outputReader->getLastName());
            $this->assertSame($inputReader->isGdprData(), $outputReader->isGdprData());
            $this->assertSame($inputReader->isGdprReg(), $outputReader->isGdprReg());
            $this->assertSame($inputReader->isNotificationEnabled(), $outputReader->isNotificationEnabled());
            $this->assertSame($inputReader->getSigla(), $outputReader->getSigla());
        } else {
            $this->assertNull($outputReader);
        }
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

        $output = $api->createReader($this->eppnActive, $reader);    //@todo

        $this->assertIsArray($output);
        $this->assertEmpty($output);
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

        if ($outputReader) {
            $this->assertInstanceOf(ResponseModel\Reader::class, $outputReader);
            $this->assertIsString($outputReader->getReaderId());

            $this->assertSame($inputReader->getEmail(), $outputReader->getEmail());
            $this->assertSame($inputReader->getFirstName(), $outputReader->getFirstName());
            $this->assertSame($inputReader->getLastName(), $outputReader->getLastName());
            $this->assertSame($inputReader->isGdprData(), $outputReader->isGdprData());
            $this->assertSame($inputReader->isGdprReg(), $outputReader->isGdprReg());
            $this->assertSame($inputReader->isNotificationEnabled(), $outputReader->isNotificationEnabled());
            $this->assertSame($inputReader->getSigla(), $outputReader->getSigla());
        } else {
            $this->assertNull($outputReader);
        }
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

        $output = $api->updateReader($this->eppnActive, $reader);    //@todo

        $this->assertIsArray($output);
        $this->assertEmpty($output);
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

        $this->assertInstanceOf(TicketsCollection::class, $output);
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

        $this->assertInstanceOf(Ticket::class, $output);
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
            new DateTimeImmutable($this->date),
            $this->docAltIds,
            $this->note
        );

        $output = $api->createTicket($this->eppnActive, $ticket);

        $this->assertInstanceOf(Ticket::class, $output);
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
            TicketEddDocDataSource::AUTO,
            TicketEddSubtype::ARTICLE,
            'Zahrádkář. -- ISSN 0139-7761. -- Roč. 54, č. 1 (2022), s. 4-5',
            'Okrasná zahrada',
            'anl.ANL01-001899088'
        );

        $output = $api->createTicket($this->eppnActive, $ticket);

        $this->assertInstanceOf(Ticket::class, $output);
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
            TicketEddDocDataSource::AUTO,
            TicketEddSubtype::ARTICLE,
            'Zahrádkář. -- ISSN 0139-7761. -- Roč. 54, č. 1 (2022), s. 4-5',
            'Okrasná zahrada',
            'anl.ANL01-001899088',
        );
        $ticket->setDocNumberYear('2022');
        $ticket->setDocNumberPyear('2022');
        $ticket->setDocNumberPnumber('1');
        $ticket->setPagesFrom(1);
        $ticket->setPagesTo(5);
        $ticket->setDocAuthor('Josef Černý');
        $ticket->setDocIssuer('Praha 1969');
        $ticket->setDocISSN('ISSN 0139-7761');
        $ticket->setDocCitation('ČERNÝ, Josef. Okrasná zahrada. Zahrádkář. 2022, 54(1), 4-5. ISSN 0139-7761.');
        $ticket->setDocNote('Poznámka k objednávce');
        $ticket->setReaderNote('Zpráva od čtenáře pro knihovníka');
        $ticket->setDateRequested(new DateTimeImmutable('+3 day'));

        $output = $api->createTicket($this->eppnActive, $ticket);

        $this->assertInstanceOf(Ticket::class, $output);
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
            TicketEddDocDataSource::AUTO,
            TicketEddSubtype::SELECTION,
            'Reflex : CS - Společenský týdeník',
            'Hula hoop!',
            'vkol.SVK01-000489187'
        );

        $output = $api->createTicket($this->eppnActive, $ticket);

        $this->assertInstanceOf(Ticket::class, $output);
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
            TicketEddDocDataSource::AUTO,
            TicketEddSubtype::SELECTION,
            'Reflex : CS - Společenský týdeník',
            'Hula hoop!',
            'vkol.SVK01-000489187'
        );
        //$ticket->setDocumentAltIds(); //@todo
        $ticket->setDocNumberYear('2022');
        $ticket->setDocNumberPyear('2022');
        $ticket->setDocNumberPnumber('13');
        $ticket->setDocVolume('13');
        $ticket->setPagesFrom(38);
        $ticket->setPagesTo(40);
        $ticket->setDocAuthor('Veronika Bednářová');
        $ticket->setDocIssuer('Praha 2022');
        $ticket->setDocISSN('0862-6634');
        $ticket->setDocISBN('0862-6634');

        $ticket->setDocCitation('Reflex: CS - Společenský týdeník. Praha: Ringier ČR, 1990-. ISSN 0862-6634.');
        $ticket->setDocNote('Poznámka k objednávce');
        $ticket->setReaderNote('Zpráva od čtenáře pro knihovníka');
        $ticket->setDateRequested(new DateTimeImmutable('+3 day'));

        $output = $api->createTicket($this->eppnActive, $ticket);

        $this->assertInstanceOf(Ticket::class, $output);
    }

    /**
     * @throws \Mzk\ZiskejApi\Exception\ApiResponseException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function testApiGetTicketMvs(): void
    {
        $api = ApiFactory::createApi();

        $output = $api->getTicket($this->eppnActive, $this->ticketIdMvs);

        $this->assertInstanceOf(Ticket::class, $output);

        if ($output !== null) {
            $this->assertSame($this->ticketIdMvs, $output->getId());
        }
    }

    /**
     * @throws \Mzk\ZiskejApi\Exception\ApiResponseException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function testApiGetTicketEdd(): void
    {
        $api = ApiFactory::createApi();

        $output = $api->getTicket($this->eppnActive, $this->ticketIdEdd);

        $this->assertInstanceOf(Ticket::class, $output);

        if ($output !== null) {
            $this->assertSame($this->ticketIdEdd, $output->getId());
        }
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
        $this->assertEquals(true, $output);
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
        $this->assertEquals(true, $output);
    }

    /* ESTIMATE */

    /**
     * @throws \Consistence\Enum\InvalidEnumValueException
     * @throws \Mzk\ZiskejApi\Exception\ApiResponseException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function testApiGetServiceEddEstimate(): void
    {
        $api = ApiFactory::createApi();

        $output = $api->getEddEstimateFee(10, TicketEddSubtype::ARTICLE);

        $this->assertInstanceOf(EddEstimate::class, $output);
        $this->assertEquals(true, $output->isValid());
        $this->assertEquals(60, $output->getFee());
    }
}

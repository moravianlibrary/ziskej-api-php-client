<?php declare(strict_types = 1);

namespace Mzk\ZiskejApi;

use DateTimeImmutable;
use Http\Message\Authentication\Bearer;
use Monolog\Logger;
use Mzk\ZiskejApi\RequestModel\Reader;
use Mzk\ZiskejApi\RequestModel\Ticket;
use Symfony\Component\Dotenv\Dotenv;

final class ApiTest extends TestCase
{

    /**
     * Test reader eppn
     * @var string
     */
    private $eppn = '1185@mzk.cz';

    /**
     * Document id
     * @var string
     */
    private $docId = 'mzk.MZK01-001579506';

    /**
     * Alternative document ids
     * @var string[]
     */
    private $docAltIds = [
        'caslin.SKC01-007434977',
        'nkp.NKC01-002901834',
    ];

    /**
     * @var string
     */
    private $ticketId = 'd2b76fb303764fc9';

    /**
     * @var string
     */
    private $note = 'This is a note';

    /**
     * @var string
     */
    private $date = '2019-12-31';

    /**
     * Test wrong token
     * @var string
     */
    private $tokenWrong = '';

    /**
     * Logger
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    protected function setUp(): void
    {
        parent::setUp();

        $this->logger = new Logger('ZiskejApi');
    }

    /*
     * LOGIN
     */

    public function testApiPostLogin(): void
    {
        $apiClient = new ApiClient(null, $this->logger);
        $api = new Api($apiClient);

        $dotEnv = new Dotenv();
        $dotEnv->load(__DIR__.'/.env');

        $token = $api->login($_ENV['username'], $_ENV['password']);

        $this->assertNotEmpty($token);
        $this->assertIsString($token);
    }

    /*
     * LIBRARIES
     */

    public function testApiGetLibraries(): void
    {
        $apiClient = new ApiClient(null, $this->logger);
        $api = new Api($apiClient);

        $output = $api->getLibraries();

        $this->assertIsArray($output);
        $this->assertNotEmpty($output);
    }

    /*
     * READERS
     */

    public function testApiGetReader200(): void
    {
        $api = ApiFactory::createApi();

        $output = $api->getReader($this->eppn);

        $this->assertIsArray($output);
        $this->assertNotEmpty($output);
        $this->assertCount(9, $output);
    }

    public function testApiGetReader200Advanced(): void
    {
        $api = ApiFactory::createApi();

        $output = $api->getReader($this->eppn, true);

        $this->assertIsArray($output);
        $this->assertNotEmpty($output);
        $this->assertCount(13, $output);
    }

    public function testApiGetReader401(): void
    {
        $this->expectException(\Mzk\ZiskejApi\Exception\ApiResponseException::class);
        $this->expectExceptionCode(401);

        $api = new Api(new ApiClient(new Bearer($this->tokenWrong), $this->logger));

        $output = $api->getReader($this->eppn);

        $this->assertIsArray($output);
        $this->assertEmpty($output);
    }

    public function testApiPutReader200(): void
    {
        $api = ApiFactory::createApi();

        $reader = new Reader(
            'Jakub',
            'Novák',
            'jakub.novak@example.com',
            'BOA001',
            true,
            true
        );

        $output = $api->putReader($this->eppn, $reader);

        $this->assertIsArray($output);
        $this->assertSame($reader->getEmail(), $output['email']);
        $this->assertSame($reader->getFirstName(), $output['first_name']);
        $this->assertSame($reader->getLastName(), $output['last_name']);
        $this->assertSame($reader->isGdprData(), $output['is_gdpr_data']);
        $this->assertSame($reader->isGdprReg(), $output['is_gdpr_reg']);
        $this->assertSame($reader->isNotificationEnabled(), $output['notification_enabled']);
        $this->assertSame($reader->getSigla(), $output['sigla']);
        $this->assertIsString($output['reader_id']);
    }

    public function testApiPutReader422(): void
    {
        $this->expectException(\Mzk\ZiskejApi\Exception\ApiInputException::class);
        $this->expectExceptionCode(422);

        $api = ApiFactory::createApi();

        $reader = new Reader(
            'Jakub',
            'Novák',
            'jakub.novak@example.com',
            'XXX001',
            true,
            true
        );

        $output = $api->putReader($this->eppn, $reader);

        $this->assertIsArray($output);
        $this->assertEmpty($output);
    }

    public function testApiPutReader401(): void
    {
        $this->expectException(\Mzk\ZiskejApi\Exception\ApiResponseException::class);
        $this->expectExceptionCode(401);

        $authentication = new Bearer($this->tokenWrong);
        $apiClient = new ApiClient($authentication, $this->logger);
        $api = new Api($apiClient);

        $reader = new Reader(
            'Jakub',
            'Novák',
            'jakub.novak@example.com',
            'BOA001',
            true,
            true
        );

        $output = $api->putReader($this->eppn, $reader);    //@todo

        $this->assertIsArray($output);
        $this->assertEmpty($output);
    }

    /*
     * TICKETS
     */

    public function testApiGetTicketsList(): void
    {
        $api = ApiFactory::createApi();

        $output = $api->getTicketsList($this->eppn);

        $this->assertIsArray($output);
    }

    public function testApiGetTicketsDetails(): void
    {
        $api = ApiFactory::createApi();

        $output = $api->getTicketsDetails($this->eppn);

        $this->assertIsArray($output);
    }

    public function testApiCreateTicket(): void
    {
        $api = ApiFactory::createApi();

        $ticket = new Ticket(
            $this->eppn,
            $this->docId,
            new DateTimeImmutable($this->date)
        );

        $output = $api->createTicket($ticket);

        $this->assertIsString($output);
    }

    public function testApiCreateTicketFull(): void
    {
        $api = ApiFactory::createApi();

        $ticket = new Ticket(
            $this->eppn,
            $this->docId,
            new DateTimeImmutable($this->date)
        );

        $ticket->setNote($this->note);
        $ticket->setDocumentAltIds($this->docAltIds);

        $output = $api->createTicket($ticket);

        $this->assertIsString($output);
    }

    public function testApiGetTicket(): void
    {
        $api = ApiFactory::createApi();

        $output = $api->getTicket($this->ticketId, $this->eppn);

        $this->assertIsArray($output);
    }

}

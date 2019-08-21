<?php declare(strict_types = 1);

namespace Mzk\ZiskejApi;

use Mzk\ZiskejApi\RequestModel\Reader;
use Mzk\ZiskejApi\RequestModel\Ticket;

final class Api
{

    /**
     * @var \Mzk\ZiskejApi\ApiClient
     */
    private $apiClient;

    public function __construct(ApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    /*
     * LOGIN
     */

    /**
     * Authenticace API and get access token
     * POST /login
     *
     * @param string $username
     * @param string $passeord
     * @return string
     *
     * @throws \Http\Client\Exception
     * @throws \Mzk\ZiskejApi\Exception\ApiException
     * @throws \Mzk\ZiskejApi\Exception\ApiResponseException
     */
    public function login(string $username, string $passeord): string
    {
        $response = $this->apiClient->sendRequest(
            new RequestObject(
                'POST',
                '/login',
                [],
                [],
                [
                    'username' => $username,
                    'password' => $passeord,
                ]
            )
        );

        switch ($response->getStatusCode()) {
            case 200:
                $contents = $response->getBody()->getContents();
                $data = json_decode($contents, true);
                if (empty($data['token'])) {
                    throw new \Mzk\ZiskejApi\Exception\ApiException(
                        'Ziskej API error: API did not return the token key.'
                    );
                }
                $return = $data['token'];
                break;
            default:
                throw new \Mzk\ZiskejApi\Exception\ApiResponseException($response);
                break;
        }

        return $return;
    }

    /*
     * LIBRARIES
     */

    /**
     * List all libraries
     * GET /libraries
     *
     * @return string[]
     *
     * @throws \Http\Client\Exception
     * @throws \Mzk\ZiskejApi\Exception\ApiResponseException
     */
    public function getLibraries(): array
    {
        $response = $this->apiClient->sendRequest(
            new RequestObject(
                'GET',
                '/libraries'
            )
        );

        switch ($response->getStatusCode()) {
            case 200:
                $contents = $response->getBody()->getContents();
                $array = json_decode($contents, true);
                $return = isset($array['items']) || is_array($array['items'])
                    ? $array['items']
                    : [];
                break;
            default:
                throw new \Mzk\ZiskejApi\Exception\ApiResponseException($response);
                break;
        }

        return $return;
    }

    /*
     * READERS
     */

    /**
     * Get reader detail
     * GET /readers/:eppn
     *
     * @param string $eppn
     * @param bool $advanced
     * @return string[]
     *
     * @throws \Http\Client\Exception
     * @throws \Mzk\ZiskejApi\Exception\ApiResponseException
     */
    public function getReader(string $eppn, bool $advanced = false): array
    {
        $response = $this->apiClient->sendRequest(
            new RequestObject(
                'GET',
                '/readers/:eppn',
                [
                    ':eppn' => $eppn,
                ],
                $advanced ? ['expand' => 'status'] : []
            )
        );

        switch ($response->getStatusCode()) {
            case 200:
                $contents = $response->getBody()->getContents();
                $return = json_decode($contents, true);
                break;
            default:
                throw new \Mzk\ZiskejApi\Exception\ApiResponseException($response);
                break;
        }

        return $return;
    }


    /**
     * Create or update reader
     * PUT /readers/:eppn
     *
     * @param string $eppn
     * @param \Mzk\ZiskejApi\RequestModel\Reader $reader
     * @return string[]
     *
     * @throws \Http\Client\Exception
     * @throws \Mzk\ZiskejApi\Exception\ApiInputException
     * @throws \Mzk\ZiskejApi\Exception\ApiResponseException
     */
    public function putReader(string $eppn, Reader $reader): array
    {
        $response = $this->apiClient->sendRequest(
            new RequestObject(
                'PUT',
                '/readers/:eppn',
                [
                    ':eppn' => $eppn,
                ],
                [],
                $reader->toArray()
            )
        );

        switch ($response->getStatusCode()) {
            case 200:
            case 201:
            case 204:
                $contents = $response->getBody()->getContents();
                $return = json_decode($contents, true);
                break;
            case 422:
                // Library is not active
                throw new \Mzk\ZiskejApi\Exception\ApiInputException(
                    sprintf(
                        'Ziskej API input error: Library with sigla "%s" is not active',
                        $reader->getSigla()
                    ),
                    $response->getStatusCode()
                );
            default:
                throw new \Mzk\ZiskejApi\Exception\ApiResponseException($response);
                break;
        }

        return (array)$return;
    }

    /*
     * TICKETS
     */

    /**
     * Get tickets for reader
     * GET /readers/:eppn/tickets
     *
     * @param string $eppn
     * @return string[] List of ticket ids
     *
     * @throws \Http\Client\Exception
     * @throws \Mzk\ZiskejApi\Exception\ApiResponseException
     */
    public function getTicketsList(string $eppn): array
    {
        $response = $this->apiClient->sendRequest(
            new RequestObject(
                'GET',
                '/readers/:eppn/tickets',
                [
                    ':eppn' => $eppn,
                ]
            )
        );

        switch ($response->getStatusCode()) {
            case 200:
                $contents = $response->getBody()->getContents();
                $array = json_decode($contents, true);
                $return = isset($array['items']) || is_array($array['items'])
                    ? $array['items']
                    : [];
                break;
            default:
                throw new \Mzk\ZiskejApi\Exception\ApiResponseException($response);
                break;
        }

        return (array)$return;
    }

    /**
     * Get tickets for reader with details
     * GET /readers/:eppn/tickets
     *
     * @param string $eppn
     * @return string[][] List of tickets with details
     *
     * @throws \Http\Client\Exception
     * @throws \Mzk\ZiskejApi\Exception\ApiResponseException
     */
    public function getTicketsDetails(string $eppn): array
    {
        $response = $this->apiClient->sendRequest(
            new RequestObject(
                'GET',
                '/readers/:eppn/tickets',
                [
                    ':eppn' => $eppn,
                ],
                [
                    'expand' => 'detail',
                ]
            )
        );

        switch ($response->getStatusCode()) {
            case 200:
                $contents = $response->getBody()->getContents();
                $array = json_decode($contents, true);
                $return = isset($array['items']) || is_array($array['items'])
                    ? $array['items']
                    : [];
                break;
            default:
                throw new \Mzk\ZiskejApi\Exception\ApiResponseException($response);
                break;
        }

        return (array)$return;
    }

    /**
     * Create new ticket for reader
     * POST /readers/:eppn/tickets
     *
     * @param string $eppn
     * @param \Mzk\ZiskejApi\RequestModel\Ticket $ticket
     * @return string
     *
     * @throws \Http\Client\Exception
     * @throws \Mzk\ZiskejApi\Exception\ApiException
     * @throws \Mzk\ZiskejApi\Exception\ApiResponseException
     */
    public function createTicket(string $eppn, Ticket $ticket): string
    {
        $response = $this->apiClient->sendRequest(
            new RequestObject(
                'POST',
                '/readers/:eppn/tickets',
                [
                    ':eppn' => $eppn,
                ],
                [],
                $ticket->toArray()
            )
        );

        switch ($response->getStatusCode()) {
            //@todo api should return code 201, but return 200
            case 200:
            case 201:
                $contents = $response->getBody()->getContents();
                $array = json_decode($contents, true);
                if (empty($array['id'])) {
                    throw new \Mzk\ZiskejApi\Exception\ApiException(
                        'Ziskej API error: API did not return "id" parameter.'
                    );
                }
                $return = $array['id'];
                break;
            default:
                throw new \Mzk\ZiskejApi\Exception\ApiResponseException($response);
                break;
        }

        return (string)$return;
    }

    /**
     * Ticket detail
     * GET /readers/:eppn/tickets/:ticket_id
     *
     * @param string $eppn
     * @param string $ticket_id
     * @return string[] Ticket details
     *
     * @throws \Http\Client\Exception
     * @throws \Mzk\ZiskejApi\Exception\ApiResponseException
     */
    public function getTicket(string $eppn, string $ticket_id): array
    {
        $response = $this->apiClient->sendRequest(
            new RequestObject(
                'GET',
                '/readers/:eppn/tickets/:ticket_id',
                [
                    ':eppn' => $eppn,
                    ':ticket_id' => $ticket_id,
                ]
            )
        );

        switch ($response->getStatusCode()) {
            case 200:
                $contents = $response->getBody()->getContents();
                $return = json_decode($contents, true);
                break;
            case 404:
                //@todo ticket not found
            default:
                throw new \Mzk\ZiskejApi\Exception\ApiResponseException($response);
                break;
        }

        return (array)$return;
    }

    /*
     * MESSAGES
     */

    //@todo GET /messages/:ticket_id
    //@todo POST /messages/:ticket_id
    //@todo PUT /messages/:ticket_id/read

}

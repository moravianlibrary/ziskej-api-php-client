<?php

declare(strict_types=1);

namespace Mzk\ZiskejApi;

use Mzk\ZiskejApi\Enum\LibraryServiceType;
use Mzk\ZiskejApi\Enum\TicketEddSubtype;
use Mzk\ZiskejApi\Enum\TicketType;
use Mzk\ZiskejApi\Exception\ApiException;
use Mzk\ZiskejApi\Exception\ApiResponseException;
use Mzk\ZiskejApi\ResponseModel\EddEstimate;
use Mzk\ZiskejApi\ResponseModel\Library;
use Mzk\ZiskejApi\ResponseModel\LibraryCollection;
use Mzk\ZiskejApi\ResponseModel\MessageCollection;
use Mzk\ZiskejApi\ResponseModel\Ticket;
use Mzk\ZiskejApi\ResponseModel\TicketCollection;
use Mzk\ZiskejApi\ResponseModel\TicketEdd;
use Mzk\ZiskejApi\ResponseModel\TicketMvs;

final class Api
{
    /**
     * @var \Mzk\ZiskejApi\ApiClient
     */
    private ApiClient $apiClient;

    public function __construct(ApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    /*
     * LIBRARIES
     */

    /**
     * Get library by sigla
     *
     * @param string $sigla
     *
     * @return \Mzk\ZiskejApi\ResponseModel\Library|null
     *
     * @throws \Mzk\ZiskejApi\Exception\ApiResponseException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function getLibrary(string $sigla): ?Library
    {
        $libraries = $this->getLibrariesAll();
        return $libraries->get($sigla);
    }

    /**
     * List all libraries
     * GET /libraries
     *
     * @return \Mzk\ZiskejApi\ResponseModel\LibraryCollection
     *
     * @throws \Mzk\ZiskejApi\Exception\ApiResponseException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function getLibrariesAll(): LibraryCollection
    {
        $apiResponse = $this->apiClient->sendApiRequest(
            new ApiRequest(
                'GET',
                '/libraries',
                [],
                [
                    'service' => LibraryServiceType::ANY_ZK->value,
                    'include_deactivated' => 1,
                ]
            )
        );

        switch ($apiResponse->getStatusCode()) {
            case 200:
                $contents = $apiResponse->getBody()->getContents();
                $array = json_decode($contents, true);
                if (isset($array['items']) && is_array($array['items'])) {
                    return LibraryCollection::fromArray($array['items']);
                }
                return new LibraryCollection();

            default:
                throw new ApiResponseException($apiResponse);
        }
    }

    /**
     * List all libraries active in Ziskej MVS
     * GET /libraries
     *
     * @return \Mzk\ZiskejApi\ResponseModel\LibraryCollection
     *
     * @throws \Mzk\ZiskejApi\Exception\ApiResponseException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function getLibrariesMvsActive(): LibraryCollection
    {
        $apiResponse = $this->apiClient->sendApiRequest(
            new ApiRequest(
                'GET',
                '/libraries',
                [],
                [
                    'service' => LibraryServiceType::MVS_ZK->value,
                ]
            )
        );

        switch ($apiResponse->getStatusCode()) {
            case 200:
                $contents = $apiResponse->getBody()->getContents();
                $array = json_decode($contents, true);
                if (isset($array['items']) && is_array($array['items'])) {
                    return LibraryCollection::fromArray($array['items']);
                }
                return new LibraryCollection();

            default:
                throw new ApiResponseException($apiResponse);
        }
    }

    /**
     * List all libraries active in Ziskej EDD
     * GET /libraries
     *
     * @return \Mzk\ZiskejApi\ResponseModel\LibraryCollection
     *
     * @throws \Mzk\ZiskejApi\Exception\ApiResponseException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function getLibrariesEddActive(): LibraryCollection
    {
        $apiResponse = $this->apiClient->sendApiRequest(
            new ApiRequest(
                'GET',
                '/libraries',
                [],
                [
                    'service' => LibraryServiceType::EDD_ZK->value,
                ]
            )
        );

        switch ($apiResponse->getStatusCode()) {
            case 200:
                $contents = $apiResponse->getBody()->getContents();
                $array = json_decode($contents, true);
                if (isset($array['items']) && is_array($array['items'])) {
                    return LibraryCollection::fromArray($array['items']);
                }
                return new LibraryCollection();

            default:
                throw new ApiResponseException($apiResponse);
        }
    }

    /*
     * READERS
     */

    /**
     * Get reader detail
     * GET /readers/:eppn
     *
     * @param string $eppn
     *
     * @return \Mzk\ZiskejApi\ResponseModel\Reader|null
     *
     * @throws \Mzk\ZiskejApi\Exception\ApiResponseException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function getReader(string $eppn): ?ResponseModel\Reader
    {
        $apiResponse = $this->apiClient->sendApiRequest(
            new ApiRequest(
                'GET',
                '/readers/:eppn',
                [
                    ':eppn' => $eppn,
                ],
                [
                    'expand' => 'status',
                ]
            )
        );

        switch ($apiResponse->getStatusCode()) {
            case 200:
                $contents = $apiResponse->getBody()->getContents();
                return ResponseModel\Reader::fromArray(json_decode($contents, true));
            case 404:
                return null;
            default:
                throw new ApiResponseException($apiResponse);
        }
    }

    /**
     * Create new reader
     *
     * @param string $eppn
     * @param \Mzk\ZiskejApi\RequestModel\Reader $reader
     *
     * @return \Mzk\ZiskejApi\ResponseModel\Reader|null
     *
     * @throws \Http\Client\Exception
     * @throws \Mzk\ZiskejApi\Exception\ApiResponseException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function createReader(string $eppn, RequestModel\Reader $reader): ?ResponseModel\Reader
    {
        return $this->updateReader($eppn, $reader);
    }

    /**
     * Create or update reader
     * PUT /readers/:eppn
     *
     * @param string $eppn
     * @param \Mzk\ZiskejApi\RequestModel\Reader $reader
     *
     * @return \Mzk\ZiskejApi\ResponseModel\Reader|null
     *
     * @throws \Http\Client\Exception
     * @throws \Mzk\ZiskejApi\Exception\ApiResponseException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function updateReader(string $eppn, RequestModel\Reader $reader): ?ResponseModel\Reader
    {
        $apiResponse = $this->apiClient->sendApiRequest(
            new ApiRequest(
                'PUT',
                '/readers/:eppn',
                [
                    ':eppn' => $eppn,
                ],
                [],
                $reader->toArray()
            )
        );

        switch ($apiResponse->getStatusCode()) {
            case 200:
            case 201:
            case 204:
                return $this->getReader($eppn);
            default:
                throw new ApiResponseException($apiResponse);
        }
    }

    /*
     * TICKETS
     */

    /**
     * Get tickets for reader
     * GET /readers/:eppn/tickets
     *
     * @param string $eppn
     *
     * @return array<string> List of ticket ids
     *
     * @throws \Mzk\ZiskejApi\Exception\ApiResponseException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function getTicketsList(string $eppn): array
    {
        $apiResponse = $this->apiClient->sendApiRequest(
            new ApiRequest(
                'GET',
                '/readers/:eppn/tickets',
                [
                    ':eppn' => $eppn,
                ]
            )
        );

        switch ($apiResponse->getStatusCode()) {
            case 200:
                $contents = $apiResponse->getBody()->getContents();
                $array = json_decode($contents, true);
                $return = isset($array['items']) && is_array($array['items'])
                    ? $array['items']
                    : [];
                break;
            default:
                throw new ApiResponseException($apiResponse);
        }

        return (array) $return;
    }

    /**
     * Get tickets (MVS and EDD) for reader with details
     * GET /readers/:eppn/tickets
     *
     * @param string $eppn
     *
     * @return \Mzk\ZiskejApi\ResponseModel\TicketCollection List of tickets with details
     *
     * @throws \Mzk\ZiskejApi\Exception\ApiResponseException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @throws \Exception
     */
    public function getTickets(string $eppn): TicketCollection
    {
        $apiResponse = $this->apiClient->sendApiRequest(
            new ApiRequest(
                'GET',
                '/readers/:eppn/tickets',
                [
                    ':eppn' => $eppn,
                ],
                [
                    'expand' => 'detail',
                    'include_closed' => 1,
                ]
            )
        );

        switch ($apiResponse->getStatusCode()) {
            case 200:
                $contents = $apiResponse->getBody()->getContents();
                $array = json_decode($contents, true);

                if (isset($array['items']) && is_array($array['items'])) {
                    $tickets = TicketCollection::fromArray($array['items']);
                } else {
                    $tickets = new TicketCollection();
                }
                break;
            default:
                throw new ApiResponseException($apiResponse);
        }

        return $tickets;
    }

    /**
     * Get MVS type tickets for reader with details
     * GET /readers/:eppn/tickets
     *
     * @param string $eppn
     *
     * @return \Mzk\ZiskejApi\ResponseModel\TicketCollection List of tickets with details
     *
     * @throws \Mzk\ZiskejApi\Exception\ApiResponseException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @throws \Exception
     */
    public function getTicketsMvs(string $eppn): TicketCollection
    {
        $apiResponse = $this->apiClient->sendApiRequest(
            new ApiRequest(
                'GET',
                '/readers/:eppn/tickets',
                [
                    ':eppn' => $eppn,
                ],
                [
                    'ticket_type' => TicketType::MVS->value,
                    'expand' => 'detail',
                    'include_closed' => 1,
                ]
            )
        );

        switch ($apiResponse->getStatusCode()) {
            case 200:
                $contents = $apiResponse->getBody()->getContents();
                $array = json_decode($contents, true);

                if (isset($array['items']) && is_array($array['items'])) {
                    $tickets = TicketCollection::fromArray($array['items']);
                } else {
                    $tickets = new TicketCollection();
                }
                break;
            default:
                throw new ApiResponseException($apiResponse);
        }

        return $tickets;
    }

    /**
     * Get EDD type tickets for reader with details
     * GET /readers/:eppn/tickets
     *
     * @param string $eppn
     *
     * @return \Mzk\ZiskejApi\ResponseModel\TicketCollection List of tickets with details
     *
     * @throws \Mzk\ZiskejApi\Exception\ApiResponseException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @throws \Exception
     */
    public function getTicketsEdd(string $eppn): TicketCollection
    {
        $apiResponse = $this->apiClient->sendApiRequest(
            new ApiRequest(
                'GET',
                '/readers/:eppn/tickets',
                [
                    ':eppn' => $eppn,
                ],
                [
                    'ticket_type' => TicketType::EDD->value,
                    'expand' => 'detail',
                    'include_closed' => 1,
                ]
            )
        );

        switch ($apiResponse->getStatusCode()) {
            case 200:
                $contents = $apiResponse->getBody()->getContents();
                $array = json_decode($contents, true);

                if (isset($array['items']) && is_array($array['items'])) {
                    $tickets = TicketCollection::fromArray($array['items']);
                } else {
                    $tickets = new TicketCollection();
                }
                break;
            default:
                throw new ApiResponseException($apiResponse);
        }

        return $tickets;
    }

    /**
     * Create new ticket for reader
     * POST /readers/:eppn/tickets
     *
     * @param string $eppn
     * @param \Mzk\ZiskejApi\RequestModel\TicketRequest $ticket
     *
     * @return \Mzk\ZiskejApi\ResponseModel\Ticket|null Created Ticket or null
     *
     * @throws \Http\Client\Exception
     * @throws \Mzk\ZiskejApi\Exception\ApiException
     * @throws \Mzk\ZiskejApi\Exception\ApiResponseException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function createTicket(string $eppn, RequestModel\TicketRequest $ticket): ?Ticket
    {
        $apiRequest = new ApiRequest(
            'POST',
            '/readers/:eppn/tickets',
            [
                ':eppn' => $eppn,
            ],
            [],
            $ticket->toArray()
        );
        $apiResponse = $this->apiClient->sendApiRequest($apiRequest);

        switch ($apiResponse->getStatusCode()) {
            case 201:
                $contents = $apiResponse->getBody()->getContents();
                $array = json_decode($contents, true);

                if (!isset($array['id'])) {
                    throw new ApiException(
                        'Ziskej API error: Ziskej API response "id" parameter is missing.'
                    );
                }

                return $this->getTicket($eppn, (string) $array['id']);
            default:
                throw new ApiResponseException($apiResponse);
        }
    }

    /**
     * Ticket detail
     * GET /readers/:eppn/tickets/:ticket_id
     *
     * @param string $eppn
     * @param string $ticketId
     *
     * @return \Mzk\ZiskejApi\ResponseModel\Ticket|null Ticket detail data or null
     *
     * @throws \Mzk\ZiskejApi\Exception\ApiResponseException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @throws \Exception
     */
    public function getTicket(string $eppn, string $ticketId): ?Ticket
    {
        $apiResponse = $this->apiClient->sendApiRequest(
            new ApiRequest(
                'GET',
                '/readers/:eppn/tickets/:ticket_id',
                [
                    ':eppn' => $eppn,
                    ':ticket_id' => $ticketId,
                ]
            )
        );

        switch ($apiResponse->getStatusCode()) {
            case 200:
                $contents = $apiResponse->getBody()->getContents();
                $array = json_decode($contents, true);
                return match ($array['ticket_type']) {
                    TicketType::MVS->value => TicketMvs::fromArray($array),
                    TicketType::EDD->value => TicketEdd::fromArray($array),
                    default => null,
                };
            case 404:
                return null;
            default:
                throw new ApiResponseException($apiResponse);
        }
    }

    /**
     * Delete ticket
     * DELETE /readers/:eppn/tickets/:ticket_id
     *
     * @param string $eppn
     * @param string $ticketId
     *
     * @return bool If ticket deleted
     *
     * @throws \Mzk\ZiskejApi\Exception\ApiResponseException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function cancelTicket(string $eppn, string $ticketId): bool
    {
        $apiResponse = $this->apiClient->sendApiRequest(
            new ApiRequest(
                'DELETE',
                '/readers/:eppn/tickets/:ticket_id',
                [
                    ':eppn' => $eppn,
                    ':ticket_id' => $ticketId,
                ]
            )
        );

        switch ($apiResponse->getStatusCode()) {
            case 200:
                return true;
            case 422:
                return false;
            default:
                throw new ApiResponseException($apiResponse);
        }
    }

    /*
     * MESSAGES
     */

    /**
     * Get notes for order
     *
     * @param string $eppn
     * @param string $ticketId
     *
     * @return \Mzk\ZiskejApi\ResponseModel\MessageCollection
     *
     * @throws \Mzk\ZiskejApi\Exception\ApiResponseException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @throws \Exception
     */
    public function getMessages(string $eppn, string $ticketId): MessageCollection
    {
        $apiRequest = new ApiRequest(
            'GET',
            '/readers/:eppn/tickets/:ticket_id/messages',
            [
                ':eppn' => $eppn,
                ':ticket_id' => $ticketId,
            ]
        );
        $apiResponse = $this->apiClient->sendApiRequest($apiRequest);

        switch ($apiResponse->getStatusCode()) {
            case 200:
                $contents = $apiResponse->getBody()->getContents();
                $array = json_decode($contents, true);
                if (isset($array['items']) && is_array($array['items'])) {
                    $collection = MessageCollection::fromArray(array_reverse($array['items'], true));
                } else {
                    $collection = new MessageCollection();
                }
                break;
            default:
                throw new ApiResponseException($apiResponse);
        }

        return $collection;
    }

    /**
     * Create new note to order
     *
     * @param string $eppn
     * @param string $ticketId
     * @param \Mzk\ZiskejApi\RequestModel\Message $message
     *
     * @return bool
     *
     * @throws \Mzk\ZiskejApi\Exception\ApiResponseException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function createMessage(string $eppn, string $ticketId, RequestModel\Message $message): bool
    {
        $apiResponse = $this->apiClient->sendApiRequest(
            new ApiRequest(
                'POST',
                '/readers/:eppn/tickets/:ticket_id/messages',
                [
                    ':eppn' => $eppn,
                    ':ticket_id' => $ticketId,
                ],
                [],
                $message->toArray()
            )
        );

        switch ($apiResponse->getStatusCode()) {
            case 201:
                return true;
            default:
                throw new ApiResponseException($apiResponse);
        }
    }

    /**
     * Set all messages as read
     *
     * @param string $eppn
     * @param string $ticketId
     * @param \Mzk\ZiskejApi\RequestModel\Messages $messages
     *
     * @return bool
     *
     * @throws \Mzk\ZiskejApi\Exception\ApiResponseException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function updateMessages(string $eppn, string $ticketId, RequestModel\Messages $messages): bool
    {
        $apiResponse = $this->apiClient->sendApiRequest(
            new ApiRequest(
                method: 'PUT',
                endpoint: '/readers/:eppn/tickets/:ticket_id/messages',
                urlQuery: [
                    ':eppn' => $eppn,
                    ':ticket_id' => $ticketId,
                ],
                paramsUrl: [],
                paramsData: $messages->toArray()
            )
        );

        switch ($apiResponse->getStatusCode()) {
            case 200:
                return true;
            default:
                throw new ApiResponseException($apiResponse);
        }
    }

    /**
     * Get EDD fee estimate
     *
     * @param int $numberOfPages
     * @param \Mzk\ZiskejApi\Enum\TicketEddSubtype $eddSubtype
     *
     * @return \Mzk\ZiskejApi\ResponseModel\EddEstimate
     *
     * @throws \Mzk\ZiskejApi\Exception\ApiResponseException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function getEddEstimateFee(int $numberOfPages, TicketEddSubtype $eddSubtype): EddEstimate
    {
        $apiRequest = new ApiRequest(
            'GET',
            '/service/edd/estimate',
            [],
            [
                'number_of_pages' => $numberOfPages,
                'edd_subtype' => $eddSubtype->value,
            ]
        );

        $apiResponse = $this->apiClient->sendApiRequest($apiRequest);

        switch ($apiResponse->getStatusCode()) {
            case 200:
                $contents = $apiResponse->getBody()->getContents();
                return EddEstimate::fromArray(json_decode($contents, true));
            default:
                throw new ApiResponseException($apiResponse);
        }
    }
}

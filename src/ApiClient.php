<?php

declare(strict_types=1);

namespace Mzk\ZiskejApi;

use GuzzleHttp\Psr7\Utils;
use Http\Client\Common\Plugin\AuthenticationPlugin;
use Http\Client\Common\Plugin\BaseUriPlugin;
use Http\Client\Common\Plugin\LoggerPlugin;
use Http\Client\Common\PluginClient;
use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Message\Authentication;
use Psr\Log\LoggerInterface;

final class ApiClient
{
    /**
     * Base URI of the client
     *
     * @var string|\Psr\Http\Message\UriInterface|null
     */
    private $baseUri;

    /**
     * @var \Http\Client\HttpClient
     */
    private HttpClient $httpClient;

    /**
     * @var \Http\Message\Authentication|null
     */
    private ?Authentication $authentication;

    /**
     * @var \Psr\Log\LoggerInterface|null
     */
    private ?LoggerInterface $logger;

    /**
     * @var array<mixed>
     */
    private array $plugins = [];

    public function __construct(
        ?HttpClient $httpClient,
        ?string $baseUri,
        ?Authentication $authentication,
        ?LoggerInterface $logger
    ) {
        $this->baseUri = $baseUri;
        $this->authentication = $authentication;
        $this->logger = $logger;

        // set base uri
        if ($this->baseUri) {
            $this->plugins[] = new BaseUriPlugin(Psr17FactoryDiscovery::findUriFactory()->createUri($this->baseUri), [
                'replace' => true,
            ]);
        }

        if ($this->authentication) {
            $this->plugins[] = new AuthenticationPlugin($this->authentication);
        }

        if ($this->logger) {
            $formater = new CustomHttpMessageFormatter();
            $this->plugins[] = new LoggerPlugin($this->logger, $formater);
        }

        $this->httpClient = new PluginClient(
            $httpClient ?? HttpClientDiscovery::find(),
            $this->plugins
        );
    }

    /**
     * Send ApiRequest and get ApiResponse
     *
     * @param \Mzk\ZiskejApi\ApiRequest $requestObject
     *
     * @return \Mzk\ZiskejApi\ApiResponse
     *
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function sendApiRequest(ApiRequest $requestObject): ApiResponse
    {
        $requestFactory = Psr17FactoryDiscovery::findRequestFactory();

        $body = Utils::streamFor(json_encode($requestObject->getParamsData()));

        $request = $requestFactory->createRequest(
            $requestObject->getMethod(),
            $requestObject->getUri()
        )
            ->withHeader('Content-Type', 'application/json')
            ->withBody($body);

        $response = $this->httpClient->sendRequest($request);

        return new ApiResponse($response);
    }
}

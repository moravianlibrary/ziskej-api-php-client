<?php

declare(strict_types=1);

namespace Mzk\ZiskejApi;

use GuzzleHttp\Psr7\Utils;
use Http\Client\Common\Plugin\AuthenticationPlugin;
use Http\Client\Common\Plugin\BaseUriPlugin;
use Http\Client\Common\Plugin\LoggerPlugin;
use Http\Client\Common\PluginClient;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Http\Message\Authentication;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\UriInterface;
use Psr\Log\LoggerInterface;

final class ApiClient
{
    /**
     * Base URI of the client
     *
     * @var string|\Psr\Http\Message\UriInterface|null
     */
    private string|UriInterface|null $baseUri;

    /**
     * @var ClientInterface
     */
    private ClientInterface $httpClient;

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
        ?ClientInterface $httpClient,
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
            $httpClient ?? Psr18ClientDiscovery::find(),
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

        $body = Utils::streamFor(json_encode($requestObject->paramsData));

        $request = $requestFactory->createRequest(
            $requestObject->method,
            $requestObject->getPath()
        )
            ->withHeader('Content-Type', 'application/json')
            ->withBody($body);

        $response = $this->httpClient->sendRequest($request);

        return new ApiResponse($response);
    }
}

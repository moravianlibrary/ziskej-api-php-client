<?php declare(strict_types = 1);

namespace Mzk\ZiskejApi;

class RequestObject
{

    /**
     * @var string
     */
    protected $method;

    /**
     * @var string
     */
    protected $endpoint;

    /**
     * @var string[]
     */
    protected $urlQuery = [];

    /**
     * @var string[]
     */
    protected $paramsUrl = [];

    /**
     * @var string[]
     */
    protected $paramsData = [];

    /**
     * RequestModel constructor.
     * @param string $method
     * @param string $endpoint
     * @param string[] $urlQuery
     * @param string[] $paramsUrl
     * @param string[] $paramsData
     */
    public function __construct(
        string $method,
        string $endpoint,
        array $urlQuery = [],
        array $paramsUrl = [],
        array $paramsData = []
    ) {
        $this->method = $method;
        $this->endpoint = $endpoint;
        $this->urlQuery = $urlQuery;
        $this->paramsUrl = $paramsUrl;
        $this->paramsData = $paramsData;
    }


    public function getMethod(): string
    {
        return $this->method;
    }

    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    /**
     * @return string[]
     */
    public function getUrlQuery(): array
    {
        return $this->urlQuery;
    }

    /**
     * @return string[]
     */
    public function getParamsUrl(): array
    {
        return $this->paramsUrl;
    }

    /**
     * @return string[]
     */
    public function getParamsData(): array
    {
        return $this->paramsData;
    }

}

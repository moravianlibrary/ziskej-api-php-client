<?php

declare(strict_types=1);

namespace Mzk\ZiskejApi;

final class ApiRequest
{
    /**
     * HTTP Method
     *
     * @var string
     */
    private string $method;

    /**
     * URI endpoint
     *
     * @var string
     */
    private string $endpoint;

    /**
     * @var array<string>
     */
    private array $urlQuery;

    /**
     * URL params
     *
     * @var array<string, int|string>
     */
    private array $paramsUrl;

    /**
     * Data params
     *
     * @var array<string>
     */
    private array $paramsData;

    /**
     * RequestModel constructor.
     *
     * @param string $method
     * @param string $endpoint
     * @param array<string> $urlQuery
     * @param array<string, int|string> $paramsUrl
     * @param array<string> $paramsData
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
     * @return array<string>
     */
    public function getParamsData(): array
    {
        return $this->paramsData;
    }

    /**
     * @return string
     */
    public function getUri(): string
    {
        $uri = '';

        if (count($this->urlQuery)) {
            $uri .= $this->render($this->endpoint, $this->urlQuery);
        } else {
            $uri .= $this->endpoint;
        }

        if (count($this->paramsUrl)) {
            $uri .= '?' . http_build_query($this->paramsUrl);
        }

        //@todo create url by using Url object

        return $uri;
    }

    /**
     * @param string $string
     * @param array<string> $replaces
     *
     * @return string
     */
    private function render(string $string, array $replaces): string
    {
        $search = [];
        $replace = [];
        foreach ($replaces as $key => $value) {
            $search[] = $key;
            $replace[] = $value;
        }
        return str_replace($search, $replace, $string);
    }
}

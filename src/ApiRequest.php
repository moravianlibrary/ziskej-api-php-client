<?php

declare(strict_types=1);

namespace Mzk\ZiskejApi;

final class ApiRequest
{
    /**
     * HTTP Method
     */
    public readonly string $method;

    /**
     * URI endpoint
     */
    public readonly string $endpoint;

    /**
     * @var array<string>
     */
    public readonly array $urlQuery;

    /**
     * URL params
     *
     * @var array<string, int|string>
     */
    public readonly array $paramsUrl;

    /**
     * Data params
     *
     * @var array<mixed>
     */
    public readonly array $paramsData;

    /**
     * RequestModel constructor.
     *
     * @param string $method
     * @param string $endpoint
     * @param array<string> $urlQuery
     * @param array<string, int|string> $paramsUrl
     * @param array<mixed> $paramsData
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

    /**
     * @return string
     */
    public function getPath(): string
    {
        $path = '';

        if (count($this->urlQuery)) {
            $path .= $this->render($this->endpoint, $this->urlQuery);
        } else {
            $path .= $this->endpoint;
        }

        if (count($this->paramsUrl)) {
            $path .= '?' . http_build_query($this->paramsUrl);
        }

        return $path;
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

<?php

declare(strict_types=1);

namespace Mzk\ZiskejApi;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

final class ApiResponse
{
    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    private ResponseInterface $response;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    public function getStatusCode(): int
    {
        return $this->response->getStatusCode();
    }

    public function getReasonPhrase(): string
    {
        return $this->response->getReasonPhrase();
    }

    public function getBody(): StreamInterface
    {
        return $this->response->getBody();
    }
}

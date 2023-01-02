<?php

declare(strict_types=1);

namespace Mzk\ZiskejApi\Exception;

use Exception;
use Mzk\ZiskejApi\ApiResponse;

final class ApiResponseException extends Exception
{
    public function __construct(ApiResponse $apiResponse)
    {
        parent::__construct(
            sprintf(
                'Ziskej API response error: "%d %s: %s"',
                $apiResponse->getStatusCode(),
                $apiResponse->getReasonPhrase(),
                (string) $apiResponse->getBody()
            ),
            $apiResponse->getStatusCode(),
            parent::getPrevious()
        );
    }
}

<?php declare(strict_types = 1);

namespace Mzk\ZiskejApi\Exception;

use Psr\Http\Message\ResponseInterface;

class ApiResponseException extends \Exception
{

    public function __construct(ResponseInterface $response)
    {
        parent::__construct(
            sprintf(
                'Ziskej API response error: "%d %s"',
                $response->getStatusCode(),
                $response->getReasonPhrase()
            ),
            $response->getStatusCode(),
            parent::getPrevious()
        );
        //@todo log this exception
    }

}

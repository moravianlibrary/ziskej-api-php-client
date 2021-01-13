<?php declare(strict_types = 1);

namespace Mzk\ZiskejApi;

use Http\Message\Formatter;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * A formatter that prints the HTTP message.
 */
class CustomHttpMessageFormatter implements Formatter
{

    /**
     * The maximum length of the body.
     *
     * @var int|null
     */
    private $maxBodyLength;

    public function __construct(?int $maxBodyLength = 1000)
    {
        $this->maxBodyLength = $maxBodyLength;
    }

    /**
     * {@inheritdoc}
     */
    public function formatRequest(RequestInterface $request)
    {
        $message = sprintf(
            "%s %s HTTP/%s\n",
            $request->getMethod(),
            $request->getRequestTarget(),
            $request->getProtocolVersion()
        );

        foreach ($request->getHeaders() as $name => $values) {
            $message .= $name.': '.implode(', ', $values)."\n";
        }

        return $this->addBody($request, $message);
    }

    /**
     * {@inheritdoc}
     */
    public function formatResponse(ResponseInterface $response)
    {
        $message = sprintf(
            "HTTP/%s %s %s\n",
            $response->getProtocolVersion(),
            $response->getStatusCode(),
            $response->getReasonPhrase()
        );

        foreach ($response->getHeaders() as $name => $values) {
            $message .= $name.': '.implode(', ', $values)."\n";
        }

        return $this->addBody($response, $message);
    }

    /**
     * Add the message body if the stream is seekable.
     *
     * @param \Psr\Http\Message\MessageInterface $request
     * @param string           $message
     *
     * @return string
     */
    private function addBody(MessageInterface $request, string $message): string
    {
        $stream = $request->getBody();
        if (!$stream->isSeekable() || $this->maxBodyLength === 0) {
            // Do not read the stream
            return $message."\n";
        }

        $message = preg_replace('/Authorization: .*/', 'Authorization: [...]', $message);

        if ($this->maxBodyLength === null) {
            $message .= "\n".$stream->__toString();
        } else {
            $message .= "\n".mb_substr($stream->__toString(), 0, $this->maxBodyLength);
        }

        $stream->rewind();

        return $message;
    }

}

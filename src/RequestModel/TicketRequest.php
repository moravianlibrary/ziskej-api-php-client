<?php

declare(strict_types=1);

namespace Mzk\ZiskejApi\RequestModel;

abstract class TicketRequest
{
    /**
     * @return array<string>
     */
    abstract public function toArray(): array;
}

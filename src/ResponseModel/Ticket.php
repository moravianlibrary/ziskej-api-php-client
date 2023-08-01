<?php

declare(strict_types=1);

namespace Mzk\ZiskejApi\ResponseModel;

abstract class Ticket
{
    /**
     * @param array<mixed> $statuses
     *
     * @return array<\Mzk\ZiskejApi\ResponseModel\Status>
     *
     * @throws \Exception
     */
    protected static function setStatusHistory(array $statuses): array
    {
        $return = [];
        foreach ($statuses as $statusHistory) {
            $return[] = Status::fromArray($statusHistory);
        }
        return $return;
    }
}

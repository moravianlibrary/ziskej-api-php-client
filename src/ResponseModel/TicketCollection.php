<?php

declare(strict_types=1);

namespace Mzk\ZiskejApi\ResponseModel;

use SmartEmailing\Types\Arrays;

final class TicketCollection
{
    /**
     * @var array<\Mzk\ZiskejApi\ResponseModel\Ticket>
     */
    private array $items = [];

    /**
     * @param array<array<string>> $data
     *
     * @return \Mzk\ZiskejApi\ResponseModel\TicketCollection
     *
     * @throws \Exception
     */
    public static function fromArray(array $data): TicketCollection
    {
        $self = new self();
        foreach ($data as $subarray) {
            if (Arrays::fromOrNull($subarray, true)) {
                $self->addTicket(TicketMvs::fromArray($subarray));
            }
        }
        return $self;
    }

    public function addTicket(Ticket $ticket): void
    {
        $this->items[] = $ticket;
    }

    /**
     * @return array<\Mzk\ZiskejApi\ResponseModel\Ticket>
     */
    public function getAll(): array
    {
        return $this->items;
    }
}

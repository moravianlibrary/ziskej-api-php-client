<?php

declare(strict_types=1);

namespace Mzk\ZiskejApi\Enum;

enum TicketDataSource: string
{
    case AUTO = 'auto';
    case MANUAL = 'manual';
}

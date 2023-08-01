<?php

declare(strict_types=1);

namespace Mzk\ZiskejApi\Enum;

enum StatusName: string
{
    case CREATED = 'created';
    case PAID = 'paid';
    case UNPAID = 'unpaid';
    case ACCEPTED = 'accepted';
    case PREPARED = 'prepared';
    case LENT = 'lent';
    case CLOSED = 'closed';
    case CANCELLED = 'cancelled';
    case REJECTED = 'rejected';
}

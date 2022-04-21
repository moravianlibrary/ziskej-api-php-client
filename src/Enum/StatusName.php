<?php declare(strict_types = 1);

namespace Mzk\ZiskejApi\Enum;

abstract class StatusName extends BaseEnum
{
    public const CREATED = 'created';
    public const PAID = 'paid';
    public const ACCEPTED = 'accepted';
    public const PREPARED = 'prepared';
    public const LENT = 'lent';
    public const CLOSED = 'closed';
    public const CACNELLED = 'cancelled';
    public const REJECTED = 'rejected';
}

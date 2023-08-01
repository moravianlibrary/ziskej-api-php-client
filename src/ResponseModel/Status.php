<?php

declare(strict_types=1);

namespace Mzk\ZiskejApi\ResponseModel;

use DateTimeImmutable;
use Mzk\ZiskejApi\Enum\StatusName;
use SmartEmailing\Types\StringType;

final class Status
{
    /**
     * @param \DateTimeImmutable $createdAt Status created datetime
     * @param \Mzk\ZiskejApi\Enum\StatusName $statusName Status name
     */
    public function __construct(
        public readonly DateTimeImmutable $createdAt,
        public readonly StatusName $statusName
    ) {
    }

    /**
     * @param array<string> $data
     *
     * @return \Mzk\ZiskejApi\ResponseModel\Status
     *
     * @throws \Exception
     */
    public static function fromArray(array $data): Status
    {
        return new self(
            new DateTimeImmutable(StringType::extract($data, 'date')),
            StatusName::from(StringType::extract($data, 'id'))
        );
    }
}

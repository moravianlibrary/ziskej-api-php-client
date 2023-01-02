<?php

declare(strict_types=1);

namespace Mzk\ZiskejApi\ResponseModel;

use DateTimeImmutable;
use Mzk\ZiskejApi\Enum\StatusName;
use SmartEmailing\Types\StringType;

final class Status
{
    /**
     * Status created datetime
     *
     * @var \DateTimeImmutable
     */
    private DateTimeImmutable $createdAt;

    /**
     * Status name
     *
     * @var string
     *
     * @see \Mzk\ZiskejApi\Enum\StatusName
     */
    private string $name;

    /**
     * @throws \Consistence\Enum\InvalidEnumValueException
     */
    public function __construct(DateTimeImmutable $createdAt, string $name)
    {
        StatusName::checkValue($name);

        $this->createdAt = $createdAt;
        $this->name = $name;
    }

    /**
     * @param array<string> $data
     *
     * @return \Mzk\ZiskejApi\ResponseModel\Status
     *
     * @throws \Consistence\Enum\InvalidEnumValueException
     * @throws \Exception
     */
    public static function fromArray(array $data): Status
    {
        return new self(
            new DateTimeImmutable(StringType::extract($data, 'date')),
            StringType::extract($data, 'id')
        );
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getName(): string
    {
        return $this->name;
    }
}

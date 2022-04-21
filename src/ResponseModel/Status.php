<?php

declare(strict_types=1);

namespace Mzk\ZiskejApi\ResponseModel;

use DateTimeImmutable;
use Mzk\ZiskejApi\Enum\StatusName;
use SmartEmailing\Types\PrimitiveTypes;

class Status
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
     * @param string[] $data
     * @return \Mzk\ZiskejApi\ResponseModel\Status
     *
     * @throws \Consistence\Enum\InvalidEnumValueException
     * @throws \Exception
     */
    public static function fromArray(array $data): Status
    {
        return new self(
            new DateTimeImmutable(PrimitiveTypes::extractString($data, 'date')),
            PrimitiveTypes::extractString($data, 'id')
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

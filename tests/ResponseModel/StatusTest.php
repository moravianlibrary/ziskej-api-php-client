<?php

declare(strict_types=1);

namespace Mzk\ZiskejApi\ResponseModel;

use Consistence\Enum\InvalidEnumValueException;
use Mzk\ZiskejApi\Enum\StatusName;
use Mzk\ZiskejApi\TestCase;

final class StatusTest extends TestCase
{
    /**
     * @var array<string>
     */
    private array $input = [
        'date' => '2020-03-11',
        'id' => StatusName::CREATED,
    ];

    /**
     * @throws \Consistence\Enum\InvalidEnumValueException
     */
    public function testCreateFromArray(): void
    {
        $status = Status::fromArray($this->input);

        $this->assertEquals($this->input['date'], $status->getCreatedAt()->format('Y-m-d'));
        $this->assertEquals($this->input['id'], $status->getName());
    }

    public function testWrongStatusName(): void
    {
        $this->expectException(InvalidEnumValueException::class);

        Status::fromArray([
            'date' => '2020-03-11',
            'id' => 'wrong status name',
        ]);
    }
}

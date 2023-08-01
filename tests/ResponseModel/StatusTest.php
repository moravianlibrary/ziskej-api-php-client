<?php

declare(strict_types=1);

namespace Mzk\ZiskejApi\ResponseModel;

use Mzk\ZiskejApi\TestCase;
use ValueError;

final class StatusTest extends TestCase
{
    /**
     * @var array<string>
     */
    private array $input = [
        'date' => '2020-03-11',
        'id' => 'created',
    ];

    /**
     * @throws \Exception
     */
    public function testCreateFromArray(): void
    {
        $status = Status::fromArray($this->input);

        $this->assertSame($this->input['date'], $status->createdAt->format('Y-m-d'));
        $this->assertSame($this->input['id'], $status->statusName->value);
    }

    public function testWrongStatusName(): void
    {
        $this->expectException(ValueError::class);

        Status::fromArray([
            'date' => '2020-03-11',
            'id' => 'wrong status name',
        ]);
    }
}

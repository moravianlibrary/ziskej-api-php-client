<?php

declare(strict_types=1);

namespace Mzk\ZiskejApi\ResponseModel;

use Mzk\ZiskejApi\TestCase;

final class EddEstimateTest extends TestCase
{
    public function testCreateMin(): void
    {
        $estimate = EddEstimate::fromArray([]);

        $this->assertSame(0.0, $estimate->fee);
        $this->assertSame(0.0, $estimate->feeDk);
        $this->assertSame(0.0, $estimate->feeDilia);
        $this->assertSame(false, $estimate->isValid);
    }

    public function testCreateFull(): void
    {
        $estimate = EddEstimate::fromArray([
            'fee' => 20,
            'fee_dk' => 10,
            'fee_dilia' => 50,
            'is_valid' => true,
        ]);

        $this->assertSame(20.0, $estimate->fee);
        $this->assertSame(10.0, $estimate->feeDk);
        $this->assertSame(50.0, $estimate->feeDilia);
        $this->assertSame(true, $estimate->isValid);
    }
}

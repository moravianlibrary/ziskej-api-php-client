<?php

declare(strict_types=1);

namespace Mzk\ZiskejApi\ResponseModel;

use SmartEmailing\Types\BoolType;
use SmartEmailing\Types\FloatType;

final class EddEstimate
{
    /**
     * @param float $fee Estimated fee
     * @param float $feeDk Estimated fee, part for library (DK).
     * @param float $feeDilia Estimated fee, part for Dília.
     * @param bool $isValid Estimate is valud
     */
    public function __construct(
        public readonly float $fee,
        public readonly float $feeDk,
        public readonly float $feeDilia,
        public readonly bool $isValid
    ) {
    }

    /**
     * @param array<mixed> $data
     *
     * @return \Mzk\ZiskejApi\ResponseModel\EddEstimate
     */
    public static function fromArray(array $data): EddEstimate
    {
        return new self(
            (float) FloatType::extractOrNull($data, 'fee', true),
            (float) FloatType::extractOrNull($data, 'fee_dk'),
            (float) FloatType::extractOrNull($data, 'fee_dilia'),
            (bool) BoolType::extractOrNull($data, 'is_valid')
        );
    }
}

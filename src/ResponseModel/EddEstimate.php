<?php

declare(strict_types=1);

namespace Mzk\ZiskejApi\ResponseModel;

use SmartEmailing\Types\BoolType;
use SmartEmailing\Types\FloatType;

class EddEstimate
{
    /**
     * @var float
     */
    private float $fee;

    /**
     * @var bool
     */
    private bool $isValid;

    /**
     * @param float $fee
     * @param bool $is_valid
     */
    private function __construct(float $fee, bool $is_valid)
    {
        $this->fee = $fee;
        $this->isValid = $is_valid;
    }

    /**
     * @param string[] $data
     * @return \Mzk\ZiskejApi\ResponseModel\EddEstimate
     */
    public static function fromArray(array $data): EddEstimate
    {
        return new self(
            FloatType::extract($data, 'fee'),
            BoolType::extract($data, 'is_valid')
        );
    }

    /**
     * @return float
     */
    public function getFee(): float
    {
        return $this->fee;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->isValid;
    }
}

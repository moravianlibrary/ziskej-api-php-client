<?php

declare(strict_types=1);

namespace Mzk\ZiskejApi\ResponseModel;

final class Library
{
    /**
     * Sigla code
     */
    public readonly string $sigla;

    public function __construct(string $sigla)
    {
        $this->sigla = $sigla;
    }
}

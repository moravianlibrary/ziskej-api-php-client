<?php

declare(strict_types=1);

namespace Mzk\ZiskejApi\ResponseModel;

final class Library
{
    /**
     * Sigla code
     *
     * @var string
     */
    private string $sigla;

    public function __construct(string $sigla)
    {
        $this->sigla = $sigla;
    }

    public function getSigla(): string
    {
        return $this->sigla;
    }
}

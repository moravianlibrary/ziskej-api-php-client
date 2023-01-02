<?php

declare(strict_types=1);

namespace Mzk\ZiskejApi\ResponseModel;

use SmartEmailing\Types\StringType;

final class LibraryCollection
{
    /**
     * @var array<\Mzk\ZiskejApi\ResponseModel\Library>
     */
    private array $items = [];

    /**
     * @param array<array<string>> $data
     *
     * @return \Mzk\ZiskejApi\ResponseModel\LibraryCollection
     */
    public static function fromArray(array $data): LibraryCollection
    {
        $self = new self();
        foreach ($data as $item) {
            $sigla = StringType::fromOrNull($item, true);
            if ($sigla !== null) {
                $self->addLibrary(new Library($sigla));
            }
        }
        return $self;
    }

    public function addLibrary(Library $library): void
    {
        $this->items[$library->getSigla()] = $library;
    }

    /**
     * @return array<\Mzk\ZiskejApi\ResponseModel\Library>
     */
    public function getAll(): array
    {
        return $this->items;
    }

    /**
     * Get library by key
     *
     * @param string $key
     *
     * @return \Mzk\ZiskejApi\ResponseModel\Library|null
     */
    public function get(string $key): ?Library
    {
        return $this->items[$key] ?? null;
    }
}

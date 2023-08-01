<?php

declare(strict_types=1);

namespace Mzk\ZiskejApi\RequestModel;

use Mzk\ZiskejApi\Exception\ApiInputException;
use PHPUnit\Framework\TestCase;

class ReaderTest extends TestCase
{
    /**
     * @var array<mixed>
     */
    private array $dataRequired = [
        'firstName' => 'John',
        'lastName' => 'Doe',
        'email' => 'johndoe@example.com',
        'sigla' => 'ABC',
        'isGdprReg' => true,
        'isGdprData' => true,
    ];

    /**
     * @var array<string>
     */
    private array $dataOptional = [
        'readerLibraryId' => 'ABC123',
    ];

    /**
     * @throws \Mzk\ZiskejApi\Exception\ApiInputException
     */
    public function testCreateReaderMin(): void
    {
        $reader = new Reader(
            $this->dataRequired['firstName'],
            $this->dataRequired['lastName'],
            $this->dataRequired['email'],
            $this->dataRequired['sigla'],
            $this->dataRequired['isGdprReg'],
            $this->dataRequired['isGdprData'],
        );

        $this->assertSame($this->dataRequired['firstName'], $reader->firstName);
        $this->assertSame($this->dataRequired['lastName'], $reader->lastName);
        $this->assertSame($this->dataRequired['email'], $reader->email);
        $this->assertSame($this->dataRequired['sigla'], $reader->sigla);
        $this->assertSame(true, $reader->isNotificationEnabled);
        $this->assertSame($this->dataRequired['isGdprReg'], $reader->isGdprReg);
        $this->assertSame($this->dataRequired['isGdprData'], $reader->isGdprData);
        $this->assertSame(null, $reader->readerLibraryId);

        $this->assertSame([
            'first_name' => $this->dataRequired['firstName'],
            'last_name' => $this->dataRequired['lastName'],
            'email' => $this->dataRequired['email'],
            'sigla' => $this->dataRequired['sigla'],
            'notification_enabled' => true,
            'is_gdpr_reg' => $this->dataRequired['isGdprReg'],
            'is_gdpr_data' => $this->dataRequired['isGdprData'],
            'reader_library_id' => null,
        ], $reader->toArray());
    }

    /**
     * @throws \Mzk\ZiskejApi\Exception\ApiInputException
     */
    public function testCreateReaderFull(): void
    {
        $reader = new Reader(
            $this->dataRequired['firstName'],
            $this->dataRequired['lastName'],
            $this->dataRequired['email'],
            $this->dataRequired['sigla'],
            $this->dataRequired['isGdprReg'],
            $this->dataRequired['isGdprData'],
            $this->dataOptional['readerLibraryId'],
        );

        $this->assertSame($this->dataRequired['firstName'], $reader->firstName);
        $this->assertSame($this->dataRequired['lastName'], $reader->lastName);
        $this->assertSame($this->dataRequired['email'], $reader->email);
        $this->assertSame($this->dataRequired['sigla'], $reader->sigla);
        $this->assertSame(true, $reader->isNotificationEnabled);
        $this->assertSame($this->dataRequired['isGdprReg'], $reader->isGdprReg);
        $this->assertSame($this->dataRequired['isGdprData'], $reader->isGdprData);
        $this->assertSame($this->dataOptional['readerLibraryId'], $reader->readerLibraryId);

        $this->assertSame([
            'first_name' => $this->dataRequired['firstName'],
            'last_name' => $this->dataRequired['lastName'],
            'email' => $this->dataRequired['email'],
            'sigla' => $this->dataRequired['sigla'],
            'notification_enabled' => true,
            'is_gdpr_reg' => $this->dataRequired['isGdprReg'],
            'is_gdpr_data' => $this->dataRequired['isGdprData'],
            'reader_library_id' => $this->dataOptional['readerLibraryId'],
        ], $reader->toArray());
    }

    public function testInvalidEmailFormatThrowsException(): void
    {
        $this->expectException(ApiInputException::class);

        new Reader(
            $this->dataRequired['firstName'],
            $this->dataRequired['lastName'],
            'invalid_email_format', // Invalid email format,
            $this->dataRequired['sigla'],
            $this->dataRequired['isGdprReg'],
            $this->dataRequired['isGdprData'],
            $this->dataOptional['readerLibraryId']
        );
    }
}

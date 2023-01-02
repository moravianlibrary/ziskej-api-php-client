<?php

declare(strict_types=1);

namespace Mzk\ZiskejApi\RequestModel;

use Mzk\ZiskejApi\Exception\ApiInputException;
use SmartEmailing\Types\Emailaddress;

final class Reader
{
    /**
     * Reader first name
     *
     * @var string
     */
    private string $firstName;

    /**
     * Reader last name
     *
     * @var string
     */
    private string $lastName;

    /**
     * Reader email address
     *
     * @var \SmartEmailing\Types\Emailaddress
     */
    private Emailaddress $email;

    /**
     * Library sigla
     *
     * @var string
     */
    private string $sigla;

    /**
     * Send notifications
     *
     * @var bool
     */
    private bool $isNotificationEnabled = true;  // always true

    /**
     * @var bool
     */
    private bool $isGdprReg;

    /**
     * @var bool
     */
    private bool $isGdprData;

    /**
     * @var string|null
     */
    private ?string $readerLibraryId;

    /**
     * Reader constructor.
     *
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @param string $sigla
     * @param bool $isGdprReg
     * @param bool $isGdprData
     * @param string|null $readerLibraryId
     *
     * @throws \Mzk\ZiskejApi\Exception\ApiInputException
     */
    public function __construct(
        string $firstName,
        string $lastName,
        string $email,
        string $sigla,
        bool $isGdprReg,
        bool $isGdprData,
        ?string $readerLibraryId = null
    ) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new ApiInputException('Invalid email format');
        }

        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = Emailaddress::from($email);
        $this->sigla = $sigla;
        $this->isGdprReg = $isGdprReg;
        $this->isGdprData = $isGdprData;
        $this->readerLibraryId = $readerLibraryId;
    }

    /**
     * @return array<mixed>
     */
    public function toArray(): array
    {
        return [
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'email' => $this->email->getValue(),
            'sigla' => $this->sigla,
            'notification_enabled' => $this->isNotificationEnabled,
            'is_gdpr_reg' => $this->isGdprReg,
            'is_gdpr_data' => $this->isGdprData,
            'reader_library_id' => $this->readerLibraryId,
        ];
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getEmail(): string
    {
        return $this->email->getValue();
    }

    public function getSigla(): string
    {
        return $this->sigla;
    }

    public function isNotificationEnabled(): bool
    {
        return $this->isNotificationEnabled;
    }

    public function isGdprReg(): bool
    {
        return $this->isGdprReg;
    }

    public function isGdprData(): bool
    {
        return $this->isGdprData;
    }

    public function getReaderLibraryId(): ?string
    {
        return $this->readerLibraryId;
    }
}

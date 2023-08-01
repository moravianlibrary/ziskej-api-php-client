<?php

declare(strict_types=1);

namespace Mzk\ZiskejApi\RequestModel;

use Mzk\ZiskejApi\Exception\ApiInputException;

final class Reader
{
    /**
     * @param string $firstName Reader first name
     * @param string $lastName Reader last name
     * @param string $email Reader email address
     * @param string $sigla Library sigla
     * @param bool $isGdprReg
     * @param bool $isGdprData
     * @param string|null $readerLibraryId
     * @param bool|null $isNotificationEnabled Send notifications
     *
     * @throws \Mzk\ZiskejApi\Exception\ApiInputException
     */
    public function __construct(
        public readonly string $firstName,
        public readonly string $lastName,
        public readonly string $email,
        public readonly string $sigla,
        public readonly bool $isGdprReg,
        public readonly bool $isGdprData,
        public readonly ?string $readerLibraryId = null,
        public readonly ?bool $isNotificationEnabled = true
    ) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new ApiInputException('Invalid email format');
        }
    }

    /**
     * @return array<mixed>
     */
    public function toArray(): array
    {
        return [
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'email' => $this->email,
            'sigla' => $this->sigla,
            'notification_enabled' => $this->isNotificationEnabled,
            'is_gdpr_reg' => $this->isGdprReg,
            'is_gdpr_data' => $this->isGdprData,
            'reader_library_id' => $this->readerLibraryId,
        ];
    }
}

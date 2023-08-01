<?php

declare(strict_types=1);

namespace Mzk\ZiskejApi\ResponseModel;

use SmartEmailing\Types\BoolType;
use SmartEmailing\Types\IntType;
use SmartEmailing\Types\StringType;

final class Reader
{
    /**
     * Ziskej reader ID
     */
    public readonly string $id;

    /**
     * Active in Ziskej
     */
    public readonly bool $isActive;

    /**
     * Firstname
     */
    public readonly string $firstName;

    /**
     * Lastname
     */
    public readonly string $lastName;

    /**
     * Email address
     */
    public readonly string $email;

    /**
     * Zda posílat notifikace
     */
    public readonly bool $isNotificationEnabled;

    /**
     * Sigla mateřské knihovny
     */
    public readonly string $sigla;

    /**
     * Souhlas s registrací
     */
    public readonly bool $isGdprReg;

    /**
     * Souhlas s uložením dat
     */
    public readonly bool $isGdprData;

    /**
     * Count of tickets
     */
    public readonly int $countTickets;

    /**
     * Count of open tickets
     */
    public readonly int $countTicketsOpen;

    /**
     * Count of messages
     */
    public readonly int $countMessages;

    /**
     * Count of unread messages
     */
    public readonly int $countMessagesUnread;

    public function __construct(
        string $id,
        bool $isActive,
        string $firstName,
        string $lastName,
        string $email,
        bool $isNotificationEnabled,
        string $sigla,
        bool $isGdprReg,
        bool $isGdprData,
        int $countTickets,
        int $countTicketsOpen,
        int $countMessages,
        int $countMessagesUnread
    ) {
        $this->id = $id;
        $this->isActive = $isActive;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->isNotificationEnabled = $isNotificationEnabled;
        $this->sigla = $sigla;
        $this->isGdprReg = $isGdprReg;
        $this->isGdprData = $isGdprData;
        $this->countTickets = $countTickets;
        $this->countTicketsOpen = $countTicketsOpen;
        $this->countMessages = $countMessages;
        $this->countMessagesUnread = $countMessagesUnread;
    }

    /**
     * @param array<mixed> $data
     *
     * @return \Mzk\ZiskejApi\ResponseModel\Reader
     */
    public static function fromArray(array $data): Reader
    {
        return new self(
            id: StringType::extract($data, 'reader_id'),
            isActive: (bool) BoolType::extractOrNull($data, 'is_active', true),
            firstName: (string) StringType::extractOrNull($data, 'first_name', true),
            lastName: (string) StringType::extractOrNull($data, 'last_name', true),
            email: (string) StringType::extractOrNull($data, 'email', true),
            isNotificationEnabled: (bool) BoolType::extractOrNull($data, 'notification_enabled', true),
            sigla: (string) StringType::extractOrNull($data, 'sigla', true),
            isGdprReg: (bool) BoolType::extractOrNull($data, 'is_gdpr_reg'),
            isGdprData: (bool) BoolType::extractOrNull($data, 'is_gdpr_data'),
            countTickets: (int) IntType::extractOrNull($data, 'count_tickets', true),
            countTicketsOpen: (int) IntType::extractOrNull($data, 'count_tickets_open', true),
            countMessages: (int) IntType::extractOrNull($data, 'count_messages', true),
            countMessagesUnread: (int) IntType::extractOrNull($data, 'count_messages_unread', true)
        );
    }
}

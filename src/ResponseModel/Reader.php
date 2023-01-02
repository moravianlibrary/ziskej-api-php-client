<?php

declare(strict_types=1);

namespace Mzk\ZiskejApi\ResponseModel;

use SmartEmailing\Types\BoolType;
use SmartEmailing\Types\Emailaddress;
use SmartEmailing\Types\IntType;
use SmartEmailing\Types\StringType;

final class Reader
{
    /**
     * Ziskej ID
     *
     * @var string
     */
    private string $readerId;

    /**
     * Active in Ziskej
     *
     * @var bool
     */
    private bool $isActive;

    /**
     * Firstname
     *
     * @var string|null
     */
    private ?string $firstName = null;

    /**
     * Lastname
     *
     * @var string|null
     */
    private ?string $lastName = null;

    /**
     * Email address
     *
     * @var \SmartEmailing\Types\Emailaddress|null
     */
    private ?Emailaddress $email = null;

    /**
     * Zda posílat notifikace
     *
     * @var bool|null
     */
    private ?bool $isNotificationEnabled = null;

    /**
     * Sigla mateřské knihovny
     *
     * @var string|null
     */
    private ?string $sigla = null;

    /**
     * Souhlas s registrací
     *
     * @var bool|null
     */
    private ?bool $isGdprReg;

    /**
     * Souhlas s uložením dat
     *
     * @var bool|null
     */
    private ?bool $isGdprData;

    /**
     * Count of tickets
     *
     * @var int|null
     */
    private ?int $countTickets = null;

    /**
     * Count of open tickets
     *
     * @var int|null
     */
    private ?int $countTicketsOpen = null;

    /**
     * Count of messages
     *
     * @var int|null
     */
    private ?int $countMessages = null;

    /**
     * Count of unread messages
     *
     * @var int|null
     */
    private ?int $countMessagesUnread = null;

    public function __construct(
        string $readerId,
        bool $isActive,
        bool $isGdprReg,
        bool $isGdprData
    ) {
        $this->readerId = $readerId;
        $this->isActive = $isActive;
        $this->isGdprReg = $isGdprReg;
        $this->isGdprData = $isGdprData;
    }

    /**
     * @param array<mixed> $data
     *
     * @return \Mzk\ZiskejApi\ResponseModel\Reader
     */
    public static function fromArray(array $data): Reader
    {
        $self = new self(
            StringType::extract($data, 'reader_id'),
            BoolType::extract($data, 'is_active'),
            BoolType::extract($data, 'is_gdpr_reg'),
            BoolType::extract($data, 'is_gdpr_data')
        );

        $self->firstName = StringType::extractOrNull($data, 'first_name', true);
        $self->lastName = StringType::extractOrNull($data, 'last_name', true);
        $self->email = Emailaddress::extractOrNull($data, 'email', true);
        //@todo make not null:
        $self->isNotificationEnabled
            = BoolType::extractOrNull($data, 'notification_enabled', true);
        $self->sigla = StringType::extractOrNull($data, 'sigla', true);
        $self->countTickets = IntType::extractOrNull($data, 'count_tickets', true);
        $self->countTicketsOpen = IntType::extractOrNull($data, 'count_tickets_open', true);
        $self->countMessages = IntType::extractOrNull($data, 'count_messages', true);
        $self->countMessagesUnread
            = IntType::extractOrNull($data, 'count_messages_unread', true);
        return $self;
    }

    public function getReaderId(): string
    {
        return $this->readerId;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function getEmail(): ?string
    {
        return $this->email->getValue();
    }

    public function isNotificationEnabled(): ?bool
    {
        return $this->isNotificationEnabled;
    }

    public function getSigla(): ?string
    {
        return $this->sigla;
    }

    public function isGdprReg(): ?bool
    {
        return $this->isGdprReg;
    }

    public function isGdprData(): ?bool
    {
        return $this->isGdprData;
    }

    public function getCountTickets(): ?int
    {
        return $this->countTickets;
    }

    public function getCountTicketsOpen(): ?int
    {
        return $this->countTicketsOpen;
    }

    public function getCountMessages(): ?int
    {
        return $this->countMessages;
    }

    public function getCountMessagesUnread(): ?int
    {
        return $this->countMessagesUnread;
    }
}

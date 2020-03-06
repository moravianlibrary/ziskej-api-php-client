<?php declare(strict_types = 1);

namespace Mzk\ZiskejApi\ResponseModel;

class MessageCollection
{

    /**
     * @var \Mzk\ZiskejApi\ResponseModel\Message[]
     */
    private $items = [];

    /**
     * @param string[][] $array
     * @return \Mzk\ZiskejApi\ResponseModel\MessageCollection
     */
    public static function fromArray(array $array): MessageCollection
    {
        $self = new self();
        foreach ($array as $subarray) {
            $self->addMessage(Message::fromArray($subarray));
        }
        return $self;
    }

    public function addMessage(Message $message): void
    {
        $this->items[] = $message;
    }

    /**
     * @return \Mzk\ZiskejApi\ResponseModel\Message[]
     */
    public function getAll(): array
    {
        return $this->items;
    }

}

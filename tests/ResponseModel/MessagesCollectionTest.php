<?php declare(strict_types = 1);

namespace Mzk\ZiskejApi\ResponseModel;

use Mzk\ZiskejApi\TestCase;

final class MessagesCollectionTest extends TestCase
{

    /**
     * @var mixed[][]
     */
    private $input = [
        [
            "sender" => "reader",
            "date" => "24.1.2019 7:20",
            "unread" => false,
            "text" => "čistý text bez formátování s novými řádky typu unix",
        ],
        [
            "sender" => "library_zk",
            "date" => "2019-01-24",
            "unread" => true,
            "text" => "Lorem ipsum",
        ],
    ];

    public function testCreateEmptyObject(): void
    {
        $messageCollection = new MessageCollection();
        $message = $messageCollection->getAll();
        $this->assertEquals([], $message);
    }

    public function testCreateFromArray(): void
    {
        $messageCollection = MessageCollection::fromArray($this->input);
        $messages = $messageCollection->getAll();

        $this->assertCount(2, $messages);
        //@todo more tests
    }

}

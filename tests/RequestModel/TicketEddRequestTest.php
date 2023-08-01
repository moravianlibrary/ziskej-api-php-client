<?php

declare(strict_types=1);

namespace Mzk\ZiskejApi\RequestModel;

use Mzk\ZiskejApi\Enum\TicketDataSource;
use Mzk\ZiskejApi\Enum\TicketEddSubtype;
use PHPUnit\Framework\TestCase;

class TicketEddRequestTest extends TestCase
{
    public function testApiTicketEddRequestMin(): void
    {
        $tickerEddRequest = new TicketEddRequest(
            TicketDataSource::AUTO,
            TicketEddSubtype::ARTICLE,
            'Zahrádkář. -- ISSN 0139-7761. -- Roč. 54, č. 1 (2022), s. 4-5',
            'Okrasná zahrada'
        );
    }
}

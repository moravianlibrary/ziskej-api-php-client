<?php

declare(strict_types=1);

namespace Mzk\ZiskejApi\Enum;

/**
 * Explanation
 * ZK = Requesting library (requests a document from requested library)
 * DK = Requested library (sends a document to requesting library)
 */
enum LibraryServiceType: string
{
    /**
     * Libraries in Ziskej MVS and EDD in any role
     */
    case ANY = 'any';

    /**
     * Libraries in Ziskej MVS and EDD in role requesting library
     */
    case ANY_ZK = 'anyzk';

    /**
     * Libraries in Ziskej MVS and EDD in role requested library
     */
    case ANY_DK = 'anydk';

    /**
     * Libraries in Ziskej MVS in any role
     */
    case MVS = 'mvs';

    /**
     * Libraries in Ziskej EDD in any role
     */
    case EDD = 'edd';

    /**
     * Libraries in Ziskej MVS in role requesting library
     */
    case MVS_ZK = 'mvszk';

    /**
     * Libraries in Ziskej MVS in role requested library
     */
    case MVS_DK = 'mvsdk';

    /**
     * Libraries in Ziskej EDD in role requesting library
     */
    case EDD_ZK = 'eddzk';

    /**
     * Libraries in Ziskej EDD in role requested library
     */
    case EDD_DK = 'edddk';
}

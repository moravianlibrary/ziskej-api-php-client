<?php

declare(strict_types=1);

namespace Mzk\ZiskejApi\Enum;

/**
 * Explanation
 * ZK = Requesting library (requests a document from requested library)
 * DK = Requested library (sends a document to requesting library)
 */
abstract class LibraryServiceType extends BaseEnum
{
    /**
     * Libraries in Ziskej MVS and EDD in any role
     */
    public const ANY = 'any';

    /**
     * Libraries in Ziskej MVS and EDD in role requesting library
     */
    public const ANY_ZK = 'anyzk';

    /**
     * Libraries in Ziskej MVS and EDD in role requested library
     */
    public const ANY_DK = 'anydk';

    /**
     * Libraries in Ziskej MVS in any role
     */
    public const MVS = 'mvs';

    /**
     * Libraries in Ziskej EDD in any role
     */
    public const EDD = 'edd';

    /**
     * Libraries in Ziskej MVS in role requesting library
     */
    public const MVS_ZK = 'mvszk';

    /**
     * Libraries in Ziskej MVS in role requested library
     */
    public const MVS_DK = 'mvsdk';

    /**
     * Libraries in Ziskej EDD in role requesting library
     */
    public const EDD_ZK = 'eddzk';

    /**
     * Libraries in Ziskej EDD in role requested library
     */
    public const EDD_DK = 'edddk';
}

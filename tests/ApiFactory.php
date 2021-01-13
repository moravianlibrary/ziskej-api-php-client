<?php declare(strict_types = 1);

namespace Mzk\ZiskejApi;

use Http\Message\Authentication\Bearer;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Ecdsa\Sha512;
use Lcobucci\JWT\Signer\Key;
use Monolog\Logger;

class ApiFactory
{

    public static function createApi(): Api
    {
        $keyFile = __DIR__ . '/../.private/cert-cpk-ziskej-api.key';

        $signer = new Sha512();
        $privateKey = new Key('file://'. $keyFile);
        $time = time();

        $token = (new Builder())
            ->issuedBy('cpk') // Configures the issuer (iss claim)
            ->issuedAt($time) // Configures the time that the token was issue (iat claim)
            ->expiresAt($time + 3600) // Configures the expiration time of the token (exp claim)
            ->withClaim('app', 'cpk')
            ->getToken($signer, $privateKey); // Retrieves the generated token

        //@todo store token

        return new Api(
            new ApiClient(
                null,
                'https://ziskej-test.techlib.cz/api/v1',
                new Bearer((string)$token),
                new Logger('ZiskejApi')
            )
        );
    }

}

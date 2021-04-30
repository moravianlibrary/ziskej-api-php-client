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

        $token = (new Builder())
            ->issuedBy('cpk') // Configures the issuer (iss claim)
            ->issuedAt(new \DateTimeImmutable()) // Configures the time that the token was issue (iat claim)
            ->expiresAt((new \DateTimeImmutable())->setTimestamp(time() + 3600)) // Configures the expiration time of the token (exp claim)
            ->withClaim('app', 'cpk')
            ->getToken($signer, $privateKey); // Retrieves the generated token

        //@todo store token

        return new Api(
            new ApiClient(
                null,
                getenv('APP_API_URL'),
                new Bearer((string)$token),
                new Logger('ZiskejApi')
            )
        );
    }

}

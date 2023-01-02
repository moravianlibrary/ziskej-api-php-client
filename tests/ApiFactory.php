<?php

declare(strict_types=1);

namespace Mzk\ZiskejApi;

use DateTimeImmutable;
use Http\Message\Authentication\Bearer;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Ecdsa\Sha512;
use Lcobucci\JWT\Signer\Key;
use Monolog\Logger;

final class ApiFactory
{
    public static function createApi(): Api
    {
        $keyFile = __DIR__ . '/../.private/cert-cpk-ziskej-api.key';

        $signer = Sha512::create();
        $privateKey = Key\InMemory::file($keyFile);

        $config = Configuration::forSymmetricSigner(
            $signer,
            $privateKey
        );

        $token = $config->builder()
            ->issuedBy('cpk') // Configures the issuer (iss claim)
            ->issuedAt((new DateTimeImmutable())->setTimestamp(time())) // Token issued time
            ->expiresAt((new DateTimeImmutable())->setTimestamp(time() + 3600)) // Token expiration time
            ->withClaim('app', 'cpk')
            ->getToken($signer, $privateKey); // Retrieves the generated token

        //@todo store token

        return new Api(
            new ApiClient(
                null,
                (string) getenv('APP_API_URL'),
                new Bearer($token->toString()),
                new Logger('ZiskejApi')
            )
        );
    }
}

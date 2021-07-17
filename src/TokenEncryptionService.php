<?php

namespace FnService\JWT;

use Exception;
use FnService\Service;
use Jose\Component\Core\AlgorithmManager;
use Jose\Component\Core\JWK;
use Jose\Component\Encryption\Algorithm\ContentEncryption\A128CBCHS256;
use Jose\Component\Encryption\Algorithm\KeyEncryption\A128GCMKW;
use Jose\Component\Encryption\Compression\CompressionMethodManager;
use Jose\Component\Encryption\Compression\Deflate;
use Jose\Component\Encryption\JWEBuilder;
use Jose\Component\Encryption\Serializer\CompactSerializer;

class TokenEncryptionService extends Service
{
    public static function getArrBindNames() : array
    {
        return [];
    }

    public static function getArrCallbacks() : array
    {
        return [];
    }

    public static function getArrLoaders() : array
    {
        return [
            'encrypter' => function () {

                $keyEncryptionAlgorithmManager = new AlgorithmManager([
                    new A128GCMKW,
                ]);
                $contentEncryptionAlgorithmManager = new AlgorithmManager([
                    new A128CBCHS256,
                ]);
                $compressionMethodManager = new CompressionMethodManager([
                    new Deflate,
                ]);

                return new JWEBuilder(
                    $keyEncryptionAlgorithmManager,
                    $contentEncryptionAlgorithmManager,
                    $compressionMethodManager
                );
            },

            'jwk' => function () {

                return new JWK([
                    'alg'
                        => 'A128GCMKW',
                    'use'
                        => 'enc',
                    'kty'
                        => 'oct',
                    'k'
                        => 'I2FeeR3Th6FmhgHN-cxd-9GRRiwcNB2OQzW6vouGFd5hcAwNAu1377hvDmGLKttBitlHiFzk643FyHw4XFM9tdJ90s2zmkX3SsE2KX5B1Qe_sEhqYmWZsJyjeyx-Q0w4B2jX7b39GUybimHUoVHDPTUrgPUKeBf-xVIGJCvHyiE'
                ]);
            },

            'payload' => function () {

                throw new Exception;
            },

            'result' => function ($token) {

                return $token;
            },

            'token' => function ($encrypter, $jwk, $payload) {

                $jwe = $encrypter
                    ->create()
                    ->withPayload(json_encode($payload))
                    ->withSharedProtectedHeader([
                        'alg' => 'A128GCMKW',
                        'enc' => 'A128CBC-HS256',
                        'zip' => 'DEF'
                    ])
                    ->addRecipient($jwk)
                    ->build();

                return (new CompactSerializer)->serialize($jwe, 0);
            },
        ];
    }

    public static function getArrPromiseLists() : array
    {
        return [];
    }

    public static function getArrRuleLists() : array
    {
        return [];
    }

    public static function getArrTraits() : array
    {
        return [];
    }
}
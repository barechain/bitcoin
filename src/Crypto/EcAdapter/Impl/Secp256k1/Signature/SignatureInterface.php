<?php

declare(strict_types=1);

namespace Barechain\Bitcoin\Crypto\EcAdapter\Impl\Secp256k1\Signature;

interface SignatureInterface extends \Barechain\Bitcoin\Crypto\EcAdapter\Signature\SignatureInterface
{
    /**
     * @return resource
     */
    public function getResource();
}

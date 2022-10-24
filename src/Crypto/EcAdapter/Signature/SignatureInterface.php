<?php

declare(strict_types=1);

namespace Barechain\Bitcoin\Crypto\EcAdapter\Signature;

use Barechain\Bitcoin\SerializableInterface;

interface SignatureInterface extends SerializableInterface
{
    /**
     * @param SignatureInterface $signature
     * @return bool
     */
    public function equals(SignatureInterface $signature): bool;
}

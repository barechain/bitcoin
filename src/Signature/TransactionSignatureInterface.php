<?php

declare(strict_types=1);

namespace Barechain\Bitcoin\Signature;

use Barechain\Bitcoin\Crypto\EcAdapter\Signature\SignatureInterface;
use Barechain\Bitcoin\SerializableInterface;

interface TransactionSignatureInterface extends SerializableInterface
{
    /**
     * @return SignatureInterface
     */
    public function getSignature(): SignatureInterface;

    /**
     * @return int
     */
    public function getHashType(): int;

    /**
     * @param TransactionSignatureInterface $other
     * @return bool
     */
    public function equals(TransactionSignatureInterface $other): bool;
}

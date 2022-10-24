<?php

declare(strict_types=1);

namespace Barechain\Bitcoin\Signature;

use BitWasp\Buffertools\BufferInterface;

interface SignatureSortInterface
{
    /**
     * @param \Barechain\Bitcoin\Crypto\EcAdapter\Signature\SignatureInterface[] $signatures
     * @param \Barechain\Bitcoin\Crypto\EcAdapter\Key\PublicKeyInterface[] $publicKeys
     * @param BufferInterface $messageHash
     * @return \SplObjectStorage
     */
    public function link(array $signatures, array $publicKeys, BufferInterface $messageHash): \SplObjectStorage;
}

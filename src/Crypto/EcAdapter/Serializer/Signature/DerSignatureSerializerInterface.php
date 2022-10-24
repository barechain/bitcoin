<?php

declare(strict_types=1);

namespace Barechain\Bitcoin\Crypto\EcAdapter\Serializer\Signature;

use Barechain\Bitcoin\Crypto\EcAdapter\Adapter\EcAdapterInterface;
use Barechain\Bitcoin\Crypto\EcAdapter\Signature\SignatureInterface;
use BitWasp\Buffertools\BufferInterface;

interface DerSignatureSerializerInterface
{
    /**
     * @return EcAdapterInterface
     */
    public function getEcAdapter();

    /**
     * @param SignatureInterface $signature
     * @return BufferInterface
     */
    public function serialize(SignatureInterface $signature): BufferInterface;

    /**
     * @param BufferInterface $derSignature
     * @return SignatureInterface
     */
    public function parse(BufferInterface $derSignature): SignatureInterface;
}

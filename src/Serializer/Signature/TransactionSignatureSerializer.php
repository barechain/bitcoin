<?php

declare(strict_types=1);

namespace Barechain\Bitcoin\Serializer\Signature;

use Barechain\Bitcoin\Crypto\EcAdapter\Serializer\Signature\DerSignatureSerializerInterface;
use Barechain\Bitcoin\Signature\TransactionSignature;
use Barechain\Bitcoin\Signature\TransactionSignatureInterface;
use BitWasp\Buffertools\Buffer;
use BitWasp\Buffertools\BufferInterface;

class TransactionSignatureSerializer
{
    /**
     * @var DerSignatureSerializerInterface
     */
    private $sigSerializer;

    /**
     * @param DerSignatureSerializerInterface $sigSerializer
     */
    public function __construct(DerSignatureSerializerInterface $sigSerializer)
    {
        $this->sigSerializer = $sigSerializer;
    }

    /**
     * @param TransactionSignatureInterface $txSig
     * @return BufferInterface
     */
    public function serialize(TransactionSignatureInterface $txSig): BufferInterface
    {
        return new Buffer($this->sigSerializer->serialize($txSig->getSignature())->getBinary() . pack('C', $txSig->getHashType()));
    }

    /**
     * @param BufferInterface $buffer
     * @return TransactionSignatureInterface
     * @throws \Exception
     */
    public function parse(BufferInterface $buffer): TransactionSignatureInterface
    {
        $adapter = $this->sigSerializer->getEcAdapter();

        if ($buffer->getSize() < 1) {
            throw new \RuntimeException("Empty signature");
        }

        return new TransactionSignature(
            $adapter,
            $this->sigSerializer->parse($buffer->slice(0, -1)),
            (int) $buffer->slice(-1)->getInt()
        );
    }
}

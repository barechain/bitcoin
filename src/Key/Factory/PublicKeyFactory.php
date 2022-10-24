<?php

declare(strict_types=1);

namespace Barechain\Bitcoin\Key\Factory;

use Barechain\Bitcoin\Bitcoin;
use Barechain\Bitcoin\Crypto\EcAdapter\Adapter\EcAdapterInterface;
use Barechain\Bitcoin\Crypto\EcAdapter\EcSerializer;
use Barechain\Bitcoin\Crypto\EcAdapter\Key\PublicKeyInterface;
use Barechain\Bitcoin\Crypto\EcAdapter\Serializer\Key\PublicKeySerializerInterface;
use BitWasp\Buffertools\Buffer;
use BitWasp\Buffertools\BufferInterface;

class PublicKeyFactory
{
    /**
     * @var PublicKeySerializerInterface
     */
    private $serializer;

    /**
     * PublicKeyFactory constructor.
     * @param EcAdapterInterface $ecAdapter
     */
    public function __construct(EcAdapterInterface $ecAdapter = null)
    {
        $ecAdapter = $ecAdapter ?: Bitcoin::getEcAdapter();
        $this->serializer = EcSerializer::getSerializer(PublicKeySerializerInterface::class, true, $ecAdapter);
    }

    /**
     * @param string $hex
     * @return PublicKeyInterface
     * @throws \Exception
     */
    public function fromHex(string $hex): PublicKeyInterface
    {
        return $this->fromBuffer(Buffer::hex($hex));
    }

    /**
     * @param BufferInterface $buffer
     * @return PublicKeyInterface
     */
    public function fromBuffer(BufferInterface $buffer): PublicKeyInterface
    {
        return $this->serializer->parse($buffer);
    }
}

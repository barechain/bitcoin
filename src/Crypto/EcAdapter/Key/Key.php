<?php

declare(strict_types=1);

namespace Barechain\Bitcoin\Crypto\EcAdapter\Key;

use Barechain\Bitcoin\Crypto\EcAdapter\Serializer\Key\PublicKeySerializerInterface;
use Barechain\Bitcoin\Crypto\Hash;
use Barechain\Bitcoin\Serializable;
use BitWasp\Buffertools\BufferInterface;

abstract class Key extends Serializable implements KeyInterface
{
    /**
     * @var BufferInterface
     */
    protected $pubKeyHash;

    /**
     * @return bool
     */
    public function isPrivate(): bool
    {
        return $this instanceof PrivateKeyInterface;
    }

    /**
     * @param PublicKeySerializerInterface|null $serializer
     * @return \BitWasp\Buffertools\BufferInterface
     */
    public function getPubKeyHash(PublicKeySerializerInterface $serializer = null): BufferInterface
    {
        if ($this instanceof PrivateKeyInterface) {
            $publicKey = $this->getPublicKey();
        } else {
            $publicKey = $this;
        }

        if (null === $this->pubKeyHash) {
            $this->pubKeyHash = Hash::sha256ripe160($serializer ? $serializer->serialize($publicKey) : $publicKey->getBuffer());
        }

        return $this->pubKeyHash;
    }
}

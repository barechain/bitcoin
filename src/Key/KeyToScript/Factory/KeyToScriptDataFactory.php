<?php

declare(strict_types=1);

namespace Barechain\Bitcoin\Key\KeyToScript\Factory;

use Barechain\Bitcoin\Crypto\EcAdapter\EcSerializer;
use Barechain\Bitcoin\Crypto\EcAdapter\Key\KeyInterface;
use Barechain\Bitcoin\Crypto\EcAdapter\Key\PrivateKeyInterface;
use Barechain\Bitcoin\Crypto\EcAdapter\Key\PublicKeyInterface;
use Barechain\Bitcoin\Crypto\EcAdapter\Serializer\Key\PublicKeySerializerInterface;
use Barechain\Bitcoin\Key\KeyToScript\ScriptAndSignData;
use Barechain\Bitcoin\Key\KeyToScript\ScriptDataFactory;

abstract class KeyToScriptDataFactory extends ScriptDataFactory
{
    /**
     * @var PublicKeySerializerInterface
     */
    protected $pubKeySerializer;

    /**
     * KeyToP2PKScriptFactory constructor.
     * @param PublicKeySerializerInterface|null $pubKeySerializer
     */
    public function __construct(PublicKeySerializerInterface $pubKeySerializer = null)
    {
        if (null === $pubKeySerializer) {
            $pubKeySerializer = EcSerializer::getSerializer(PublicKeySerializerInterface::class, true);
        }

        $this->pubKeySerializer = $pubKeySerializer;
    }

    /**
     * @param PublicKeyInterface ...$keys
     * @return ScriptAndSignData
     */
    abstract protected function convertKeyToScriptData(PublicKeyInterface ...$keys): ScriptAndSignData;

    /**
     * @param KeyInterface ...$keys
     * @return ScriptAndSignData
     */
    public function convertKey(KeyInterface... $keys): ScriptAndSignData
    {
        $pubs = [];
        foreach ($keys as $key) {
            if ($key instanceof PrivateKeyInterface) {
                $key = $key->getPublicKey();
            }
            $pubs[] = $key;
        }

        return $this->convertKeyToScriptData(...$pubs);
    }
}

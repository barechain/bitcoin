<?php

declare(strict_types=1);

namespace Barechain\Bitcoin\Key\KeyToScript;

use Barechain\Bitcoin\Crypto\EcAdapter\Adapter\EcAdapterInterface;
use Barechain\Bitcoin\Crypto\EcAdapter\EcSerializer;
use Barechain\Bitcoin\Crypto\EcAdapter\Serializer\Key\PublicKeySerializerInterface;
use Barechain\Bitcoin\Key\KeyToScript\Decorator\P2shP2wshScriptDecorator;
use Barechain\Bitcoin\Key\KeyToScript\Decorator\P2shScriptDecorator;
use Barechain\Bitcoin\Key\KeyToScript\Decorator\P2wshScriptDecorator;
use Barechain\Bitcoin\Key\KeyToScript\Factory\KeyToScriptDataFactory;
use Barechain\Bitcoin\Key\KeyToScript\Factory\MultisigScriptDataFactory;
use Barechain\Bitcoin\Key\KeyToScript\Factory\P2pkhScriptDataFactory;
use Barechain\Bitcoin\Key\KeyToScript\Factory\P2wpkhScriptDataFactory;

class KeyToScriptHelper
{
    /**
     * @var PublicKeySerializerInterface
     */
    private $pubKeySer;

    /**
     * Slip132PrefixRegistry constructor.
     * @param EcAdapterInterface $ecAdapter
     */
    public function __construct(EcAdapterInterface $ecAdapter)
    {
        $this->pubKeySer = EcSerializer::getSerializer(PublicKeySerializerInterface::class, true, $ecAdapter);
    }

    /**
     * @return P2pkhScriptDataFactory
     */
    public function getP2pkhFactory(): P2pkhScriptDataFactory
    {
        return new P2pkhScriptDataFactory($this->pubKeySer);
    }

    /**
     * @param int $numSignatures
     * @param int $numKeys
     * @param bool $sortCosignKeys
     * @return MultisigScriptDataFactory
     */
    public function getMultisigFactory(int $numSignatures, int $numKeys, bool $sortCosignKeys): MultisigScriptDataFactory
    {
        return new MultisigScriptDataFactory($numSignatures, $numKeys, $sortCosignKeys, $this->pubKeySer);
    }

    /**
     * @return P2wpkhScriptDataFactory
     */
    public function getP2wpkhFactory(): P2wpkhScriptDataFactory
    {
        return new P2wpkhScriptDataFactory($this->pubKeySer);
    }

    /**
     * @param KeyToScriptDataFactory $scriptFactory
     * @return ScriptDataFactory
     * @throws \Barechain\Bitcoin\Exceptions\DisallowedScriptDataFactoryException
     */
    public function getP2shFactory(KeyToScriptDataFactory $scriptFactory): ScriptDataFactory
    {
        return new P2shScriptDecorator($scriptFactory);
    }

    /**
     * @param KeyToScriptDataFactory $scriptFactory
     * @return ScriptDataFactory
     * @throws \Barechain\Bitcoin\Exceptions\DisallowedScriptDataFactoryException
     */
    public function getP2wshFactory(KeyToScriptDataFactory $scriptFactory): ScriptDataFactory
    {
        return new P2wshScriptDecorator($scriptFactory);
    }

    /**
     * @param KeyToScriptDataFactory $scriptFactory
     * @return ScriptDataFactory
     * @throws \Barechain\Bitcoin\Exceptions\DisallowedScriptDataFactoryException
     */
    public function getP2shP2wshFactory(KeyToScriptDataFactory $scriptFactory): ScriptDataFactory
    {
        return new P2shP2wshScriptDecorator($scriptFactory);
    }
}

<?php

declare(strict_types=1);

namespace Barechain\Bitcoin\Key\Factory;

use Barechain\Bitcoin\Bitcoin;
use Barechain\Bitcoin\Crypto\EcAdapter\Adapter\EcAdapterInterface;
use Barechain\Bitcoin\Crypto\EcAdapter\EcSerializer;
use Barechain\Bitcoin\Crypto\EcAdapter\Serializer\Key\PublicKeySerializerInterface;
use Barechain\Bitcoin\Crypto\Hash;
use Barechain\Bitcoin\Crypto\Random\Random;
use Barechain\Bitcoin\Key\Deterministic\HierarchicalKey;
use Barechain\Bitcoin\Key\Deterministic\MultisigHD;
use Barechain\Bitcoin\Key\KeyToScript\Factory\P2pkhScriptDataFactory;
use Barechain\Bitcoin\Key\KeyToScript\ScriptDataFactory;
use Barechain\Bitcoin\Network\NetworkInterface;
use Barechain\Bitcoin\Serializer\Key\HierarchicalKey\Base58ExtendedKeySerializer;
use Barechain\Bitcoin\Serializer\Key\HierarchicalKey\ExtendedKeySerializer;
use BitWasp\Buffertools\Buffer;
use BitWasp\Buffertools\BufferInterface;

class HierarchicalKeyFactory
{
    /**
     * @var EcAdapterInterface
     */
    private $adapter;

    /**
     * @var Base58ExtendedKeySerializer
     */
    private $serializer;

    /**
     * @var PrivateKeyFactory
     */
    private $privFactory;

    /**
     * HierarchicalKeyFactory constructor.
     * @param EcAdapterInterface|null $ecAdapter
     * @param Base58ExtendedKeySerializer|null $serializer
     * @throws \Exception
     */
    public function __construct(EcAdapterInterface $ecAdapter = null, Base58ExtendedKeySerializer $serializer = null)
    {
        $this->adapter = $ecAdapter ?: Bitcoin::getEcAdapter();
        $this->privFactory = new PrivateKeyFactory($this->adapter);
        $this->serializer = $serializer ?: new Base58ExtendedKeySerializer(
            new ExtendedKeySerializer($this->adapter)
        );
    }

    /**
     * @param Random $random
     * @param ScriptDataFactory|null $scriptDataFactory
     * @return HierarchicalKey
     * @throws \Barechain\Bitcoin\Exceptions\RandomBytesFailure
     * @throws \Exception
     */
    public function generateMasterKey(Random $random, ScriptDataFactory $scriptDataFactory = null): HierarchicalKey
    {
        return $this->fromEntropy(
            $random->bytes(64),
            $scriptDataFactory
        );
    }

    /**
     * @param BufferInterface $entropy
     * @param ScriptDataFactory|null $scriptFactory
     * @return HierarchicalKey
     * @throws \Exception
     */
    public function fromEntropy(BufferInterface $entropy, ScriptDataFactory $scriptFactory = null): HierarchicalKey
    {
        $seed = Hash::hmac('sha512', $entropy, new Buffer('Bitcoin seed'));
        $privSecret = $seed->slice(0, 32);
        $chainCode = $seed->slice(32, 32);
        $scriptFactory = $scriptFactory ?: new P2pkhScriptDataFactory(EcSerializer::getSerializer(PublicKeySerializerInterface::class, true, $this->adapter));
        return new HierarchicalKey($this->adapter, $scriptFactory, 0, 0, 0, $chainCode, $this->privFactory->fromBufferCompressed($privSecret));
    }

    /**
     * @param string $extendedKey
     * @param NetworkInterface|null $network
     * @return HierarchicalKey
     * @throws \Barechain\Bitcoin\Exceptions\Base58ChecksumFailure
     * @throws \BitWasp\Buffertools\Exceptions\ParserOutOfRange
     */
    public function fromExtended(string $extendedKey, NetworkInterface $network = null): HierarchicalKey
    {
        return $this->serializer->parse($network ?: Bitcoin::getNetwork(), $extendedKey);
    }

    /**
     * @param ScriptDataFactory $scriptFactory
     * @param HierarchicalKey ...$keys
     * @return MultisigHD
     */
    public function multisig(ScriptDataFactory $scriptFactory, HierarchicalKey ...$keys): MultisigHD
    {
        return new MultisigHD($scriptFactory, ...$keys);
    }
}

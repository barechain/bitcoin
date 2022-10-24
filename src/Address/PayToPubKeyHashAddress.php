<?php

declare(strict_types=1);

namespace Barechain\Bitcoin\Address;

use Barechain\Bitcoin\Bitcoin;
use Barechain\Bitcoin\Network\NetworkInterface;
use Barechain\Bitcoin\Script\ScriptFactory;
use Barechain\Bitcoin\Script\ScriptInterface;
use BitWasp\Buffertools\BufferInterface;

class PayToPubKeyHashAddress extends Base58Address
{
    /**
     * PayToPubKeyHashAddress constructor.
     * @param BufferInterface $data
     */
    public function __construct(BufferInterface $data)
    {
        if ($data->getSize() !== 20) {
            throw new \RuntimeException("P2PKH address hash should be 20 bytes");
        }

        parent::__construct($data);
    }

    /**
     * @param NetworkInterface $network
     * @return string
     */
    public function getPrefixByte(NetworkInterface $network = null): string
    {
        $network = $network ?: Bitcoin::getNetwork();
        return pack("H*", $network->getAddressByte());
    }

    /**
     * @return ScriptInterface
     */
    public function getScriptPubKey(): ScriptInterface
    {
        return ScriptFactory::scriptPubKey()->payToPubKeyHash($this->getHash());
    }
}

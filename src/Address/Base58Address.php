<?php

declare(strict_types=1);

namespace Barechain\Bitcoin\Address;

use Barechain\Bitcoin\Base58;
use Barechain\Bitcoin\Bitcoin;
use Barechain\Bitcoin\Network\NetworkInterface;
use BitWasp\Buffertools\Buffer;

abstract class Base58Address extends Address implements Base58AddressInterface
{
    /**
     * @param NetworkInterface|null $network
     * @return string
     */
    public function getAddress(NetworkInterface $network = null): string
    {
        $network = $network ?: Bitcoin::getNetwork();
        $payload = new Buffer($this->getPrefixByte($network) . $this->getHash()->getBinary());
        return Base58::encodeCheck($payload);
    }
}

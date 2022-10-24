<?php

declare(strict_types=1);

namespace Barechain\Bitcoin\Address;

use Barechain\Bitcoin\Network\NetworkInterface;
use Barechain\Bitcoin\Script\ScriptInterface;

abstract class BaseAddressCreator
{
    /**
     * @param string $strAddress
     * @param NetworkInterface|null $network
     * @return Address
     */
    abstract public function fromString(string $strAddress, NetworkInterface $network = null): Address;

    /**
     * @param ScriptInterface $script
     * @return Address
     */
    abstract public function fromOutputScript(ScriptInterface $script): Address;
}

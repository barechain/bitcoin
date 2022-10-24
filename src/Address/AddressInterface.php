<?php

declare(strict_types=1);

namespace Barechain\Bitcoin\Address;

use Barechain\Bitcoin\Network\NetworkInterface;
use Barechain\Bitcoin\Script\ScriptInterface;
use BitWasp\Buffertools\BufferInterface;

interface AddressInterface
{
    /**
     * @param NetworkInterface $network
     * @return string
     */
    public function getAddress(NetworkInterface $network = null): string;

    /**
     * @return BufferInterface
     */
    public function getHash(): BufferInterface;

    /**
     * @return ScriptInterface
     */
    public function getScriptPubKey(): ScriptInterface;
}

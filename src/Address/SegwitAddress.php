<?php

declare(strict_types=1);

namespace Barechain\Bitcoin\Address;

use Barechain\Bitcoin\Bitcoin;
use Barechain\Bitcoin\Network\NetworkInterface;
use Barechain\Bitcoin\Script\ScriptInterface;
use Barechain\Bitcoin\Script\WitnessProgram;

class SegwitAddress extends Address implements Bech32AddressInterface
{
    /**
     * @var WitnessProgram
     */
    protected $witnessProgram;

    /**
     * SegwitAddress constructor.
     * @param WitnessProgram $witnessProgram
     */
    public function __construct(WitnessProgram $witnessProgram)
    {
        $this->witnessProgram = $witnessProgram;

        parent::__construct($witnessProgram->getProgram());
    }

    /**
     * @param NetworkInterface|null $network
     * @return string
     */
    public function getHRP(NetworkInterface $network = null): string
    {
        $network = $network ?: Bitcoin::getNetwork();
        return $network->getSegwitBech32Prefix();
    }

    /**
     * @return WitnessProgram
     */
    public function getWitnessProgram(): WitnessProgram
    {
        return $this->witnessProgram;
    }

    /**
     * @return ScriptInterface
     */
    public function getScriptPubKey(): ScriptInterface
    {
        return $this->witnessProgram->getScript();
    }

    /**
     * @param NetworkInterface|null $network
     * @return string
     */
    public function getAddress(NetworkInterface $network = null): string
    {
        $network = $network ?: Bitcoin::getNetwork();

        return \BitWasp\Bech32\encodeSegwit($network->getSegwitBech32Prefix(), $this->witnessProgram->getVersion(), $this->witnessProgram->getProgram()->getBinary());
    }
}

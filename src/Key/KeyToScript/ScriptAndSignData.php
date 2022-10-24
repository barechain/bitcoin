<?php

declare(strict_types=1);

namespace Barechain\Bitcoin\Key\KeyToScript;

use Barechain\Bitcoin\Address\Address;
use Barechain\Bitcoin\Address\BaseAddressCreator;
use Barechain\Bitcoin\Script\ScriptInterface;
use Barechain\Bitcoin\Transaction\Factory\SignData;

class ScriptAndSignData
{
    /**
     * @var ScriptInterface
     */
    private $scriptPubKey;

    /**
     * @var SignData
     */
    private $signData;

    /**
     * ScriptAndSignData constructor.
     * @param ScriptInterface $scriptPubKey
     * @param SignData $signData
     */
    public function __construct(ScriptInterface $scriptPubKey, SignData $signData)
    {
        $this->scriptPubKey = $scriptPubKey;
        $this->signData = $signData;
    }

    /**
     * @return ScriptInterface
     */
    public function getScriptPubKey(): ScriptInterface
    {
        return $this->scriptPubKey;
    }

    /**
     * @param BaseAddressCreator $creator
     * @return Address
     */
    public function getAddress(BaseAddressCreator $creator): Address
    {
        return $creator->fromOutputScript($this->scriptPubKey);
    }

    /**
     * @return SignData
     */
    public function getSignData(): SignData
    {
        return $this->signData;
    }
}

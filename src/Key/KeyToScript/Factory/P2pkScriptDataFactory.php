<?php

declare(strict_types=1);

namespace Barechain\Bitcoin\Key\KeyToScript\Factory;

use Barechain\Bitcoin\Crypto\EcAdapter\Key\PublicKeyInterface;
use Barechain\Bitcoin\Key\KeyToScript\ScriptAndSignData;
use Barechain\Bitcoin\Script\ScriptFactory;
use Barechain\Bitcoin\Script\ScriptType;
use Barechain\Bitcoin\Transaction\Factory\SignData;

class P2pkScriptDataFactory extends KeyToScriptDataFactory
{
    /**
     * @return string
     */
    public function getScriptType(): string
    {
        return ScriptType::P2PK;
    }

    /**
     * @param PublicKeyInterface ...$keys
     * @return ScriptAndSignData
     */
    protected function convertKeyToScriptData(PublicKeyInterface ...$keys): ScriptAndSignData
    {
        if (count($keys) !== 1) {
            throw new \InvalidArgumentException("Invalid number of keys");
        }
        return new ScriptAndSignData(
            ScriptFactory::scriptPubKey()->p2pk($keys[0]),
            new SignData()
        );
    }
}

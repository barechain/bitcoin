<?php

declare(strict_types=1);

namespace Barechain\Bitcoin\Key\KeyToScript\Decorator;

use Barechain\Bitcoin\Crypto\EcAdapter\Key\KeyInterface;
use Barechain\Bitcoin\Key\KeyToScript\ScriptAndSignData;
use Barechain\Bitcoin\Script\ScriptType;
use Barechain\Bitcoin\Script\WitnessScript;
use Barechain\Bitcoin\Transaction\Factory\SignData;

class P2wshScriptDecorator extends ScriptHashDecorator
{
    /**
     * @var array
     */
    protected $allowedScriptTypes = [
        ScriptType::MULTISIG,
        ScriptType::P2PKH,
        ScriptType::P2PK,
    ];

    /**
     * @var string
     */
    protected $decorateType = ScriptType::P2WSH;

    /**
     * @param KeyInterface ...$keys
     * @return ScriptAndSignData
     * @throws \Barechain\Bitcoin\Exceptions\WitnessScriptException
     */
    public function convertKey(KeyInterface ...$keys): ScriptAndSignData
    {
        $witnessScript = new WitnessScript($this->scriptDataFactory->convertKey(...$keys)->getScriptPubKey());
        return new ScriptAndSignData(
            $witnessScript->getOutputScript(),
            (new SignData())
                ->p2wsh($witnessScript)
        );
    }
}

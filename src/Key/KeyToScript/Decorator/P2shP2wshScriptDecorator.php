<?php

declare(strict_types=1);

namespace Barechain\Bitcoin\Key\KeyToScript\Decorator;

use Barechain\Bitcoin\Crypto\EcAdapter\Key\KeyInterface;
use Barechain\Bitcoin\Key\KeyToScript\ScriptAndSignData;
use Barechain\Bitcoin\Script\P2shScript;
use Barechain\Bitcoin\Script\ScriptType;
use Barechain\Bitcoin\Script\WitnessScript;
use Barechain\Bitcoin\Transaction\Factory\SignData;

class P2shP2wshScriptDecorator extends ScriptHashDecorator
{
    /**
     * @var string[]
     */
    protected $allowedScriptTypes = [
        ScriptType::MULTISIG,
        ScriptType::P2PKH,
        ScriptType::P2PK,
    ];

    /**
     * @var string
     */
    protected $decorateType = "scripthash|witness_v0_scripthash";

    /**
     * @param KeyInterface ...$keys
     * @return ScriptAndSignData
     * @throws \Barechain\Bitcoin\Exceptions\P2shScriptException
     * @throws \Barechain\Bitcoin\Exceptions\WitnessScriptException
     */
    public function convertKey(KeyInterface ...$keys): ScriptAndSignData
    {
        $witnessScript = new WitnessScript($this->scriptDataFactory->convertKey(...$keys)->getScriptPubKey());
        $redeemScript = new P2shScript($witnessScript);
        return new ScriptAndSignData(
            $redeemScript->getOutputScript(),
            (new SignData())
                ->p2sh($redeemScript)
                ->p2wsh($witnessScript)
        );
    }
}

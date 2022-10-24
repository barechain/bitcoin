<?php

declare(strict_types=1);

namespace Barechain\Bitcoin\Key\KeyToScript\Decorator;

use Barechain\Bitcoin\Crypto\EcAdapter\Key\KeyInterface;
use Barechain\Bitcoin\Key\KeyToScript\ScriptAndSignData;
use Barechain\Bitcoin\Script\P2shScript;
use Barechain\Bitcoin\Script\ScriptType;
use Barechain\Bitcoin\Transaction\Factory\SignData;

class P2shScriptDecorator extends ScriptHashDecorator
{
    /**
     * @var array
     */
    protected $allowedScriptTypes = [
        ScriptType::MULTISIG,
        ScriptType::P2PKH,
        ScriptType::P2PK,
        ScriptType::P2WKH,
    ];

    /**
     * @var string
     */
    protected $decorateType = ScriptType::P2SH;

    /**
     * @param KeyInterface ...$keys
     * @return ScriptAndSignData
     * @throws \Barechain\Bitcoin\Exceptions\P2shScriptException
     */
    public function convertKey(KeyInterface ...$keys): ScriptAndSignData
    {
        $redeemScript = new P2shScript($this->scriptDataFactory->convertKey(...$keys)->getScriptPubKey());
        return new ScriptAndSignData(
            $redeemScript->getOutputScript(),
            (new SignData())
                ->p2sh($redeemScript)
        );
    }
}

<?php

require __DIR__ . "/../vendor/autoload.php";

use Barechain\Bitcoin\Address\PayToPubKeyHashAddress;
use Barechain\Bitcoin\Transaction\Factory\SignData;
use Barechain\Bitcoin\Key\Factory\PrivateKeyFactory;
use Barechain\Bitcoin\Script\Interpreter\InterpreterInterface as I;
use Barechain\Bitcoin\Script\P2shScript;
use Barechain\Bitcoin\Script\ScriptFactory;
use Barechain\Bitcoin\Transaction\Factory\Signer;
use Barechain\Bitcoin\Transaction\Factory\TxBuilder;
use Barechain\Bitcoin\Transaction\OutPoint;
use Barechain\Bitcoin\Transaction\TransactionOutput;
use BitWasp\Buffertools\Buffer;

$privKeyFactory = new PrivateKeyFactory();
$key = $privKeyFactory->fromHexCompressed("4242424242424242424242424242424242424242424242424242424242424242");

// scriptPubKey is P2SH | P2WPKH
$redeemScript = ScriptFactory::scriptPubKey()->p2wkh($key->getPubKeyHash());
$p2shScript = new P2shScript($redeemScript);

// move to p2pkh
$dest = new PayToPubKeyHashAddress($key->getPublicKey()->getPubKeyHash());

// UTXO
$outpoint = new OutPoint(Buffer::hex('23d6640c3f3383ffc8233fbd830ee49162c720389bbba1c313a43b06a235ae13', 32), 0);
$txOut = new TransactionOutput(95590000, $p2shScript->getOutputScript());

// Move UTXO to a pub-key-hash address
$tx = (new TxBuilder())
    ->spendOutPoint($outpoint)
    ->payToAddress(94550000, $dest)
    ->get();

// Sign transaction
$signData = (new SignData())->p2sh($redeemScript);

$signer = new Signer($tx);
$input = $signer->input(0, $txOut, $signData);
$input->sign($key);
$signed = $signer->get();

$consensus = ScriptFactory::consensus();
$flags = I::VERIFY_P2SH | I::VERIFY_WITNESS;
echo "Script validation result: " . ($input->verify() ? "yay\n" : "nay\n");

echo PHP_EOL;
echo "Witness serialized transaction: " . $signed->getHex() . PHP_EOL. PHP_EOL;
echo "Base serialized transaction: " . $signed->getBaseSerialization()->getHex() . PHP_EOL;

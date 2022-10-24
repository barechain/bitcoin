<?php

require __DIR__ . "/../vendor/autoload.php";

use Barechain\Bitcoin\Address\PayToPubKeyHashAddress;
use Barechain\Bitcoin\Bitcoin;
use Barechain\Bitcoin\Key\Factory\PrivateKeyFactory;
use Barechain\Bitcoin\Script\Interpreter\InterpreterInterface as I;
use Barechain\Bitcoin\Script\ScriptFactory;
use Barechain\Bitcoin\Transaction\Factory\Signer;
use Barechain\Bitcoin\Transaction\Factory\TxBuilder;
use Barechain\Bitcoin\Transaction\OutPoint;
use Barechain\Bitcoin\Transaction\TransactionOutput;
use Barechain\Bitcoin\Transaction\Factory\SignData;
use Barechain\Bitcoin\Script\WitnessScript;
use Barechain\Bitcoin\Script\P2shScript;
use BitWasp\Buffertools\Buffer;

// Setup network and private key to segnet
$privKeyFactory = new PrivateKeyFactory;
$key = $privKeyFactory->fromHexCompressed("4242424242424242424242424242424242424242424242424242424242424242");

// Script is P2SH | P2WSH | P2PKH
$witnessScript = new WitnessScript(ScriptFactory::scriptPubKey()->payToPubKeyHash($key->getPubKeyHash()));
$p2shScript = new P2shScript($witnessScript);

// move to p2pkh
$dest = new PayToPubKeyHashAddress($key->getPublicKey()->getPubKeyHash());

// Utxo
$outpoint = new OutPoint(Buffer::hex('5df04c88810066136619ce715ae9350113b0d4157f5b40ea860204b481bb0cc9', 32), 0);
$txOut = new TransactionOutput(95590000, $p2shScript->getOutputScript());

// Move UTXO to pub-key-hash
$builder = (new TxBuilder())
    ->spendOutPoint($outpoint)
    ->payToAddress(94550000, $dest);

// Sign the transaction

$signData = (new SignData())
    ->p2sh($p2shScript)
    ->p2wsh($witnessScript);

$signer = new Signer($builder->get(), Bitcoin::getEcAdapter());
$input = $signer->input(0, $txOut, $signData);
$input->sign($key);

$signed = $signer->get();

// Verify what we've produced

$consensus = ScriptFactory::consensus();
echo "Script validation result: " . ($input->verify(I::VERIFY_P2SH | I::VERIFY_WITNESS) ? "yay\n" : "nay\n");

echo PHP_EOL;
echo "Witness serialized transaction: " . $signed->getHex() . PHP_EOL. PHP_EOL;
echo "Base serialized transaction: " . $signed->getBaseSerialization()->getHex() . PHP_EOL;

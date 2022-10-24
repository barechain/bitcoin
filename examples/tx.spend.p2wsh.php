<?php

require __DIR__ . "/../vendor/autoload.php";

use Barechain\Bitcoin\Address\PayToPubKeyHashAddress;
use Barechain\Bitcoin\Key\Factory\PrivateKeyFactory;
use Barechain\Bitcoin\Script\Interpreter\InterpreterInterface as I;
use Barechain\Bitcoin\Script\ScriptFactory;
use Barechain\Bitcoin\Transaction\Factory\Signer;
use Barechain\Bitcoin\Transaction\Factory\TxBuilder;
use Barechain\Bitcoin\Transaction\OutPoint;
use Barechain\Bitcoin\Transaction\TransactionOutput;
use Barechain\Bitcoin\Script\WitnessScript;
use Barechain\Bitcoin\Transaction\Factory\SignData;
use BitWasp\Buffertools\Buffer;

// Setup network and private key to segnet
$privKeyFactory = new PrivateKeyFactory();
$key = $privKeyFactory->fromHexCompressed("4242424242424242424242424242424242424242424242424242424242424242");

// Spend from a P2WSH P2PKH
$witnessScript = new WitnessScript(ScriptFactory::scriptPubKey()->payToPubKeyHash($key->getPubKeyHash()));

// UTXO
$outpoint = new OutPoint(Buffer::hex('c2197f15d510304f1463230c0e61566bfb8dcadb7e1c510d3c0470bcfbca2194', 32), 0);
$txOut = new TransactionOutput(99990000, $witnessScript->getOutputScript());

// move to p2pkh
$dest = new PayToPubKeyHashAddress($key->getPublicKey()->getPubKeyHash());

// Create unsigned transaction
$tx = (new TxBuilder())
    ->spendOutPoint($outpoint)
    ->payToAddress(97900000, $dest)
    ->get();

// Sign
$signData = (new SignData())
    ->p2wsh($witnessScript);

$signer = new Signer($tx);
$input = $signer->input(0, $txOut, $signData);
$input->sign($key);
$signed = $signer->get();

// Check signatures
echo "Script validation result: " . ($input->verify(I::VERIFY_P2SH | I::VERIFY_WITNESS) ? "yay\n" : "nay\n");

echo PHP_EOL;
echo "Witness serialized transaction: " . $signed->getHex() . PHP_EOL. PHP_EOL;
echo "Base serialized transaction: " . $signed->getBaseSerialization()->getHex() . PHP_EOL;

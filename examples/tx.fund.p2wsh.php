<?php

require __DIR__ . "/../vendor/autoload.php";

use Barechain\Bitcoin\Bitcoin;
use Barechain\Bitcoin\Key\Factory\PrivateKeyFactory;
use Barechain\Bitcoin\Script\ScriptFactory;
use Barechain\Bitcoin\Transaction\Factory\Signer;
use Barechain\Bitcoin\Transaction\Factory\TxBuilder;
use Barechain\Bitcoin\Transaction\OutPoint;
use Barechain\Bitcoin\Transaction\TransactionOutput;
use BitWasp\Buffertools\Buffer;
use Barechain\Bitcoin\Script\WitnessScript;

// Setup network and private key to segnet
$privKeyFactory = new PrivateKeyFactory();
$key = $privKeyFactory->fromHexCompressed("4242424242424242424242424242424242424242424242424242424242424242");

// Spend from P2PKH
$scriptPubKey = ScriptFactory::scriptPubKey()->payToPubKeyHash($key->getPubKeyHash());

// UTXO
$outpoint = new OutPoint(Buffer::hex('499c2bff1499bf84bc63058fda3ed112c2c663389f108353798a1bd6a6651afe', 32), 0);
$txOut = new TransactionOutput(100000000, $scriptPubKey);

// Move funds into P2WSH P2PKH
$destination = new WitnessScript($scriptPubKey);

// Create unsigned transaction
$tx = (new TxBuilder())
    ->spendOutPoint($outpoint)
    ->output(99990000, $destination->getOutputScript())
    ->get();

// Sign trasaction
$signed = (new Signer($tx, Bitcoin::getEcAdapter()))
    ->sign(0, $key, $txOut)
    ->get();

echo $signed->getBuffer()->getHex() . PHP_EOL;

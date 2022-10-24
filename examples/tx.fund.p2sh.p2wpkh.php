<?php

require __DIR__ . "/../vendor/autoload.php";

use Barechain\Bitcoin\Bitcoin;
use Barechain\Bitcoin\Key\Factory\PrivateKeyFactory;
use Barechain\Bitcoin\Script\P2shScript;
use Barechain\Bitcoin\Script\ScriptFactory;
use Barechain\Bitcoin\Transaction\Factory\Signer;
use Barechain\Bitcoin\Transaction\Factory\TxBuilder;
use Barechain\Bitcoin\Transaction\OutPoint;
use Barechain\Bitcoin\Transaction\TransactionOutput;
use BitWasp\Buffertools\Buffer;

// Setup network and private key to segnet
$privKeyFactory = new PrivateKeyFactory();
$key = $privKeyFactory->fromHexCompressed("4242424242424242424242424242424242424242424242424242424242424242");

$scriptPubKey = ScriptFactory::scriptPubKey()->payToPubKeyHash($key->getPubKeyHash());

// Utxo
$outpoint = new OutPoint(Buffer::hex('703f50920bff10e1622117af81b622d8bbd625460e61909cc3f8b8ee78a59c0d', 32), 0);
$txOut = new TransactionOutput(100000000, $scriptPubKey);

// Move UTXO to P2SH | P2WPKH
$destination = ScriptFactory::scriptPubKey()->p2wkh($key->getPubKeyHash());
$p2sh = new P2shScript($destination);

// Create unsigned transaction
$tx = (new TxBuilder())
    ->spendOutPoint($outpoint)
    ->output(95590000, $p2sh->getOutputScript())
    ->get();

// Sign transaction
$signed = (new Signer($tx, Bitcoin::getEcAdapter()))
    ->sign(0, $key, $txOut)
    ->get();

echo $signed->getHex() . PHP_EOL;

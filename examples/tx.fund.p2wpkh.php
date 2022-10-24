<?php

require __DIR__ . "/../vendor/autoload.php";

use Barechain\Bitcoin\Bitcoin;
use Barechain\Bitcoin\Key\Factory\PrivateKeyFactory;
use Barechain\Bitcoin\Script\ScriptFactory;
use Barechain\Bitcoin\Script\WitnessProgram;
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
$outpoint = new OutPoint(Buffer::hex('874381bb431eaaae16e94f8b88e4ea7baf2ebf541b2ae11ec10d54c8e03a237f', 32), 0);
$txOut = new TransactionOutput(100000000, $scriptPubKey);

// Destination is a pay-to-witness pubkey-hash
$p2wpkh = new WitnessProgram(0, $key->getPubKeyHash());

// Create unsigned transaction, spending UTXO, moving funds to P2WPKH
$tx = (new TxBuilder())
    ->spendOutPoint($outpoint)
    ->output(99900000, $p2wpkh->getScript())
    ->get();

// Sign transaction
$signed = (new Signer($tx, Bitcoin::getEcAdapter()))
    ->sign(0, $key, $txOut)
    ->get();

echo $signed->getHex() . PHP_EOL;

<?php

require __DIR__ . "/../vendor/autoload.php";

use Barechain\Bitcoin\Address\PayToPubKeyHashAddress;
use Barechain\Bitcoin\Key\Factory\PrivateKeyFactory;
use Barechain\Bitcoin\Script\Interpreter\InterpreterInterface as I;
use Barechain\Bitcoin\Transaction\Factory\Signer;
use Barechain\Bitcoin\Transaction\Factory\TxBuilder;
use Barechain\Bitcoin\Transaction\OutPoint;
use Barechain\Bitcoin\Transaction\TransactionOutput;
use Barechain\Bitcoin\Script\ScriptFactory;
use BitWasp\Buffertools\Buffer;

// Setup network and private key to segnet
$privKeyFactory = new PrivateKeyFactory();
$key = $privKeyFactory->fromHexCompressed("4242424242424242424242424242424242424242424242424242424242424242");

// scriptPubKey is P2WKH
$program = ScriptFactory::scriptPubKey()->p2wkh($key->getPubKeyHash());

// UTXO
$outpoint = new OutPoint(Buffer::hex('3a4242c32cf9dca64df73450c7a6141840538b90ccf5d5206b3482e52f7486fc', 32), 0);
$txOut = new TransactionOutput(99900000, $program);

// move to p2pkh
$dest = new PayToPubKeyHashAddress($key->getPublicKey()->getPubKeyHash());

// Create unsigned transaction
$tx = (new TxBuilder())
    ->spendOutPoint($outpoint)
    ->payToAddress(99850000, $dest)
    ->get();

// Sign transaction
$signer = new Signer($tx);
$input = $signer->input(0, $txOut);
$input->sign($key);
$signed = $signer->get();

// Check our signature is correct
echo "Script validation result: " . ($input->verify(I::VERIFY_P2SH | I::VERIFY_WITNESS) ? "yay\n" : "nay\n");

echo PHP_EOL;
echo "Witness serialized transaction: " . $signed->getHex() . PHP_EOL. PHP_EOL;
echo "Base serialized transaction: " . $signed->getBaseSerialization()->getHex() . PHP_EOL;

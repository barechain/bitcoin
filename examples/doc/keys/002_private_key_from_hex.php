<?php

require __DIR__ . "/../../../vendor/autoload.php";

use Barechain\Bitcoin\Address\PayToPubKeyHashAddress;
use Barechain\Bitcoin\Address\SegwitAddress;
use Barechain\Bitcoin\Crypto\Random\Random;
use Barechain\Bitcoin\Key\Factory\PrivateKeyFactory;
use Barechain\Bitcoin\Script\WitnessProgram;

$privKeyFactory = new PrivateKeyFactory();

$rbg = new Random();
$privateKey = $privKeyFactory->fromHexCompressed("a80f2da73746299cb3815b28518de080e7070d1ebe7398f5420f2857250730ff");

$publicKey = $privateKey->getPublicKey();
echo "private key wif  {$privateKey->toWif()}\n";
echo "            hex  {$privateKey->getHex()}\n";
echo "compressed       ".($privateKey->isCompressed()?"true":"false").PHP_EOL;
echo "public key  hex  {$publicKey->getHex()}\n";

$pubKeyHash160 = $publicKey->getPubKeyHash();
$pubKeyHashAddr = new PayToPubKeyHashAddress($pubKeyHash160);
echo "p2pkh address    {$pubKeyHashAddr->getAddress()}\n";

$witnessPubKeyHashAddr = new SegwitAddress(WitnessProgram::v0($pubKeyHash160));
echo "p2wpkh address   {$witnessPubKeyHashAddr->getAddress()}\n";

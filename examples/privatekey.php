<?php

use Barechain\Bitcoin\Address\PayToPubKeyHashAddress;
use Barechain\Bitcoin\Bitcoin;
use Barechain\Bitcoin\Crypto\Random\Random;
use Barechain\Bitcoin\Key\Factory\PrivateKeyFactory;

require __DIR__ . "/../vendor/autoload.php";

$network = Bitcoin::getNetwork();

$random = new Random();
$privKeyFactory = new PrivateKeyFactory();
$privateKey = $privKeyFactory->generateCompressed($random);
$publicKey = $privateKey->getPublicKey();

echo "Key Info\n";
echo " - Compressed? " . (($privateKey->isCompressed() ? 'yes' : 'no')) . "\n";

echo "Private key\n";
echo " - WIF: " . $privateKey->toWif($network) . "\n";
echo " - Hex: " . $privateKey->getHex() . "\n";
echo " - Dec: " . gmp_strval($privateKey->getSecret(), 10) . "\n";

echo "Public Key\n";
echo " - Hex: " . $publicKey->getHex() . "\n";
echo " - Hash: " . $publicKey->getPubKeyHash()->getHex() . "\n";

$address = new PayToPubKeyHashAddress($publicKey->getPubKeyHash());
echo " - Address: " . $address->getAddress() . "\n";

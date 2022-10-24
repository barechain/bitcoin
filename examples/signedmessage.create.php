<?php

require __DIR__ . "/../vendor/autoload.php";

use Barechain\Bitcoin\Address\PayToPubKeyHashAddress;
use Barechain\Bitcoin\Bitcoin;
use Barechain\Bitcoin\Crypto\Random\Random;
use Barechain\Bitcoin\Key\Factory\PrivateKeyFactory;
use Barechain\Bitcoin\MessageSigner\MessageSigner;

$ec = Bitcoin::getEcAdapter();
$random = new Random();
$privKeyFactory = new PrivateKeyFactory($ec);
$privateKey = $privKeyFactory->generateCompressed($random);

$message = 'hi';

$signer = new MessageSigner($ec);
$signed = $signer->sign($message, $privateKey);
$address = new PayToPubKeyHashAddress($privateKey->getPublicKey()->getPubKeyHash());
echo sprintf("Signed by %s\n%s\n", $address->getAddress(), $signed->getBuffer()->getBinary());

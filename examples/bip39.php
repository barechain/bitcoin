<?php

require_once __DIR__ . "/../vendor/autoload.php";

use Barechain\Bitcoin\Crypto\Random\Random;
use Barechain\Bitcoin\Key\Factory\HierarchicalKeyFactory;
use Barechain\Bitcoin\Mnemonic\Bip39\Bip39Mnemonic;
use Barechain\Bitcoin\Mnemonic\Bip39\Bip39SeedGenerator;
use Barechain\Bitcoin\Mnemonic\MnemonicFactory;

// Generate a mnemonic
$random = new Random();
$entropy = $random->bytes(Bip39Mnemonic::MAX_ENTROPY_BYTE_LEN);

$bip39 = MnemonicFactory::bip39();
$seedGenerator = new Bip39SeedGenerator();
$mnemonic = $bip39->entropyToMnemonic($entropy);

// Derive a seed from mnemonic/password
$seed = $seedGenerator->getSeed($mnemonic, 'password');
echo $seed->getHex() . "\n";

$hdFactory = new HierarchicalKeyFactory();
$bip32 = $hdFactory->fromEntropy($seed);

<?php

declare(strict_types=1);

use Barechain\Bitcoin\Address\AddressCreator;
use Barechain\Bitcoin\Bitcoin;
use Barechain\Bitcoin\Key\Deterministic\HdPrefix\GlobalPrefixConfig;
use Barechain\Bitcoin\Key\Deterministic\HdPrefix\NetworkConfig;
use Barechain\Bitcoin\Network\Slip132\BitcoinRegistry;
use Barechain\Bitcoin\Key\Deterministic\Slip132\Slip132;
use Barechain\Bitcoin\Key\KeyToScript\KeyToScriptHelper;
use Barechain\Bitcoin\Network\NetworkFactory;
use Barechain\Bitcoin\Serializer\Key\HierarchicalKey\Base58ExtendedKeySerializer;
use Barechain\Bitcoin\Serializer\Key\HierarchicalKey\ExtendedKeySerializer;

require __DIR__ . "/../vendor/autoload.php";

$adapter = Bitcoin::getEcAdapter();
$btc = NetworkFactory::bitcoin();

$slip132 = new Slip132(new KeyToScriptHelper($adapter));
$bitcoinPrefixes = new BitcoinRegistry();

$config = new GlobalPrefixConfig([
    new NetworkConfig($btc, [$slip132->p2wpkh($bitcoinPrefixes)])
]);

$serializer = new Base58ExtendedKeySerializer(
    new ExtendedKeySerializer($adapter, $config)
);

// The ONLY way to parse these keys is creating a Base58ExtendedKeySerializer with
// a global config
$rootKey = $serializer->parse($btc, "zprvAWgYBBk7JR8GiuMByuy3PBgDdCdBk3fBK77VSGEMnWT1gKG7hz5z9Jt1tPCA2itCvzowhWh5yMdGwyLcLmuKmC8RwgPZMcdCfvyVLhmUR2m");

$account0Key = $rootKey->derivePath("84'/0'/0'");
$firstKey = $account0Key->derivePath("0/0");
$address = $firstKey->getAddress(new AddressCreator());
echo $address->getAddress() . PHP_EOL;

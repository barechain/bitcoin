<?php

/*
TREZOR example using an account ypub, so you can create addresses without putting your private key or seed in your script.
*/

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

require "./vendor/autoload.php";

$adapter = Bitcoin::getEcAdapter();
$btc = NetworkFactory::bitcoin();

$slip132 = new Slip132(new KeyToScriptHelper($adapter));
$bitcoinPrefixes = new BitcoinRegistry();

$config = new GlobalPrefixConfig([
    new NetworkConfig($btc, [$slip132->p2shP2wpkh($bitcoinPrefixes)])
]);

$serializer = new Base58ExtendedKeySerializer(
    new ExtendedKeySerializer($adapter, $config)
);

// The ONLY way to parse these keys is creating a Base58ExtendedKeySerializer with
// a global config
$rootKey = $serializer->parse($btc, 'ypub...');
for ($i=0; $i < 25; $i++) {
        $firstKey = $rootKey->derivePath("0/".strval($i));
        $address = $firstKey->getAddress(new AddressCreator());
        echo $address->getAddress() . PHP_EOL;
}

<?php

require __DIR__ . "/../vendor/autoload.php";

use Barechain\Bitcoin\Address\AddressCreator;
use Barechain\Bitcoin\Address\PayToPubKeyHashAddress;
use Barechain\Bitcoin\Bitcoin;
use Barechain\Bitcoin\Crypto\EcAdapter\EcSerializer;
use Barechain\Bitcoin\Crypto\EcAdapter\Serializer\Signature\CompactSignatureSerializerInterface;
use Barechain\Bitcoin\MessageSigner\MessageSigner;
use Barechain\Bitcoin\Network\NetworkFactory;
use Barechain\Bitcoin\Serializer\MessageSigner\SignedMessageSerializer;

Bitcoin::setNetwork(NetworkFactory::bitcoinTestnet());
$addrCreator = new AddressCreator();

$address = 'n2Z2DFCxG6vktyX1MFkKAQPQFsrmniGKj5';

$sig = '-----BEGIN BITCOIN SIGNED MESSAGE-----
hi
-----BEGIN SIGNATURE-----
IBpGR29vEbbl4kmpK0fcDsT75GPeH2dg5O199D3iIkS3VcDoQahJMGJEDozXot8JGULWjN9Llq79aF+FogOoz/M=
-----END BITCOIN SIGNED MESSAGE-----';

/** @var PayToPubKeyHashAddress $address */
$address = $addrCreator->fromString($address);

/** @var CompactSignatureSerializerInterface $compactSigSerializer */
$compactSigSerializer = EcSerializer::getSerializer(CompactSignatureSerializerInterface::class);
$serializer = new SignedMessageSerializer($compactSigSerializer);

$signedMessage = $serializer->parse($sig);
$signer = new MessageSigner();
if ($signer->verify($signedMessage, $address)) {
    echo "Signature verified!\n";
} else {
    echo "Failed to verify signature!\n";
}

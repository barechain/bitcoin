<?php

declare(strict_types=1);

namespace Barechain\Bitcoin\Transaction\Factory\Checker;

use Barechain\Bitcoin\Crypto\EcAdapter\Adapter\EcAdapterInterface;
use Barechain\Bitcoin\Crypto\EcAdapter\EcSerializer;
use Barechain\Bitcoin\Crypto\EcAdapter\Serializer\Key\PublicKeySerializerInterface;
use Barechain\Bitcoin\Crypto\EcAdapter\Serializer\Signature\DerSignatureSerializerInterface;
use Barechain\Bitcoin\Script\Interpreter\CheckerBase;
use Barechain\Bitcoin\Script\Interpreter\Checker;
use Barechain\Bitcoin\Serializer\Signature\TransactionSignatureSerializer;
use Barechain\Bitcoin\Transaction\TransactionInterface;
use Barechain\Bitcoin\Transaction\TransactionOutputInterface;

class CheckerCreator extends CheckerCreatorBase
{
    public static function fromEcAdapter(EcAdapterInterface $ecAdapter)
    {
        $derSigSer = EcSerializer::getSerializer(DerSignatureSerializerInterface::class);
        $txSigSer = new TransactionSignatureSerializer($derSigSer);
        $pkSer = EcSerializer::getSerializer(PublicKeySerializerInterface::class);
        return new CheckerCreator($ecAdapter, $txSigSer, $pkSer);
    }
    /**
     * @param TransactionInterface $tx
     * @param int $nInput
     * @param TransactionOutputInterface $txOut
     * @return CheckerBase
     */
    public function create(TransactionInterface $tx, int $nInput, TransactionOutputInterface $txOut): CheckerBase
    {
        return new Checker($this->ecAdapter, $tx, $nInput, $txOut->getValue(), $this->txSigSerializer, $this->pubKeySerializer);
    }
}

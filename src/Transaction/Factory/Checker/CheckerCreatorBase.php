<?php

declare(strict_types=1);

namespace Barechain\Bitcoin\Transaction\Factory\Checker;

use Barechain\Bitcoin\Crypto\EcAdapter\Adapter\EcAdapterInterface;
use Barechain\Bitcoin\Crypto\EcAdapter\Serializer\Key\PublicKeySerializerInterface;
use Barechain\Bitcoin\Script\Interpreter\CheckerBase;
use Barechain\Bitcoin\Serializer\Signature\TransactionSignatureSerializer;
use Barechain\Bitcoin\Transaction\TransactionInterface;
use Barechain\Bitcoin\Transaction\TransactionOutputInterface;

abstract class CheckerCreatorBase
{
    /**
     * @var EcAdapterInterface
     */
    protected $ecAdapter;

    /**
     * @var TransactionSignatureSerializer
     */
    protected $txSigSerializer;

    /**
     * @var PublicKeySerializerInterface
     */
    protected $pubKeySerializer;

    /**
     * CheckerCreator constructor.
     * @param EcAdapterInterface $ecAdapter
     * @param TransactionSignatureSerializer $txSigSerializer
     * @param PublicKeySerializerInterface $pubKeySerializer
     */
    public function __construct(
        EcAdapterInterface $ecAdapter,
        TransactionSignatureSerializer $txSigSerializer,
        PublicKeySerializerInterface $pubKeySerializer
    ) {
        $this->ecAdapter = $ecAdapter;
        $this->txSigSerializer = $txSigSerializer;
        $this->pubKeySerializer = $pubKeySerializer;
    }

    /**
     * @param TransactionInterface $tx
     * @param int $nInput
     * @param TransactionOutputInterface $txOut
     * @return CheckerBase
     */
    abstract public function create(TransactionInterface $tx, int $nInput, TransactionOutputInterface $txOut): CheckerBase;
}

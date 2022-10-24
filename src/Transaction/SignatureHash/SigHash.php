<?php

declare(strict_types=1);

namespace Barechain\Bitcoin\Transaction\SignatureHash;

use Barechain\Bitcoin\Script\ScriptInterface;
use Barechain\Bitcoin\Transaction\TransactionInterface;
use BitWasp\Buffertools\BufferInterface;

abstract class SigHash implements SigHashInterface
{
    const V0 = 0;
    const V1 = 1;
    
    /**
     * @var TransactionInterface
     */
    protected $tx;

    /**
     * SigHash constructor.
     * @param TransactionInterface $transaction
     */
    public function __construct(TransactionInterface $transaction)
    {
        $this->tx = $transaction;
    }

    /**
     * @param ScriptInterface $txOutScript
     * @param int $inputToSign
     * @param int $sighashType
     * @return BufferInterface
     */
    abstract public function calculate(
        ScriptInterface $txOutScript,
        int $inputToSign,
        int $sighashType = self::ALL
    ): BufferInterface;
}

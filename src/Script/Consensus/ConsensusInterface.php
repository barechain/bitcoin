<?php

namespace Barechain\Bitcoin\Script\Consensus;

use Barechain\Bitcoin\Script\ScriptInterface;
use Barechain\Bitcoin\Transaction\TransactionInterface;

interface ConsensusInterface
{
    /**
     * @param TransactionInterface $tx
     * @param ScriptInterface $scriptPubKey
     * @param integer $nInputToSign
     * @param int $flags
     * @param integer $amount
     * @return bool
     */
    public function verify(TransactionInterface $tx, ScriptInterface $scriptPubKey, int $flags, int $nInputToSign, int $amount): bool;
}

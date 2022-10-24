<?php

declare(strict_types=1);

namespace Barechain\Bitcoin\Utxo;

use Barechain\Bitcoin\Transaction\OutPointInterface;
use Barechain\Bitcoin\Transaction\TransactionOutputInterface;

interface UtxoInterface
{
    /**
     * @return OutPointInterface
     */
    public function getOutPoint(): OutPointInterface;

    /**
     * @return TransactionOutputInterface
     */
    public function getOutput(): TransactionOutputInterface;
}

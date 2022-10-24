<?php

declare(strict_types=1);

namespace Barechain\Bitcoin\Serializer\Transaction;

use Barechain\Bitcoin\Transaction\TransactionInterface;
use BitWasp\Buffertools\BufferInterface;
use BitWasp\Buffertools\Parser;

interface TransactionSerializerInterface
{
    /**
     * @param Parser $parser
     * @return TransactionInterface
     */
    public function fromParser(Parser $parser): TransactionInterface;

    /**
     * @param BufferInterface $data
     * @return TransactionInterface
     */
    public function parse(BufferInterface $data): TransactionInterface;

    /**
     * @param TransactionInterface $transaction
     * @param int $optFlags
     * @return BufferInterface
     */
    public function serialize(TransactionInterface $transaction, int $optFlags = 0): BufferInterface;
}

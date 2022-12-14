<?php

declare(strict_types=1);

namespace Barechain\Bitcoin\Block;

use Barechain\Bitcoin\Bloom\BloomFilter;
use Barechain\Bitcoin\Math\Math;
use Barechain\Bitcoin\Serializable;
use Barechain\Bitcoin\Serializer\Block\BlockHeaderSerializer;
use Barechain\Bitcoin\Serializer\Block\BlockSerializer;
use Barechain\Bitcoin\Serializer\Transaction\TransactionSerializer;
use Barechain\Bitcoin\Transaction\TransactionInterface;
use BitWasp\Buffertools\BufferInterface;

class Block extends Serializable implements BlockInterface
{
    /**
     * @var Math
     */
    private $math;

    /**
     * @var BlockHeaderInterface
     */
    private $header;

    /**
     * @var TransactionInterface[]
     */
    private $transactions;

    /**
     * @var MerkleRoot
     */
    private $merkleRoot;

    /**
     * Block constructor.
     * @param Math $math
     * @param BlockHeaderInterface $header
     * @param TransactionInterface[] ...$transactions
     */
    public function __construct(Math $math, BlockHeaderInterface $header, TransactionInterface ...$transactions)
    {
        $this->math = $math;
        $this->header = $header;
        $this->transactions = $transactions;
    }

    /**
     * {@inheritdoc}
     * @see \Barechain\Bitcoin\Block\BlockInterface::getHeader()
     */
    public function getHeader(): BlockHeaderInterface
    {
        return $this->header;
    }

    /**
     * {@inheritdoc}
     * @see \Barechain\Bitcoin\Block\BlockInterface::getMerkleRoot()
     * @throws \Barechain\Bitcoin\Exceptions\MerkleTreeEmpty
     */
    public function getMerkleRoot(): BufferInterface
    {
        if (null === $this->merkleRoot) {
            $this->merkleRoot = new MerkleRoot($this->math, $this->getTransactions());
        }

        return $this->merkleRoot->calculateHash();
    }

    /**
     * @see \Barechain\Bitcoin\Block\BlockInterface::getTransactions()
     * @return TransactionInterface[]
     */
    public function getTransactions(): array
    {
        return $this->transactions;
    }

    /**
     * @see \Barechain\Bitcoin\Block\BlockInterface::getTransaction()
     * @param int $i
     * @return TransactionInterface
     */
    public function getTransaction(int $i): TransactionInterface
    {
        if (!array_key_exists($i, $this->transactions)) {
            throw new \InvalidArgumentException("No transaction in the block with this index");
        }

        return $this->transactions[$i];
    }

    /**
     * @param BloomFilter $filter
     * @return FilteredBlock
     */
    public function filter(BloomFilter $filter): FilteredBlock
    {
        $vMatch = [];
        $vHashes = [];
        foreach ($this->getTransactions() as $tx) {
            $vMatch[] = $filter->isRelevantAndUpdate($tx);
            $vHashes[] = $tx->getTxHash();
        }

        return new FilteredBlock(
            $this->getHeader(),
            PartialMerkleTree::create(count($this->getTransactions()), $vHashes, $vMatch)
        );
    }

    /**
     * {@inheritdoc}
     * @see \BitWasp\Buffertools\SerializableInterface::getBuffer()
     */
    public function getBuffer(): BufferInterface
    {
        return (new BlockSerializer($this->math, new BlockHeaderSerializer(), new TransactionSerializer()))->serialize($this);
    }
}

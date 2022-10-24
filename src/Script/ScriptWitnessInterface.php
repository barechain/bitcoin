<?php

declare(strict_types=1);

namespace Barechain\Bitcoin\Script;

use Barechain\Bitcoin\Collection\CollectionInterface;
use BitWasp\Buffertools\BufferInterface;
use BitWasp\Buffertools\SerializableInterface;

interface ScriptWitnessInterface extends CollectionInterface, SerializableInterface
{
    /**
     * @return BufferInterface[]
     */
    public function all(): array;

    /**
     * @param ScriptWitnessInterface $witness
     * @return bool
     */
    public function equals(ScriptWitnessInterface $witness): bool;
}

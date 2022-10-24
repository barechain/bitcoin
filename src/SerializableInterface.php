<?php

declare(strict_types=1);

namespace Barechain\Bitcoin;

use BitWasp\Buffertools\BufferInterface;

interface SerializableInterface extends \BitWasp\Buffertools\SerializableInterface
{
    /**
     * @return BufferInterface
     */
    public function getBuffer(): BufferInterface;

    /**
     * @return string
     */
    public function getHex(): string;

    /**
     * @return string
     */
    public function getBinary(): string;

    /**
     * @return string
     */
    public function getInt();
}

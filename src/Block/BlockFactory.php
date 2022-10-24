<?php

declare(strict_types=1);

namespace Barechain\Bitcoin\Block;

use Barechain\Bitcoin\Bitcoin;
use Barechain\Bitcoin\Math\Math;
use Barechain\Bitcoin\Script\Opcodes;
use Barechain\Bitcoin\Serializer\Block\BlockHeaderSerializer;
use Barechain\Bitcoin\Serializer\Block\BlockSerializer;
use Barechain\Bitcoin\Serializer\Script\ScriptWitnessSerializer;
use Barechain\Bitcoin\Serializer\Transaction\OutPointSerializer;
use Barechain\Bitcoin\Serializer\Transaction\TransactionInputSerializer;
use Barechain\Bitcoin\Serializer\Transaction\TransactionOutputSerializer;
use Barechain\Bitcoin\Serializer\Transaction\TransactionSerializer;
use BitWasp\Buffertools\Buffer;
use BitWasp\Buffertools\BufferInterface;

class BlockFactory
{
    /**
     * @param string $string
     * @param Math|null $math
     * @return BlockInterface
     * @throws \BitWasp\Buffertools\Exceptions\ParserOutOfRange
     * @throws \Exception
     */
    public static function fromHex(string $string, Math $math = null): BlockInterface
    {
        return self::fromBuffer(Buffer::hex($string), $math);
    }

    /**
     * @param BufferInterface $buffer
     * @param Math|null $math
     * @return BlockInterface
     * @throws \BitWasp\Buffertools\Exceptions\ParserOutOfRange
     */
    public static function fromBuffer(BufferInterface $buffer, Math $math = null): BlockInterface
    {
        $opcodes = new Opcodes();
        $serializer = new BlockSerializer(
            $math ?: Bitcoin::getMath(),
            new BlockHeaderSerializer(),
            new TransactionSerializer(
                new TransactionInputSerializer(new OutPointSerializer(), $opcodes),
                new TransactionOutputSerializer($opcodes),
                new ScriptWitnessSerializer()
            )
        );

        return $serializer->parse($buffer);
    }
}

<?php

declare(strict_types=1);

namespace Barechain\Bitcoin\Script\Interpreter;

use Barechain\Bitcoin\Script\ScriptInterface;
use Barechain\Bitcoin\Transaction\SignatureHash\Hasher;
use Barechain\Bitcoin\Transaction\SignatureHash\SigHash;
use Barechain\Bitcoin\Transaction\SignatureHash\V1Hasher;
use BitWasp\Buffertools\Buffer;
use BitWasp\Buffertools\BufferInterface;

class Checker extends CheckerBase
{
    /**
     * @var array
     */
    protected $sigHashCache = [];

    /**
     * @param ScriptInterface $script
     * @param int $sigHashType
     * @param int $sigVersion
     * @return BufferInterface
     */
    public function getSigHash(ScriptInterface $script, int $sigHashType, int $sigVersion): BufferInterface
    {
        $cacheCheck = $sigVersion . $sigHashType . $script->getBuffer()->getBinary();
        if (!isset($this->sigHashCache[$cacheCheck])) {
            if (SigHash::V1 === $sigVersion) {
                $hasher = new V1Hasher($this->transaction, $this->amount);
            } else {
                $hasher = new Hasher($this->transaction);
            }

            $hash = $hasher->calculate($script, $this->nInput, $sigHashType);
            $this->sigHashCache[$cacheCheck] = $hash->getBinary();
        } else {
            $hash = new Buffer($this->sigHashCache[$cacheCheck], 32);
        }

        return $hash;
    }
}

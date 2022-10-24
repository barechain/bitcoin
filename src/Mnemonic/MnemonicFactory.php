<?php

declare(strict_types=1);

namespace Barechain\Bitcoin\Mnemonic;

use Barechain\Bitcoin\Bitcoin;
use Barechain\Bitcoin\Crypto\EcAdapter\Adapter\EcAdapterInterface;
use Barechain\Bitcoin\Mnemonic\Bip39\Bip39Mnemonic;
use Barechain\Bitcoin\Mnemonic\Bip39\Bip39WordListInterface;
use Barechain\Bitcoin\Mnemonic\Electrum\ElectrumMnemonic;
use Barechain\Bitcoin\Mnemonic\Electrum\ElectrumWordListInterface;

class MnemonicFactory
{

    /**
     * @param ElectrumWordListInterface $wordList
     * @param EcAdapterInterface $ecAdapter
     * @return ElectrumMnemonic
     */
    public static function electrum(ElectrumWordListInterface $wordList = null, EcAdapterInterface $ecAdapter = null): ElectrumMnemonic
    {
        return new ElectrumMnemonic(
            $ecAdapter ?: Bitcoin::getEcAdapter(),
            $wordList ?: new \Barechain\Bitcoin\Mnemonic\Electrum\Wordlist\EnglishWordList()
        );
    }

    /**
     * @param \Barechain\Bitcoin\Mnemonic\Bip39\Bip39WordListInterface $wordList
     * @param EcAdapterInterface $ecAdapter
     * @return Bip39Mnemonic
     */
    public static function bip39(Bip39WordListInterface $wordList = null, EcAdapterInterface $ecAdapter = null): Bip39Mnemonic
    {
        return new Bip39Mnemonic(
            $ecAdapter ?: Bitcoin::getEcAdapter(),
            $wordList ?: new Bip39\Wordlist\EnglishWordList()
        );
    }
}

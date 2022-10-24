<?php

use Barechain\Bitcoin\Crypto\Hash;
use Barechain\Bitcoin\Script\Interpreter\Interpreter;
use Barechain\Bitcoin\Script\Opcodes;
use Barechain\Bitcoin\Script\ScriptFactory;
use Barechain\Bitcoin\Transaction\TransactionFactory;
use BitWasp\Buffertools\Buffer;

require __DIR__ . "/../../../vendor/autoload.php";

$preimage = new Buffer('auauly4lraslidfhalsdfalsdfa');
$hash = Hash::sha256($preimage);

$flags = Interpreter::VERIFY_NONE;
$scriptSig = ScriptFactory::sequence([$preimage]);
$scriptPubKey = ScriptFactory::sequence([Opcodes::OP_SHA256, $hash, Opcodes::OP_EQUAL]);

$tx = TransactionFactory::build()
    ->input(str_pad('', 64, '0'), 0, $scriptSig)
    ->get();

$consensus = ScriptFactory::consensus();
$nIn = 0;
$amount = 0;
echo $consensus->verify($tx, $scriptPubKey, $nIn, $flags, $amount) ? "valid preimage" : "invalid preimage";
echo PHP_EOL;

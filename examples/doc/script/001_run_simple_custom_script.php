<?php

require __DIR__ . "/../../../vendor/autoload.php";

use Barechain\Bitcoin\Script\Interpreter\InterpreterInterface as I;
use Barechain\Bitcoin\Script\Opcodes;
use Barechain\Bitcoin\Script\ScriptFactory;
use Barechain\Bitcoin\Transaction\TransactionFactory;

$flags = I::VERIFY_NONE;
$scriptSig = ScriptFactory::create()->int(1)->getScript();
$scriptPubKey = ScriptFactory::create()->int(1)->opcode(Opcodes::OP_ADD)->int(2)->opcode(Opcodes::OP_EQUAL)->getScript();

$tx = TransactionFactory::build()
    ->input(str_pad('', 64, '0'), 0, $scriptSig)
    ->get();

$consensus = ScriptFactory::consensus();
$nIn = 0;
$amount = 0;
echo $consensus->verify($tx, $scriptPubKey, $nIn, $flags, $amount) ? "correct" : "incorrect";
echo PHP_EOL;

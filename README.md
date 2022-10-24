  
## Bitcoin

This repository contains an implementation of Bitcoin using mostly pure PHP.

*Warning*: This library does not support 32-bit installations of PHP. Please also note that composer is the only supported installation method.

## Installation

You can install this library via Composer: `composer require barechain/bitcoin`

## Documentation

 Check out the beginnings of the documentation for the library: [[Introduction](doc/documentation/Introduction.md)]

## Presently supported:

 - Blocks, headers, and merkle blocks and bloom filters
 - P2SH & Segregated witness scripts
 - An adaptable elliptic-curve library, using [[PhpEcc](https://github.com/mdanter/phpecc)] by default, or libsecp256k1 if the bindings are found
 - Support for building, parsing, signing/validating transactions
 - Deterministic signatures (RFC6979)
 - BIP32 and electrum (older type I) deterministic key algorithms
 - BIP39, and the older electrum seed format.
 - ScriptFactory for common input/output types, parser, interpreter, and classifiers
 - Supports bindings to libbitcoinconsensus
 - Bindings to Stratum (electrum) servers

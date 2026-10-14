# JSON RPC 2.0 PSR-7 Message Factory

[![GitHub Actions][GA Image]][GA Link]
[![Code Coverage][Coverage Image]][CodeCov Link]
[![Downloads][Downloads Image]][Packagist Link]
[![Packagist][Packagist Image]][Packagist Link]

## Installation

Add as [Composer](https://getcomposer.org/) dependency:

```sh
composer require simpod/json-rpc
```

## Response extraction

Construct `SimPod\JsonRpc\Extractor\ResponseExtractor` with a PSR-7 message to read
its JSON-RPC response fields. The constructor validates the fields used by the
typed getters before they can be read:

- The response must be a JSON object with a string `jsonrpc` field.
- An optional `id` must be a string, integer or `null`.
- A non-null `error` must be an object with an integer `code` and a string `message`.

Malformed fields cause `SimPod\JsonRpc\Extractor\MalformedResponse`, which extends
`UnexpectedValueException`. Invalid JSON still causes `JsonException`. Both
exceptions are thrown by the constructor, not by a getter.

This is field validation, not full JSON-RPC protocol validation. No `method` is
required. Unknown fields are accepted, and `result` and `error.data` remain
unrestricted JSON values, decoded as associative arrays where applicable. A
response can contain both an error and a partial result. Application-specific
validation, such as checking result shapes or matching an expected request ID,
remains the caller's responsibility.

[GA Image]: https://github.com/simPod/PhpJsonRpc/workflows/CI/badge.svg

[GA Link]: https://github.com/simPod/PhpJsonRpc/actions?query=workflow%3A%22CI%22+branch%3Amaster

[Coverage Image]: https://codecov.io/gh/simPod/PhpJsonRpc/branch/master/graph/badge.svg

[CodeCov Link]: https://codecov.io/gh/simPod/PhpJsonRpc/branch/master

[Downloads Image]: https://poser.pugx.org/simpod/json-rpc/d/total.svg

[Packagist Image]: https://poser.pugx.org/simpod/json-rpc/v/stable.svg

[Packagist Link]: https://packagist.org/packages/simpod/json-rpc

# Json Web Token Implementation

![Version](https://img.shields.io/packagist/v/remessage/json-web-token?style=for-the-badge)
![PHP Version Support](https://img.shields.io/packagist/php-v/remessage/json-web-token?style=for-the-badge)
![License](https://img.shields.io/github/license/re-message/json-web-token?style=for-the-badge)
![GitHub Workflow Status](https://img.shields.io/github/workflow/status/re-message/json-web-token/testing/1.x?style=for-the-badge)

This library implements a series of standards related with JSON Web Token and is used by others [Re: Message](https://remessage.ru) libraries and services like `remessage/client` and API server.

## Installation

You will need Composer to install. Run this command:

`composer require remessage/json-web-token`

## Usage

### Algorithms

All tokens and services uses algorithms to sign, verify, encrypt and decrypt the token data. Each algorithm MUST implement `RM\Standard\Jwt\Algorithm\AlgorithmInterface`.

At this moment, we provide only HMAC-based algorithms:
1. `RM\Standard\Jwt\Algorithm\Signature\HMAC\HS256` for HMAC with SHA-256
1. `RM\Standard\Jwt\Algorithm\Signature\HMAC\HS512` for HMAC with SHA-512
1. `RM\Standard\Jwt\Algorithm\Signature\HMAC\HS3256` for HMAC with SHA3-256 (or Keccak256)
1. `RM\Standard\Jwt\Algorithm\Signature\HMAC\HS3512` for HMAC with SHA3-512 (or Keccak512)

Also, you can implement your own algorithm. You need implement the `RM\Standard\Jwt\Algorithm\AlgorithmInterface` interface.

### Keys

Each key must implement the `RM\Standard\Jwt\Key\Factory\KeyInterface` interface.

At the moment, we provide support for:
1. Octet key
2. RSA keys (PSS and PKCS1) _(only signing)_

Also, you can implement your own key. You need implement the `RM\Standard\Jwt\Key\Factory\KeyFactoryInterface` interface to create your key from array. If your key contains a property that is not implemented in this library, then you also need to implement the `RM\Standard\Jwt\Key\Parameter\KeyParameterInterface` interface and use `RM\Standard\Jwt\Key\Parameter\Factory\ParameterFactory` with your key parameter class.

### Tokens

To create new token you can use the `RM\Standard\Jwt\Signature\SignatureToken` class. The class constructor have 3 arguments: header parameters, payload claims and signature. The header parameters must include the algorithm parameter. Other arguments is optional.

Example:

```php
<?php

use RM\Standard\Jwt\Algorithm\Signature\HMAC\HS3256;
use RM\Standard\Jwt\Property\Header\Algorithm;
use RM\Standard\Jwt\Signature\SignatureToken;

$algorithm = new HS3256();
$token = new SignatureToken([Algorithm::fromAlgorithm($algorithm)]);
```

Via secondary constructor:
```php
<?php

use RM\Standard\Jwt\Algorithm\Signature\HMAC\HS3256;
use RM\Standard\Jwt\Signature\SignatureToken;

$token = SignatureToken::createWithAlgorithm(new HS3256());
```

### Properties

The token has parameters called properties, these are important sensitive data that are needed for authorization and verification. They are separated respectively in the header and in the payload of the token. Header parameters contains the common token data: the signing or encryption algorithm and the type of token. Payload claims contain the data necessary for verification: the token sign/encrypt time, the action time, who signed and for whom. The payload can also include business logic data like permissions or something else.

Header parameters defined in the `RM\Standard\Jwt\Property\Header\` namespace. Payload claims defined in the `RM\Standard\Jwt\Property\Payload\` namespace.

You can use custom properties. To create your custom property you need implement one of these interfaces:
1. `RM\Standard\Jwt\Property\Header\HeaderParameterInterface` to create custom header parameter
2. `RM\Standard\Jwt\Property\Payload\ClaimInterface` to create custom claim

According to the standard, property names must be concise enough. We use 3-character names, but there are no restrictions.

### Serialization

Serialization of tokens provided by some services implemented the `RM\Standard\Jwt\Serializer\SerializerInterface` interface.

Example:

```php
<?php

use RM\Standard\Jwt\Serializer\SignatureCompactSerializer;
use RM\Standard\Jwt\Signature\SignatureToken;

// serialized token
// {"alg": "HS256","typ": "JWT"} . {"sub": "1234567890","name": "John Doe","iat": 1516239022} . signature
$rawToken = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c';

$serializer = new SignatureCompactSerializer();

// result is a SignatureToken object
// serializer DO NOT validate token
$token = $serializer->deserialize($rawToken);

// will return true
var_dump($rawToken === $token->toString($serializer));
```

### Signing

To sign the token you should use the `RM\Standard\Jwt\Signature\Signer`. Signer only depends on the serializer, but the default is `RM\Standard\Jwt\Serializer\SignatureCompactSerializer`.

Serializer is necessary for the service to sign the token, since the signature is the header and the payload signed by the key.

Also, you can use decorators for Signer to provide some token handling:
- `RM\Standard\Jwt\Signature\GeneratedSigner` provides ability to generate token property before signing
- `RM\Standard\Jwt\Signature\EventfulSigner` creates events
- `RM\Standard\Jwt\Signature\LoggableSigner` allows collecting logs about the signing process

Example:

```php
<?php

use Laminas\Math\Rand;
use ParagonIE\ConstantTime\Base64UrlSafe;
use RM\Standard\Jwt\Algorithm\Signature\HMAC\HS3256;
use RM\Standard\Jwt\Key\Key;
use RM\Standard\Jwt\Key\Parameter\Type;
use RM\Standard\Jwt\Key\Parameter\Value;
use RM\Standard\Jwt\Signature\SignatureToken;
use RM\Standard\Jwt\Signature\Signer;

// some algorithm
$algorithm = new HS3256();
$token = SignatureToken::createWithAlgorithm($algorithm);

// generate random octet key for example
$value = Base64UrlSafe::encode(Rand::getBytes(64));
$key = new Key(
    [
        new Type(Type::OCTET),
        new Value($value),
    ]
);

$signer = new Signer();
// method returns new token object with signature
$signedToken = $signer->sign($token, $algorithm, $key);

// will return something like this:
// eyJhbGciOiJIUzMyNTYiLCJ0eXAiOiJKV1QifQ.W10.KDa2nZVCuX1LldcMJZz2wp_QifjN7sNHCFLtGDAWF9s
echo $signedToken;
```

## Implementation
This library implements only **the necessary minimum** for the correct operation of the service platform.

### Will not implemented
* Nested JSON Web Token
* Multiple signatures for JWS and JWE
* JWS JSON Serialization ([RFC 7515](https://datatracker.ietf.org/doc/html/rfc7515), section 7.2)
* JWE JSON Serialization ([RFC 7516](https://datatracker.ietf.org/doc/html/rfc7516), section 7.2)

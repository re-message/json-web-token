# Json Web Token Implementation

This library implements a series of standards related with JSON Web Token and is used by others [Re: Message](https://remessage.ru) libraries and services like `remessage/client` and API server.

## Installation

You will need Composer to install. Run this command:

`composer require remessage/json-web-token`

## Usage

### Algorithms

All tokens and services uses algorithms to sign, verify, encrypt and decrypt the token data. Each algorithm MUST implement `RM\Standard\Jwt\Algorithm\AlgorithmInterface`.

### Keys

At the moment, we provide only octet support. This is a just string which used as key in HMAC algorithms.

### Tokens

To create new token you can use class `RM\Standard\Jwt\Signature\SignatureToken` class. Class constructor have 3 arguments: header claims, payload claims and signature. You should pass algorithm name with header claims. Other arguments and claims is optional.

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

### Claims

The token has parameters called claim, these are important sensitive data that are needed for authorization and verification. They are divided respectively in the header and in the payload of the token. Header claims are general token data: the signing or encryption algorithm and the type of token. Payload claims contain the data necessary for verification: this is the time of signing the token, the time of its action, who signed it and for whom.

Header claims defined in `RM\Standard\Jwt\Token\Header` class as constants. Payload claim defined in `RM\Standard\Jwt\Token\Payload` class.

You can use your custom claims. According to the standard, claim names must be concise enough. We use 3-character names, but there are no restrictions.


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

Serializer is necessary for the service to sign the token, since the signature is the header, and the payload signed by the key.

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
        Type::NAME => TypeAlias::OCTET,
        Value::NAME => $value,
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
* JWS JSON Serialization ([RFC 7515](https://tools.ietf.org/html/rfc7515), section 7.2)
* JWE JSON Serialization ([RFC 7516](https://tools.ietf.org/html/rfc7516), section 7.2)

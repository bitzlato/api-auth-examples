<?php

function base64UrlEncode(string $data, bool $usePadding = false): string
{
    $encoded = strtr(base64_encode($data), '+/', '-_');
    return true === $usePadding ? $encoded : rtrim($encoded, '=');
}

function base64UrlDecode(string $data): string
{
    return base64_decode(strtr($data, '-_', '+/'), true);
}

function fromAsn1(string $signature, int $length): string
{
    $message = bin2hex($signature);
    $position = 0;

    if ('30' !== readAsn1Content($message, $position, 2)) {
        throw new InvalidArgumentException('Invalid data. Should start with a sequence.');
    }

    if ('81' === readAsn1Content($message, $position, 2)) {
        $position += 2;
    }

    $pointR = retrievePositiveInteger(readAsn1Integer($message, $position));
    $pointS = retrievePositiveInteger(readAsn1Integer($message, $position));

    return hex2bin(str_pad($pointR, $length, '0', STR_PAD_LEFT) . str_pad($pointS, $length, '0', STR_PAD_LEFT));
}


function readAsn1Content(string $message, int &$position, int $length): string
{
    $content = mb_substr($message, $position, $length, '8bit');
    $position += $length;

    return $content;
}

function readAsn1Integer(string $message, int &$position): string
{
    if ('02' !== readAsn1Content($message, $position, 2)) {
        throw new InvalidArgumentException('Invalid data. Should contain an integer.');
    }

    $length = (int)hexdec(readAsn1Content($message, $position, 2));

    return readAsn1Content($message, $position, $length * 2);
}

function retrievePositiveInteger(string $data): string
{
    while (0 === mb_strpos($data, '00', 0, '8bit')
        && mb_substr($data, 2, 2, '8bit') > '7f') {
        $data = mb_substr($data, 2, null, '8bit');
    }

    return $data;
}

function sign($jwk, $input): string
{
    $d = unpack('H*', str_pad(base64UrlDecode($jwk['d']), 32, "\0", STR_PAD_LEFT))[1];

    $der = pack(
        'H*',
        '3077' // SEQUENCE, length 87+length($d)=32
        . '020101' // INTEGER, 1
        . '0420'   // OCTET STRING, length($d) = 32
        . $d
        . 'a00a' // TAGGED OBJECT #0, length 10
        . '0608' // OID, length 8
        . '2a8648ce3d030107' // 1.3.132.0.34 = P-256 Curve
        . 'a144' //  TAGGED OBJECT #1, length 68
        . '0342' // BIT STRING, length 66
        . '00' // prepend with NUL - pubkey will follow
    );

    $length = (int)ceil(256 / 8);
    $der .= "\04" . str_pad(base64UrlDecode($jwk['x']), $length, "\0", STR_PAD_LEFT)
        . str_pad(base64UrlDecode($jwk['y']), $length, "\0", STR_PAD_LEFT);

    $pem = '-----BEGIN EC PRIVATE KEY-----' . PHP_EOL;
    $pem .= chunk_split(base64_encode($der), 64, PHP_EOL);
    $pem .= '-----END EC PRIVATE KEY-----' . PHP_EOL;

    openssl_sign($input, $signature, $pem, 'sha256');

    return sprintf("%s.%s", $input, base64UrlEncode(fromAsn1($signature, 64)));
}

$secretKey = '
        {
            "kty":"EC","alg":"ES256","crv":"P-256",
            "x":"pSH0jvbtVZiseTpJZk0_yfudEIv86uwjeH_gr1qmOGA",
            "y":"eGdC9EIGmhCheM_T8vhS4Qwk7RfaPRBxF3W5omgBc_M",
            "d":"DuSjR5eZBp5S-9HNKA8kRQFA_3Akkept-dTbwFoq_3w"
        }
';

$jwk = json_decode($secretKey, true);

$headers["typ"] = 'JWT';
$headers["alg"] = 'ES256';
$headers["kid"] = '1';

$claims["email"] = 'bitzlato.demo@gmail.com';
$claims["aud"] = array('usr');
$claims["iat"] = time();
$claims["jti"] = "" . rand() . "";

$header = base64UrlEncode(json_encode($headers, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
$payload = base64UrlEncode(json_encode($claims, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
$unsignedToken = $header . '.' . $payload;

$jws = sign($jwk, $unsignedToken);

echo 'JWT: ' . $jws . "\n";

$curl = curl_init();
// Set some options - we are passing in a useragent too here
curl_setopt_array($curl, [
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => 'https://www.bitzlato.com/api/auth/whoami',
    CURLOPT_HTTPHEADER => array(
        "Authorization: Bearer $jws"
    )
]);
// Send the request & save response to $resp
$response = curl_exec($curl);

$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

// Close request to clear up some resources
curl_close($curl);

echo $httpCode . ' ' . $response;

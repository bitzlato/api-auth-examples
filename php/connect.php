<?php

require_once('vendor/autoload.php');

use Jose\Component\Core\AlgorithmManager;
use Jose\Component\Core\JWK;
use Jose\Component\Signature\Algorithm\ES256;
use Jose\Component\Signature\Algorithm\PS256;
use Jose\Easy\Build;

// secret user key  | секретный ключ пользователя
$secretKey = '
        {
            "kty":"EC","alg":"ES256","crv":"P-256",
            "x":"pSH0jvbtVZiseTpJZk0_yfudEIv86uwjeH_gr1qmOGA",
            "y":"eGdC9EIGmhCheM_T8vhS4Qwk7RfaPRBxF3W5omgBc_M",
            "d":"DuSjR5eZBp5S-9HNKA8kRQFA_3Akkept-dTbwFoq_3w"
        }
';

// create jwk instance from given secret user key | создаем экземпляр jwk из полученного секретного пользовательского ключа
$es256 = new ES256();
$jwk = JWK::createFromJson($secretKey);
$am = new AlgorithmManager([$es256]);

// We build token with user claims | Собираем токен с пользовательскими claim
$jws = Build::jws() // We build a JWS
    ->iat(time())
    ->jti(rand())
    ->alg($es256)
    ->aud('usr')
    // user identity, one of: email, uid (user id), subject, tgid (telegram id)
    // идентификатор пользователя, одно из: email, uid (user id), subject, tgid (telegram id)
    // Email won't work in case when a user has two accounts with the same email, prefer user id (uid) for such case.
    // Email не будет работать в случае если у пользователя два аккаунта с один и тем же email (auth0 & google)
    // Используйте uid в таких случаях
    ->claim('email', 'bitzlato.demo@gmail.com')
    // You omit this header if the account has a single key
    // Этот заголовок можно не указывать, если у аккаунта единственный ключ
    ->header('kid', '2')
    ->sign($jwk) // Sign the token with the given JWK
;

echo "JWT:" . $jws . "\n";

// initialize curl for future usage | инициализируем curl для последующего использования
$curl = curl_init();

// set options for curl connection | устанавлием параметры для curl соединения
curl_setopt_array($curl, [
    // turn on result displaying
    CURLOPT_RETURNTRANSFER => 1,
    // url of request || url запроса
    CURLOPT_URL => 'https://www.bitzlato.com/api/auth/whoami',
    // adding token to headers | добавляем токен в заголовки
    CURLOPT_HTTPHEADER => array(
        "Authorization: Bearer $jws"
    )
]);
// save response to value | сохраняем ответ в переменную
$response = curl_exec($curl);

// receive code of response | получаем код ответа
$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

// close connection | закрываем соединение
curl_close($curl);

// displaying result | выводим результат
echo $httpcode . ' ' . $response;

<?php

use Jose\Component\Core\JWK;
use Jose\Easy\Build;

require_once('vendor/autoload.php');

// secret user key  | секретный ключ пользователя
$secretKey = '{"kty":"EC","alg":"ES256","crv":"P-256","x":"0NGddmbyrbwwezAiEnHEbSkxZtOKXAKBolr69M1IND8","y":"WBkjcp08z2nBRAqwaQLwc9BXIEdZLsFlyOcT9Ra52dk","d":"QDnTruzH8W7DpDDP4dzqYv3N4fI5V0u2fwJ8x5xSZpk"}';

$time = time();
// create jwk instance from given secret user key | создаем экземпляр jwk из полученного секретного пользовательского ключа
$jwk = JWK::createFromJson($secretKey);

// We build token with user claims | Собираем токен с пользовательскими claim
$jws = Build::jws()
    ### leave as is | оставьте как есть
    ->typ("JWT", true)
    ->alg('ES256', true)
    ->aud("usr")
    ###

    // user identifier | идентификатор пользователя
    ->claim('email', 'testuset@test.com')

    // token issued at time | время когда выпущен токен
    ->iat($time)

    // unique token identifier | уникальный идентификатор токена
    ->jti($time * 1000)

    // Sign the token with the given secret key | Подписываем токен полученным секретным ключом
    ->sign($jwk)
;

// initialize curl for future usage | инициализируем curl для последующего использования
$curl = curl_init();

// set options for curl connection | устанавлием параметры для curl соединения
curl_setopt_array($curl, [
    // turn on result displaying
    CURLOPT_RETURNTRANSFER => 1,
    // url of request || url запроса
    CURLOPT_URL => 'https://demo.bitzlato.com/api/auth/whoami',
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
echo $httpcode. ' ' . $response;

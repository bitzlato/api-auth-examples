const axios = require('axios')
const jose = require('node-jose')

// секретный ключ пользователя (private key). ID данного ключа (kid) = "1",
// его вам выдали при постановке ключа. Или же его потом можно найти в настройках в web интерфейсе
const secret =
    {
        "kty":"EC",
        "alg":"ES256",
        "crv":"P-256",
        "x":"pSH0jvbtVZiseTpJZk0_yfudEIv86uwjeH_gr1qmOGA",
        "y":"eGdC9EIGmhCheM_T8vhS4Qwk7RfaPRBxF3W5omgBc_M",
        "d":"DuSjR5eZBp5S-9HNKA8kRQFA_3Akkept-dTbwFoq_3w"
    }

// обязательные claims
// required claims
const claims = {
    // user identity, one of: email, uid (user id), subject, tgid (telegram id)
    // идентификатор пользователя, одно из: email, uid (user id), subject, tgid (telegram id)
    // Email won't work in case when a user has two accounts with the same email, prefer user id (uid) for such case.
    // Email не будет работать в случае если у пользователя два аккаунта с один и тем же email (auth0 & google)
    // Используйте uid в таких случаях
    email: "bitzlato.demo@gmail.com",
    // leave as is
    // оставляем как есть
    aud: "usr",
    // token issue time
    // время когда выпущен токен
    iat: (Date.now() / 1000) | 0,
    // unique token identifier
    // уникальный идентификатор токена
    jti: jose.util.randomBytes(8).toString('base64')
}

async function connect() {
    // generate jwt token from users' private key
    // генерируем jwt token из секретного ключа пользователя
    const jwt = await jose.JWS.createSign(
        {
            alg: 'ES256',
            format: 'compact',
            // You omit this header if the account has a single key
            // Этот заголовок можно не указывать, если у аккаунта единственный ключ
            fields: { kid: "1" }
        },
        {key: secret, reference: false},
    )
        .update(JSON.stringify(claims))
        .final()

    // set auth header with token
    // установливаем заголовок аутентификации с токеном
    const options = {
        headers: {Authorization: `Bearer ${jwt}`},
    }

    // change to bitzlato api host
    // изменить на хост api bitzlato
    axios.get('https://www.bitzlato.com/api/auth/whoami', options)
        .then(response => {
            console.log(response.data)
        })
}

connect().then(_ => _);



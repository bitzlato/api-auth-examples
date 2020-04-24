const axios = require('axios')
const jose = require('node-jose')


// секретный ключ пользователя 
const secret = 
	{"kty":"EC","alg":"ES256","crv":"P-256","x":"lu122ea3dpr4ZxigdS7pZzfLBlR2SeyPJe8EDdkdVa4","y":"JckGgFiNR2bmzsI2-cxcIY--zXkTzMYD715b386H_pw","d":"v-R0HYNXQi0KzxG9U_7cOk0QGn7hVslvVOUzqBvGdR8"} 
	// обязательные claims
// requred claims
const claims = {
    // user identificator
    // идентификатор пользователя
    email: "testuser@test.com",
    // leave as is
    // оставляем как есть
    aud: "usr",
    // token issue time
    // время когда выпущен токен
    iat: (Date.now() / 1000) | 0,
    // unique token identificator
    // уникальный идентификатор токена
    jti: jose.util.randomBytes(8).toString('base64')
}

async function connect() {
    // generate jwt token from user secret key
    // генерируем jwt token из серктеного ключа пользователя 
    const jwt = await jose.JWS.createSign(
        { alg: 'ES256', format: 'compact' },
        { key: secret, reference: false },
    )
        .update(JSON.stringify(claims))
        .final()

    // set auth header with token
    // установливаем заголовок аутентификации с токеном
    const options = {
        headers: { Authorization: `Bearer ${jwt}` },
    }

    // change to bitzlato api host
    // изменить на хост api bitzlato
    axios.get('https://demo.bitzlato.com/api/auth/whoami', options)
        .then(response => {
            console.log(response)
        })
}

connect();



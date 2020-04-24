import http
import python_http_client
import datetime
import time

from jose import jwt, jws
from jose.constants import ALGORITHMS


# secret user key 
secret2 =   {"kty":"EC","alg":"ES256","crv":"P-256","x":"s1kv2ea3dpr4ZxigdS7pZzfLBlR2SeyPJe8EDdkdVa4","y":"JckGgFiNR2bmzsI2-cxcIY--zXkTzMYD715b386H_pw","d":"v-R0HYNXQi0KzxG9U_7cOk0QGn7hVslvVOUzqBvGdR8"}

def main():
    dt = datetime.datetime.now()
    ts = time.mktime(dt.timetuple())
    ms = str(int(round(time.time() * 1000)))
    claims = {
        # user identificator
        "email": "testuser@test.com",
        # leave as is
        "aud": "usr",
        # token issue time
        "iat": int(ts),
        # unique token identificator
        "jti": ms
    }
    # make token with claims from secret user key
    token = jws.sign(claims, secret2, algorithm=ALGORITHMS.ES256)

    conn = http.client.HTTPSConnection("demo.bitzlato.com", 443)

    # make  request against api endpoint with Authorization: Bearer <token> header
    res = conn.request("GET", "/api/auth/whoami", headers = {
        "Authorization": "Bearer " + token
    })
    r1 = conn.getresponse()
    print(r1.status, r1.reason, r1.read())


if __name__ == '__main__':
    main()

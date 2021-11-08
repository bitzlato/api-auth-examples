import datetime
import http
import python_http_client
import time
import random
from jose import jws
from jose.constants import ALGORITHMS

# secret user key
key = {
    "kty": "EC",
    "alg": "ES256",
    "crv": "P-256",
    "x": "pSH0jvbtVZiseTpJZk0_yfudEIv86uwjeH_gr1qmOGA",
    "y": "eGdC9EIGmhCheM_T8vhS4Qwk7RfaPRBxF3W5omgBc_M",
    "d": "DuSjR5eZBp5S-9HNKA8kRQFA_3Akkept-dTbwFoq_3w"
}


def main():
    dt = datetime.datetime.now()
    ts = time.mktime(dt.timetuple())
    claims = {
        # user identifier
        "email": "bitzlato.demo@gmail.com",
        # leave as is
        "aud": "usr",
        # token issue time
        "iat": int(ts),
        # unique token identifier
        "jti": hex(random.getrandbits(64))
    }
    print(claims)
    # make token with claims from secret user key
    # put the correct key id (kid)
    token = jws.sign(claims, key, headers={"kid": "1"}, algorithm=ALGORITHMS.ES256)

    conn = http.client.HTTPSConnection("www.bitzlato.com", 443)

    # make  request against api endpoint with Authorization: Bearer <token> header
    res = conn.request("GET", "/api/auth/whoami", headers={
        "Authorization": "Bearer " + token
    })
    r1 = conn.getresponse()
    print(r1.status, r1.reason, r1.read())


if __name__ == '__main__':
    main()

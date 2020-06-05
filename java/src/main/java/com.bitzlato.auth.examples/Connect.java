package com.bitzlato.auth.examples;

import com.nimbusds.jose.JOSEException;
import com.nimbusds.jose.JWSHeader;
import com.nimbusds.jose.crypto.ECDSASigner;
import com.nimbusds.jose.jwk.ECKey;
import com.nimbusds.jwt.JWTClaimsSet;
import com.nimbusds.jwt.SignedJWT;

import java.net.URI;
import java.net.http.HttpClient;
import java.net.http.HttpRequest;
import java.net.http.HttpResponse;
import java.net.http.HttpResponse.BodyHandlers;
import java.text.ParseException;
import java.time.Duration;
import java.time.Instant;
import java.util.Date;
import java.util.Random;
import java.util.concurrent.ExecutionException;
import java.util.function.Supplier;

import static com.nimbusds.jose.JWSAlgorithm.ES256;
import static java.lang.Character.MAX_RADIX;
import static java.net.http.HttpClient.Version;
import static java.net.http.HttpClient.newBuilder;

public class Connect {

    static class TokenGenerator implements Supplier<String> {
        /**
         * This is your private key. This key has id = 2
         */
        private final String jsonWebKey = "{" +
                "\"kty\":\"EC\",\"alg\":\"ES256\",\"crv\":\"P-256\"," +
                "\"x\":\"pSH0jvbtVZiseTpJZk0_yfudEIv86uwjeH_gr1qmOGA\"," +
                "\"y\":\"eGdC9EIGmhCheM_T8vhS4Qwk7RfaPRBxF3W5omgBc_M\"," +
                "\"d\":\"DuSjR5eZBp5S-9HNKA8kRQFA_3Akkept-dTbwFoq_3w\"" +
                "}";

        private final JWSHeader jwsHeader = new JWSHeader.Builder(ES256)
                // You must provide the correct kid if an account has more than one key
                // see https://tools.ietf.org/html/rfc7515#section-4.1.4
                .keyID("2")
                .build();

        private final ECDSASigner signer;

        {
            try {
                signer = new ECDSASigner(ECKey.parse(jsonWebKey));
            } catch (ParseException | JOSEException e) {
                throw new IllegalArgumentException(e);
            }
        }

        private final Random rnd = new Random();

        @Override
        public String get() {
            final var claims = new JWTClaimsSet.Builder()
                    .audience("usr")
                    .jwtID(Long.toString(rnd.nextLong(), MAX_RADIX))
                    .issueTime(Date.from(Instant.now()))
                    // choose one of methods of user identification

                    // By email
                    // Note: won't work if ones' has two accounts with the same email.
                    // .claim("email", uniqueEmail)

                    // always works: `User Id`
                    .claim("uid", 13491868)

                    // telegram id -- if you have it (and know),
                    // note that telegram and web accounts are not necessarily same
                    // (you should perform accounts merge, to accomplish that)
                    // .claim("tgid", 10004389385)
                    .build();


            try {
                final var jwt = new SignedJWT(jwsHeader, claims);
                jwt.sign(signer);
                final var jwtString = jwt.serialize();
                System.out.println("JWT=" + jwtString);
                return jwtString;
            } catch (JOSEException e) {
                throw new IllegalStateException(e);
            }
        }
    }


    private static final URI baseUrl = URI.create("https://demo.bitzlato.com/api/p2p/");

    public static void main(String[] args) throws ExecutionException, InterruptedException {
        final var tokenGen = new TokenGenerator();

        final var client = newBuilder().version(Version.HTTP_2).build();


        final var req = HttpRequest.newBuilder()
                .uri(baseUrl.resolve("wallets/v2/"))
                .timeout(Duration.ofSeconds(5))
                .header("Authorization", "Bearer " + tokenGen.get())
                .GET()
                .build();
        System.out.println("Request=" + req.toString());

        client.sendAsync(req, BodyHandlers.ofString())
                .thenApply(response -> {
                    System.out.println(response.statusCode());
                    return response;
                } )
                .thenApply(HttpResponse::body)
                .thenAccept(System.out::println)
        .get();
    }
}

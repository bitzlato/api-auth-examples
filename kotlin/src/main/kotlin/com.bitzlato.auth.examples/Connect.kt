package com.bitzlato.auth.examples

import com.nimbusds.jose.JWSAlgorithm
import com.nimbusds.jose.JWSHeader
import com.nimbusds.jose.crypto.ECDSASigner
import com.nimbusds.jose.jwk.ECKey
import com.nimbusds.jwt.JWTClaimsSet
import com.nimbusds.jwt.SignedJWT
import java.net.URI
import java.net.http.HttpClient
import java.net.http.HttpRequest
import java.net.http.HttpResponse
import java.net.http.HttpResponse.BodyHandlers
import java.time.Duration
import java.time.Instant
import java.util.*

class TokenGenerator : () -> String {
    /**
     * This is your private key. This key has id = 1 (kid)
     */
    private val jsonWebKey = """
        {
            "kty":"EC","alg":"ES256","crv":"P-256",
            "x":"pSH0jvbtVZiseTpJZk0_yfudEIv86uwjeH_gr1qmOGA",
            "y":"eGdC9EIGmhCheM_T8vhS4Qwk7RfaPRBxF3W5omgBc_M",
            "d":"DuSjR5eZBp5S-9HNKA8kRQFA_3Akkept-dTbwFoq_3w"
        }
    """.trimIndent()
    private val kid = 1
    private val userId = 13491868

    private val jwsHeader = JWSHeader.Builder(JWSAlgorithm.ES256)
            // You must provide the correct kid if an account has more than one key
            // see https://tools.ietf.org/html/rfc7515#section-4.1.4
            // You may omit this header in case when a user has only a single key.
            .keyID(kid.toString())
            .build()

    private val signer: ECDSASigner = ECDSASigner(ECKey.parse(jsonWebKey))
    private val rnd = Random()

    override fun invoke(): String {
        val claims = JWTClaimsSet.Builder()
                .audience("usr")
                .jwtID(rnd.nextLong().toString(Character.MAX_RADIX))
                .issueTime(Date.from(Instant.now())) // choose one of methods of user identification
                // By email
                // Note: won't work if ones' has two accounts with the same email.
                // .claim("email", uniqueEmail)
                // always works: `User Id`
                .claim("uid", userId)
                // telegram id -- if you have it (and know),
                // note that telegram and web accounts are not necessarily same
                // (you should perform accounts merge, to accomplish that)
                // .claim("tgid", 10004389385)
                .build()
        return SignedJWT(jwsHeader, claims).run {
            sign(signer)
            serialize().also {
                println("JWT=$it")
            }
        }
    }
}

private val baseUrl = URI.create("https://www.bitzlato.com/api/p2p/")

fun main() {
    val tokenGen = TokenGenerator()
    val client = HttpClient.newBuilder().version(HttpClient.Version.HTTP_2).build()
    val req = HttpRequest.newBuilder()
            .uri(baseUrl.resolve("wallets/v2/"))
            .timeout(Duration.ofSeconds(5))
            .header("Authorization", "Bearer ${tokenGen()}")
            .GET()
            .build()
    println("Request=$req")
    client.sendAsync(req, BodyHandlers.ofString())
            .thenApply { response: HttpResponse<String?> ->
                println(response.statusCode())
                response
            }
            .thenApply { obj: HttpResponse<String?> -> obj.body() }
            .thenAccept { x: String? -> println(x) }
            .get()
}

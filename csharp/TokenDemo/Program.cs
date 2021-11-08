using System;
using System.Net.Http;
using System.Net.Http.Headers;
using System.Threading.Tasks;
using JsonWebToken;

namespace TokenDemo
{
    internal static class Program
    {
        /**
         * This is your private key. This key has id = 1
         */
        private const string PrivKey = "{" +
                                       "\"kty\":\"EC\",\"alg\":\"ES256\",\"crv\":\"P-256\"," +
                                       "\"x\":\"pSH0jvbtVZiseTpJZk0_yfudEIv86uwjeH_gr1qmOGA\"," +
                                       "\"y\":\"eGdC9EIGmhCheM_T8vhS4Qwk7RfaPRBxF3W5omgBc_M\"," +
                                       "\"d\":\"DuSjR5eZBp5S-9HNKA8kRQFA_3Akkept-dTbwFoq_3w\"" +
                                       "}";
        private const string Email = "bitzlato.demo@gmail.com";

        private static readonly Random Rnd = new Random();

        private static void Main()
        {
            var privJwk = Jwk.FromJson(PrivKey);

            var descriptor = new JwsDescriptor()
            {
                Algorithm = SignatureAlgorithm.EcdsaSha256,
                SigningKey = privJwk,
                IssuedAt = DateTime.UtcNow,
                Audience = "usr",
                JwtId = Rnd.Next().ToString("X"),
                // Optionally set kid if you've got more than one key. Provide the correct int value.
                // see: https://tools.ietf.org/html/rfc7515#section-4.1.4
                KeyId = 1.ToString()
            };
            descriptor.AddClaim("email", Email);

            var writer = new JwtWriter();
            var token = writer.WriteTokenString(descriptor);

            Console.WriteLine("JWT content:");
            Console.WriteLine(descriptor);
            Console.WriteLine();
            Console.WriteLine("JWT:");
            Console.WriteLine(token);

            var httpClient = new HttpClient();
            httpClient.DefaultRequestHeaders.Authorization =
                new AuthenticationHeaderValue("Bearer", token);

            var who = Task.Run(async () => await
                httpClient.GetStringAsync("https://www.bitzlato.com/api/auth/whoami")
            ).GetAwaiter().GetResult();

            Console.WriteLine("Who am I:");
            Console.WriteLine(who);
        }
    }
}

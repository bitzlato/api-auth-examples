using System;
using System.Net.Http;
using System.Net.Http.Headers;
using System.Threading.Tasks;
using JsonWebToken;

namespace TokenDemo
{
    class Program
    {
        private const string privkey =
            "{\"kty\":\"EC\",\"alg\":\"ES256\",\"crv\":\"P-256\"," +
            "\"x\":\"lmzBhWRXSZTx5q6b80PK_GL7b94YYXI1hNB3YdJ6bzQ\",\"y\":\"sXwmQX8sAm5yoybyq0RvMMhQp7Ox4lvhdpy_xPjfs58\"," +
            "\"d\":\"P_MepPu0IPJEIQ_WUL5p12qb-phfMBdiDC8vHgdwvJ4\"}";

        private const string email = "hajife8896@iopmail.com";

        private static readonly Random rnd = new Random();

        static void Main(string[] args)
        {
            var privJwk = ECJwk.FromJson(privkey);

            var descriptor = new JwsDescriptor()
            {
                Algorithm = SignatureAlgorithm.EcdsaSha256,
                SigningKey = privJwk,
                IssuedAt = DateTime.UtcNow,
                Audience = "usr",
                JwtId = rnd.Next().ToString("X")
                // optionally set kid if you've got more than one key: KeyId = 1
            };
            descriptor.AddClaim("email", email);

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
                httpClient.GetStringAsync("https://demo.bitzlato.com/api/auth/whoami")
            ).GetAwaiter().GetResult();

            Console.WriteLine("Who am I:");
            Console.WriteLine(who);
        }
    }
}
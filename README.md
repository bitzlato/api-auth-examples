### Description

A guide about the access to the Bitzlato API

***

### Table of Contents

**Getting access to the API**

To gain access to the API, you need to generate the user's secret key for the API token in the user's personal account on the website https://bitzlato.bz/p2p.

**How to get**:

1. Log in on the [website](https://bitzlato.bz/p2p);
1. Open the "My profile" menu, the "Security" tab;
1. Enable two-factor authentication (compulsory condition);
1. In the "API Management" section, click "Create Key";
1. Check the box "Active" if you want to use the key immediately;
1. Enter the name of the key;
1. Select rights for the key:
    * Read allows you to view the profile data;    
    * Trade allows you to conduct transactions, create / modify ads;    
    * Money allows you to create checks and withdraw funds;    
    * Rights can be combined in any order and do not inherit rights from other keys, that is, a key with Read and Money rights will not be able to create an advertisement and conduct a transaction.

1. Click “Generate new key”;
1. The user's secret key will be shown, you need to write it down/save on your computer. If you have lost the secret key, then you need to generate a new one;
1. To save the public key on the server, click "Send the public key to the server".


***Until the key is sent to the server, they will not be able to authorize!***

After that, you can use the user's secret key to sign each request sent to the API endpoints. With each request to the API, the client executable code signs the token with the user's secret key, and the service backend verifies the signature against the public key, and if the signature is correct, it allows the request. API connection examples are available here: [https://github.bz/bitzlato/api-auth-examples]()

**How to transfer your account to another person?**<br />
To transfer an account into trust managing, it is enough to provide the user's secret key and your mail. After receiving the private key, the user who received the key will be able to carry out all the actions, the rights to which you provided when creating the key.


**The service is not responsible for operations carried out using keys transferred to a third party for management!**


Secret key type:
```json
{
   "kty":"EC", 
   "alg":"ES256", 
   "crv":"P-256", 
   "x":"EjDTE4kXWR1vOuWkFyZNgm_82ACJUzJVpMSowHFqxP0", 
   "y":"jP3uNx4dhddy4hDJ3EJcQBnbqFB604ACY1TOAzzQ-rw", 
   "d":"0NeSRzoCcB HmHCIhZPvDPCn6vU25aOsfe5Fvk_VEP2E"
}
```

## Examples

Repo has connection examples in a several languages:

* [C#](csharp)
* [Java](java)
* [JavaScript](js)
* [Kotlin](kotlin)
* [Python](python)
* [PHP](php)
* [PHP without external libraries](php-no-libs)

## API Documentation

You can check out [API Docs](docs/en/api) in English.

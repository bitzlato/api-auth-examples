# Manual usage

How to run:

```
# Install ruby gems
bundle

# Setup settings
export BITZLATO_API_KEY='{"kty":"EC","alg":"ES256","crv":"P-256","x":"wwf6h_sZhv6TXAYz4XrdXZVpLo_uoNESbaEf_zEydus","y":"OL-0AqcTNoaCBVAEpDNsU1bpZA7eQ9CtGPZGmEEg5QI","d":"nDTvKjSPQ4UAPiBmJKXeF1MKhuhLtjJtW6hypstWolk"}'
export BITZLATO_API_URL=https://demo.bitzlato.bz
export BITZLATO_API_CLIENT_UID=12

# Requests examples:
./example.rb get /api/auth/whoami
./example.rb post /api/gate/v1/invoices/ '{"cryptocurrency":"BTC", "amount":"0.002","comment":"test invoice 2"}'
```

# Using gem

There are also exists `gem bitzlato` - https://rubygems.org/gems/bitzlato

Source: https://github.com/finfex/bitzlato

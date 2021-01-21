## **Get a list of offers according to filtering options**

Returns json data about a list of offers

**URL**

*/api/p2p/public/exchange/dsa/*

**METHOD**

*GET*

**URL Parameters**

**Required:** 

`cryptocurrency=[string]` 

`type=[string]`

**Optional:**

`amount=[bigdecimal]`

`amountType=[string]`

`currency=[string]`

`isOwnerActive=[boolean]`

`isOwnerVerificated=[boolean]`

`lang=[string]`

`limit=[integer]`

`skip=[integer]`

`paymethod=[string]`

**Success Response**

**Code**: 200 **Content**:
```json
{
  "data": [
    {
    "cryptocurrency": String,
    "currency": String,
    "id": Int,
    "isOwnerVerificated": boolean,
    
    "limitCryptocurrency": {
        "max": String,
        "min": String,
        "realMax": String
    },
    
    "limitCurrency": {
        "max": String,
        "min": String,
        "realMax": String
    },
    "owner": String,
    "ownerBalance": BigDecimal,
    "ownerLastActivity": Timestamp,
    "ownerTrusted": boolean,
    "paymethod": {
        "id": Int,
        "name": String
    },
    "paymethodId": Int,
    "rate": String,
    "safeMode": boolean,
    "type": String
    }
  ],
  "total": Int
}
```
## **Getting a description of a user's wallet**

**URL**

*/api/p2p/wallets/:cryptocurrency*

**METHOD**

*GET*

**URL Parameters**

**Optional:**

`currency: [string]` - currency code

**Success Response**

**Code**: 200 **Content**:
```json
{
  "cryptocurrency": String,
  "balance": String,
  "holdBalance": String,
  "address": String,
  "createdAt": Timestamp,
  "worth": {
    "currency": String,
    "value": String,
    "holdValue": String
  }
}
```


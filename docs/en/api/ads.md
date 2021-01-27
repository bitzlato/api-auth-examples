## **Create a new ad**

Creating a new ad

**URL**

*/api/p2p/dsa/*

**METHOD**

*POST*

**URL Parameters**

*none*

**Data Parameters**

```json
{
  "type": String,
  "cryptocurrency": String,
  "paymethod": Int,
  "minAmount": Int,
  "maxAmount": Int,
  "terms": String,
  "details": String,
  "maxLimitForNewTrader": BigDecimal,
  "minPartnerTradesAmount": BigDecimal,
  "verifiedOnly": Boolean,
  "liquidityLimit": Boolean,
  "rateValue": BigDecimal,
  "ratePercent": BigDecimal
}
```

* Fields with *?* are optional
* *maxAmount* and *minAmount* should not be less than 0
* *maxAmount* should be greater than *minAmount*
* *rateValue* or *ratePercent* must be specified, *ratePercent* or *rateValue* must be *null* respectively
* *rateValue* can not be less than 0.01

**Success Response**

**Code**: 200 **Content**: 
```json
{
  "cryptocurrency": string,
  "deepLinkCode": string,
  "details": string,
  "id": Int,
  "links": null,
  "position": null,
  "liquidityLimit": boolean,
  "maxAmount": string,
  "maxLimitForNewTrader": string,
  "minAmount": string,
  "minPartnerTradesAmount": string,
  "ownerLastActivity": timestamp,
  "paymethod": Int,
  "paymethod_currency": string,
  "paymethod_description": string,
  "ratePercent": string,
  "rateValue": string,
  "status": string,
  "terms": string,
  "type": string,
  "unactiveReason": string,
  "verifiedOnly": boolean
}
```

**Error Response**

* **Code**: 496 ZeroValue 
  
    **Content**: 
    ```json
        {
          "statusCode": 496,
          "code": "ZeroValue"
        }
    ```
  
***

## **Get a list of user's ads**

Getting a list of user's ads

**URL**

*/api/p2p/dsa/all*

**METHOD**

*GET*

**URL Parameters**

**Required:**

*none*

**Optional:**

`cryptocurrency = [string]` - Cryptocurrency code

`currency = [string]` - Currency code

`lang = [string]` - The language parameter of the returned data. **Default** - "en"

**Success Response**

**Code**: 200 **Content**:
```json
[
  {
    "cryptocurrency": string,
    "deepLinkCode": string,
    "details": string,
    "id": Int,
    "links": null,
    "position": null,
    "liquidityLimit": boolean,
    "maxAmount": string,
    "maxLimitForNewTrader": string,
    "minAmount": string,
    "minPartnerTradesAmount": string,
    "ownerLastActivity": timestamp,
    "paymethod": Int,
    "paymethod_currency": string,
    "paymethod_description": string,
    "ratePercent": string,
    "rateValue": string,
    "status": string,
    "terms": string,
    "type": string,
    "unactiveReason": string,
    "verifiedOnly": boolean
  }
]
```

***

## **Change rate in all ads**

**URL**

*/api/p2p/dsa/changerates*

**METHOD**

*PUT*

**Data Parameters**

```json
{
    "currency": String,
    "cryptocurrency": String,
    "rateValue": String,
    "ratePercent": String,
    "type": String
}
```

* *rateValue* or *ratePercent* must be specified, *ratePercent* or *rateValue* must be *null* respectively
* Available types: "purchase", "selling"

**Success Response**

**Code**: 200 **Content**: *none*

***

## **Get list of payment methods available to the user**

**URL**

*/api/p2p/dsa/paymethods/:type/:currency/:cryptocurrency/*

**METHOD**

*GET*

**URL Parameters**

**Required:**

`cryptocurrency = [string]` - Filter by cryptocurrency code

`currency = [string]` - Filter by currency code

`type = [string]` - Payment type. **Available types**: "purchase", "selling"

**Optional**:

`paymentGroupId = [Int]` - Payment group ID

`lang = [string]` - The language parameter of the returned data. **Default** - "en"

**Success Response**

**Code**: 200 **Content**:

```json
[
  {
    "id": Int,
    "currency":  String,
    "description": String
  }
]
```

***

## **Change the status of all your ads**

**URL**

*/api/p2p/dsa/set-status-bulk/*

**METHOD**

*POST*

**Data Parameters**

**Required**:

```json
{
  "type": String,
  "status": String,
  "cryptocurrency": String
}
```

* Available *types*: "purchase", "selling"
* Available *statuses*: "active", "pause"

**Success Response**

**Code**: 200 **Content**:

```json
[
  {
    "cryptocurrency": string,
    "deepLinkCode": string,
    "details": string,
    "id": Int,
    "links": null,
    "position": null,
    "liquidityLimit": boolean,
    "maxAmount": string,
    "maxLimitForNewTrader": string,
    "minAmount": string,
    "minPartnerTradesAmount": string,
    "ownerLastActivity": timestamp,
    "paymethod": Int,
    "paymethod_currency": string,
    "paymethod_description": string,
    "ratePercent": string,
    "rateValue": string,
    "status": string,
    "terms": string,
    "type": string,
    "unactiveReason": string,
    "verifiedOnly": boolean
  }
]
```

***

## **Get ad description**

**URL**

*/api/p2p/dsa/:advertId*

**METHOD**

*GET*

**URL Parameters**

**Required:**

`advertId: [integer]` - ad number

**Success Response**

**Code**: 200 **Content**:
```json
{
    "cryptocurrency": string,
    "deepLinkCode": string,
    "details": string,
    "id": Int,
    "links": [
      {
        "type:": string,
        "url": string
      }
    ],
    "position": null,
    "liquidityLimit": boolean,
    "maxAmount": string,
    "maxLimitForNewTrader": string,
    "minAmount": string,
    "minPartnerTradesAmount": string,
    "ownerLastActivity": timestamp,
    "paymethod": Int,
    "paymethod_currency": string,
    "paymethod_description": string,
    "ratePercent": string,
    "rateValue": string,
    "status": string,
    "terms": string,
    "type": string,
    "unactiveReason": string,
    "verifiedOnly": boolean
  }
```

***

## **Change ad description**

**URL**

*/api/p2p/dsa/:advertId*

**METHOD**

*PUT*

**URL Parameters**

**Required:**

`advertId: [integer]` - ad number

**Data Parameters**

**Optional**

```json
{
  "minAmount": Int,
  "maxAmount": Int,
  "terms": String,
  "details": String,
  "maxLimitForNewTrader": BigDecimal,
  "minPartnerTradesAmount": BigDecimal,
  "verifiedOnly": Boolean,
  "liquidityLimit": Boolean,
  "rateValue": BigDecimal,
  "ratePercent": BigDecimal
}
```

*rateValue* or *ratePercent* must be specified, *ratePercent* or *rateValue* must be *null* respectively

**Success Response**

**Code**: 200 **Content**:

```json
{
  "type": String,
  "cryptocurrency": String,
  "paymethod": Int,
  "minAmount": Int,
  "maxAmount": Int,
  "terms": String,
  "details": String,
  "maxLimitForNewTrader": BigDecimal,
  "minPartnerTradesAmount": BigDecimal,
  "verifiedOnly": Boolean,
  "liquidityLimit": Boolean,
  "rateValue": BigDecimal,
  "ratePercent": BigDecimal
}
```

***

## **Removing an ad**

**URL**

*/api/p2p/dsa/:advertId*

**METHOD**

*DELETE*

**URL Parameters**

**Required:**

`advertId: [integer]` - ad number

**Success Response**

**Code**: 200 **Content**: *none*

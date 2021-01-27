##  **Creating a new deal**

Returns json data about a created trade

**URL**

*/api/p2p/trade*

**METHOD**

*POST*

**Data Parameters**

```json
{
  "advertId": integer,
  "amount": bigdecimal,
  "amountType": string,
  "counterDetails": string?,
  "rate": string?
}
```

* Fields with *?* in Data Parameters are optional
* *counterDetails* is required if trade type is "purchase"

**Success Response**

**Code**: 200 **Content**: 

```json
{
    "availableActions": [string],
    "counterDetails": string,
    "cryptocurrency": string,
    "currency": string,
    "details": string,
    "disputeAvailable": boolean,
    "fee": string,
    "history": [{
        "date": timestamp,
        "status": string
    }],
    "id": integer,
    "owner": boolean,
    "partner": string,
    "rate": string,
    "status": string,
    "terms": string,
    "times": {
      "autocancel": timestamp,
      "created": timestamp,
      "dispute": timestamp
    },
    "type": string,
    "waitingTimeIncreased": boolean
}
```

**Error Response**

* **Code**: 400 NOTALLOWEDVALUE

    **Content**: 
    ```json
    {
      "statusCode": 400,
      "code": "NotAllowedValue"
    }
    ```

* **Code**: 499 OPERATIONISFROZEN

    **Content**:
    ```json
    {
      "statusCode": 499,
      "code": "OperationIsFrozen"
    }
    ```

* **Code**: 472 MAINTENANCEENABLED

    **Content**:
    ```json
    {
      "statusCode": 472,
      "code": "MaintenanceEnabled"
    }
    ```

* **Code**: 476 ADVERTUNAVAILABLE

    **Content**:
    ```json
    {
      "statusCode": 476,
      "code": "AdvertUnavailable"
    }
    ```
  
* **Code**: 466 TRADEDETAILSREQUIRED

    **Content**:
    ```json
    {
      "statusCode": 466,
      "code": "TradeDetailsRequired"
    }
    ```
  
* **Code**: 470 AlreadyHaveTrade

    **Content**:
    ```json
    {
      "statusCode": 470,
      "code": "AlreadyHaveTrade"
    }
    ```
* **Code**: 482 TooManyTrades

    **Content**:
    ```json
    {
      "statusCode": 482,
      "code": "TooManyTrades"
    }
    ```
* **Code**: 463 TradeRateWasChanged

    **Content**:
    ```json
    {
      "statusCode": 463,
      "code": "TradeRateWasChanged"
    }
    ```
* **Code**: 485 MaxLimitForNewTrader

    **Content**:
    ```json
    {
      "statusCode": 485,
      "code": "MaxLimitForNewTrader"
    }
    ```
* **Code**: 487 MinPartnerTradesAmount

    **Content**:
    ```json
    {
      "statusCode": 487,
      "code": "MinPartnerTradesAmount"
    }
    ```

* **Code**: 473 ForbiddenTradeWithYourself

    **Content**:
    ```json
    {
      "statusCode": 473,
      "code": "ForbiddenTradeWithYourself"
    }
    ```

***

## **Getting a description of the user's active deals**

Getting a description of the user's active deals

**URL**

*/api/p2p/trade/*

**METHOD**

*GET*

**URL Parameters**

**Required:**

*none*

**Optional:**

`amountFrom: [number]` - Filtering parameter by a trade amount from

`amountTo: [number]` - Filtering parameter by a trade amount to

`amountType: [string]` - Filtering parameter by an amount type

`cryptocurrency: [string]` - Filtering returned data by cryptocurrency ticker

`dateFrom: [string]` - Filtering parameter by trade time from

`dateTo: [string]` - Filtering parameter by trade time to

`limit: [integer]` - Returned Results Limit.
**Default**: 1

`onlyClosed: [boolean]` - Filtering the returned data by only completed or canceled deals.
**Default**: false

`partner: [string]` - Filtering parameter by trade partner

`paymethod: [string]` - Filtering parameter by trade payment method

`skip: [integer]` - Return data offset.
**Default**: 0

`status: [string]` - Filtering parameter by trade status.
**Available values**: "trade_created", "confirm_trade", "payment", "confirm_payment", "dispute", "cancel"

`tradeId: [integer]` - Filtering parameter by trade ID

`type: [string]` - Filtering parameter by trade type
**Available values**: "purchase" "selling"

**Success Response**

**Code**: 200 **Content**:
```json
{
  "data": [
    {
      "cryptocurrency": {
        "amount": string,
        "code": string
      },
      "currency": {
        "amount": string,
        "code": string
      },
      "date": timestamp,
      "id": integer,
      "partner": string,
      "paymethod": integer,
      "rate": string,
      "status": string,
      "type": string
    }
  ],
  "total": integer
}
```

***

## **Getting a description of a deal**

Getting a description of a deal

**URL**

*/api/p2p/trade/:tradeId*

**METHOD**

*GET*

**URL Parameters**

**Required:**

`tradeId: [integer]` - trade number

**Success Response**

**Code**: 200 **Content**:
```json
{
  "availableActions": [string],
  "counterDetails": string,
  "cryptocurrency": string,
  "currency": string,
  "details": string,
  "disputeAvailable": boolean,
  "fee": string,
  "history": [{
    "date": timestamp,
    "status": string
  }],
  "id": integer,
  "owner": boolean,
  "partner": string,
  "rate": string,
  "status": string,
  "terms": string,
  "times": {
    "autocancel": timestamp,
    "created": timestamp,
    "dispute": timestamp
  },
  "type": string,
  "waitingTimeIncreased": boolean
}
```

***

## **Execution of actions within a trade**

**URL**

*/api/p2p/trade/:tradeId*

**METHOD**

*POST*

**URL Parameters**

**Required:**

`tradeId: [integer]` - trade number

**Header Parameters**

`X-Code-2FA: [integer]` - Two-factor authorization code to release coins 
(if you have it enabled, then it is **required**)

**Data Parameters**

```json
{
  "type": string
}
```

**Available types**: "confirm_trade", "payment", "confirm_payment", "dispute", "cancel".

Type (aka action) should be chosen according to available actions in `"availableActions": [string]` 
in trade description

**Success Response**

**Code**: 200 **Content**:
```json
{
  "availableActions": [string],
  "counterDetails": string,
  "cryptocurrency": string,
  "currency": string,
  "details": string,
  "disputeAvailable": boolean,
  "fee": string,
  "history": [{
    "date": timestamp,
    "status": string
  }],
  "id": integer,
  "owner": boolean,
  "partner": string,
  "rate": string,
  "status": string,
  "terms": string,
  "times": {
    "autocancel": timestamp,
    "created": timestamp,
    "dispute": timestamp
  },
  "type": string,
  "waitingTimeIncreased": boolean
}
```

***

## **Change trade parameters (requisites)**

**URL**

*/api/p2p/trade/:tradeId*

**METHOD**

*PUT*

**URL Parameters**

**Required:**

`tradeId: [integer]` - trade number

**Data Parameters**

```json
{
  "details": string
}
```

**Success Response**

**Code**: 200 **Content**:

```json
{
  "availableActions": [string],
  "counterDetails": string,
  "cryptocurrency": string,
  "currency": string,
  "details": string,
  "disputeAvailable": boolean,
  "fee": string,
  "history": [{
    "date": timestamp,
    "status": string
  }],
  "id": integer,
  "owner": boolean,
  "partner": string,
  "rate": string,
  "status": string,
  "terms": string,
  "times": {
    "autocancel": timestamp,
    "created": timestamp,
    "dispute": timestamp
  },
  "type": string,
  "waitingTimeIncreased": boolean
}
```

**Error Response**

* **Code**: 493 StateChangeNotAllowed

  **Content**:
    ```json
    {
      "statusCode": 493,
      "code": "StateChangeNotAllowed"
    }
    ```

**Notes**

Available if you are seller and trade status is "trade-created" or "confirm-trade", otherwise throws error. 
Also, counterDetails should be null, otherwise no effect.

***

## **Describe the reason of a dispute**

Can be called only once per dispute

**URL**

*/api/p2p/trade/:tradeId/dispute/description*

**METHOD**

*POST*

**URL Parameters**

**Required:**

`tradeId: [integer]` - trade number

**Data Parameters**

```json
{
  "description": string
}
```

**Success Response**

**Code**: 200 **Content**: `"Dispute reason saved"`

**Error Response**

**Code**: 400 **Content**: `"Dispute reason already exist"`

***

## **Allows you to rate the results of the trade**

**URL**

*/api/p2p/trade/:tradeId/feedback*

**METHOD**

*PUT*

**URL Parameters**

**Required:**

`tradeId: [integer]` - trade number

**Data Parameters**

```json
{
  "rate": string
}
```

**Available rates**: "thumb-up", "relieved", "hankey"

**Success Response**

* **Code**: 200 **Content**: `""` (empty string)

* **Code**: 200 **Content**: `"Feedback already exist"`

**Error Response**

**Code**: 400 **Content**: `"Wrong status code"`

***

## **Receive a trade invoice in pdf format**

**URL**

*/api/p2p/trade/:tradeId/invoice*

**METHOD**

*GET*

**URL Parameters**

**Required:**

`tradeId: [integer]` - trade number

**Success Response**

**Code**: 200 **Content**: ByteArray code

***

## **Postpone the decision to accept the trade**

**URL**

*/api/p2p/trade/:tradeId/timeout*

**METHOD**

*POST*

**URL Parameters**

**Required:**

`tradeId: [integer]` - trade number

**Data Parameters**

```json
{
  "timeout": integer
}
```

**Available timeouts**: 1 - 10 (minutes)

Can be changed by trade initiator

**Success Response**

**Code**: 200 **Content**: none

**Error Response**

**Code**: 474 AlreadyDone

**Content**: 
```json
    {
        "statusCode": 474,
        "code": "AlreadyDone"
    }
```

***
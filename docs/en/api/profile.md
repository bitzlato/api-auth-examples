## **Get profile trade status**

Returns information about the user's trading status for each cryptocurrency

**URL**

*/api/p2p/dsa/status*

**METHOD**

*GET*

**Success Response**

**Code**: 200 **Content**:
```json
{
  [cryptocurrency]: boolean
}
```

`cryptocurrency = [string]` - cryptocurrency code

***

## **Change profile trade status**

Allows you to change the status of trading for one or several cryptocurrencies

**URL**

*/api/p2p/dsa/status*

**METHOD**

*PUT*

**Data Parameters**

**Required**:

```json
{
  [cryptocurrency]: boolean
}
```

`cryptocurrency = [string]` - cryptocurrency code

**Code**: 200 **Content**:
```json
{
  [cryptocurrency]: boolean
}
```

`cryptocurrency = [string]` - cryptocurrency code

***
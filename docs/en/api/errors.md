## HTTP Codes

**Standard HTTP codes used:**

* 200 OK - for all successfully processed requests
* 401 Unauthorized - authentication information is absent or incorrect. Access token is outdated and authentication should be done again
* 403 Forbidden - requested information is related to another user or access token do not corresponds this gateway.
* 500 Internal Server Error - Something goes wrong. Should be no such errors if work is correct.

**Non-standard HTTP codes used:**

* 461 Proposal Outdated - Proposal is outdated, because it's time was up or someone had used it already
* 462 Not Enough Funds - Not enough funds for operation
* 463 Rate Was Changed - rate was changed
* 464 Voucher Outdated - Voucher outdated and can't be used
* 465 Unobvious Requirements - not enough data, server can't define what user wants to do (for example for amount of voucher calculation) and data needs to be specified
* 466 Details Required - can't start a trade, trades requisites needed for payment.
* 467 Internal Withdrawal - Can't transfer to internal address
* 468 Not Enough Rating - Not enough user rating for operation
* 469 User Don't Accept Messages Without Transaction - user don't accept messages without transaction
* 470 Already Have A Trade With A User - user already has active trade with current user. Response body should be in format: { tradeId: ... }, where tradeId is id of not finished trade
* 471 Safety Wizard Required - User should pass through safety wizard
* 472 Maintenance Enabled - The system is in maintenance mode. Can't process request
* 473 Forbidden To Trade With Yourself - Can't make trade based on own advert
* 474 Already Done - attempt to make an operation again which can be done only once
* 475 Service Shutdown - Service is under work. There is no possibility to use it temporarily
* 476 Advert Unavailable - No possibility to make new trade based on current advert
* 477 Advert Cannot Be Activated - advert can't be activated, body should contain field unactiveReason with error code (check GET /api/p2p/dsa/:advertId)
* 478 Token 2FA Required - You must provide code2fa parameter.
* 479 Public name already exists - Public name already exists.
* 480 Blocked by admin - You are blocked by admin.
* 481 Ads updated to often - You have updated ads too often.
* 482 Too many trades - You have too many trades.
* 483 Too high advert rate - Your ad has too high advert rate.
* 484 Wrong trade limit - Trade limit is out of range
* 485 Max limit for new trader - Trade amount more than required for new traders.
* 486 Blacklisted - You are blacklisted by this user.
* 487 Min partner trades amount - Total trades amount less than required for this ad
* 488 Wrong file type - Wrong file type.
* 489 Wrong max limit for new trader - Max limit for new trader must be greater than ad min limit.
* 490 Actions are frozen - Your actions frozen.
* 491 Withdraw Voucher not found - Withdraw voucher not found.
* 492 Requisites not allowed - Requisites not allowed.
* 493 State change not allowed - State change not allowed.
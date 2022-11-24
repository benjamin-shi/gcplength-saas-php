# gcplength-saas-php

## Introduction

This project is a PHP implemention of GS1 Company Prefix Length detection, by using Codes like GCP, GTIN, etc.

## Interfaces

### 1. api/gcplength

**File:** api.gs1.GCPLength.php

### 2. api/refresh

**File:** api.gs1.GCPLength.refresh.php

**Functionality**
This interface will download the "gcpprefixformatlist.json" file from GS1 Global (www.gs1.org) website and reparse it.

**Return**
JSON data like:
```
{
    "isOK" : true,
    "status" : "OK",
    ...
}
```
if successed, or
```
{
    "isOK" : false,
    "status" : "Error",
    ...
}
```
if failed.

## Contacts

Yu Shi (Benjamin)
**Email:** shiyubnu@gmail.com

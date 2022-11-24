# gcplength-saas-php

## Introduction

This project is a PHP implemention of GS1 Company Prefix Length detection, by using Codes like GCP, GTIN, etc.

## Interfaces

### 1. api/gcplength

**File:** api.gs1.GCPLength.php

**Functionality**
This interface will detect GS1 Company Prefix Length for a GS1 Code, such as GCP, GTIN, SSCC, etc.

**Input**
1. JSON Format
```
{
    "Code" : "<the code>"
}

For Example
{
    "Code" : "06901234567892"
}
or
{
    "Code" : "6901234567892"
}
or
{
    "Code" : "690123"
}
```

2. Querystring
```
api/gcplength?Code=<the code>

For example
api/gcplength?Code=06901234567892
```

**Return**
JSON data like:
```
{
    "isOK":true,
    "status":"OK",
    "message":"",
    "errors":[],
    "data":{
        "GCP":"69012",
        "Length":7
    }
}
```
if successed, or
```
{
    "isOK":false,
    "status":"Error",
    "message":"GCP Length cannot be found in register.",
    "errors":[],
    "data":null
}
```
if failed.

### 2. api/refresh

**File:** api.gs1.GCPLength.refresh.php

**Functionality**
This interface will download the "gcpprefixformatlist.json" file from GS1 Global (www.gs1.org) website and reparse it.

**Usage**
1. download the "gcpprefixformatlist.json" file from GS1 Global (www.gs1.org) website and reparse it.
   ```api/refresh?refresh=yes```

2. Just reparse the "gcpprefixformatlist.json" file
   ```api/refresh```

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

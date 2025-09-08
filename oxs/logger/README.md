

# OXS Logger

**OXS Logger** is an OXID eShop module that provides **detailed request logging**.  
It captures raw data about controller actions, request parameters, and the classes loaded during the lifecycle of a request.

The goal: create a **complete trace of what happened in the shop** so developers, support engineers, and analysts can reconstruct a user’s actions.  
Logs are **minimally invasive** and produce **structured JSON entries**, designed to be consumed later by a GUI or analytics tools.

---

## Installation
```bash
composer config repositories.oxid-support/logger path repo/oxs/logger
composer require oxid-support/logger:@dev
```
```bash
./vendor/bin/oe-console o:m:a oxslogger
```

## Features

- **Request Route Logging**
    - Captures controller (`cl`) and action (`fnc`)
    - Logs referer, user agent, GET and POST parameters
    - Sensitive values masked (`[redacted]`), keys remain visible
    - No whitelist: all parameters are logged
    - Value length limited to 500 characters

- **Symbol Tracking**
    - Tracks all classes, interfaces, and traits **declared during the request**
    - Preserves the **exact load order**
    - Filters:
        - Removes OXID module aliases (`*_parent`)
        - Removes legacy lowercase aliases (`oxuser`, `oxdb`, …)
        - Removes aliases without a file (`class_alias`, eval)
    - Produces a **raw list of FQCNs** (fully-qualified class names)

- **Request Finish Logging**
    - Duration in ms (`durationMs`)
    - Memory usage in MB (`memoryMb`)
    - Final controller and action
    - Full request context included for reference

- **Security**
    - Sensitive parameters (passwords, tokens, IDs) are masked
    - No session secrets or authentication data exposed
    - Raw JSON output suitable for automated processing

---

## Architecture


### Diagram (simplified overview)
```txt
Request Start
│
├── request.route   (Controller + Params + Context)
│
├── Request Processing …
│       └── SymbolTracker records new classes
│
├── request.symbols (All loaded classes in load order)
│
└── request.finish  (Duration, memory, final context)
```


The module consists of the following building blocks:

### 1. `ShopLogger`
- Central logger facade
- Configures Monolog as JSON logger
- Provides logger instance to all other components

### 2. `RequestContext`
- Builds context per request (shop ID, URL, session, user, language, PHP/Shop version, IP)
- Used in `request.route` and `request.finish`
- Provides a unique `requestId`

### 3. `SymbolTracker`
- Records declared classes/interfaces/traits at request start
- At request end, computes the **delta**
- Returns a plain list in load order
- Strips aliases, legacy names, and eval’d classes

### 4. `ShopControl` (Extension)
- Hooks into the request lifecycle
- Logs `request.route` at the beginning
- Logs `request.symbols` and `request.finish` at the end
- Ensures events are consistently captured

### 5. Sanitizer
- Normalizes GET/POST input
- Masks sensitive values but keeps parameter keys
- Prevents accidental leaks of credentials or tokens

---

## Log Events

A request usually produces three entries:

### `request.route`
```json
{
  "message": "request.route",
  "context": {
    "requestId": "abc123",
    "controller": "details",
    "action": "render",
    "referer": "http://shop.local/search?q=dest",
    "userAgent": "Mozilla/5.0 ...",
    "get": {
      "searchparam": "dest",
      "anid": "066e3ce119c43c81cc0e46d4f1681eed",
      "listtype": "search"
    },
    "post": null
  },
  "extra": {
    "context": {
      "ts": "2025-09-07T23:34:30+02:00",
      "shopId": 1,
      "shopUrl": "http://shop.local/",
      "sessionId": "cc57d264...",
      "userId": "no user",
      "ip": "172.21.0.1",
      "lang": "de",
      "edition": "CE",
      "php": "8.3.22",
      "oxid": "7.3.1"
    }
  }
}
```

### `request.symbols`
```json
{
  "message": "request.symbols",
  "context": {
    "requestId": "abc123",
    "symbols": [
      "OxidEsales\\Eshop\\Core\\Config",
      "OxidEsales\\EshopCommunity\\Core\\Config",
      "OxidSolutionCatalysts\\Unzer\\Core\\Config",
      "OxidEsales\\Eshop\\Application\\Model\\User",
      "OxidEsales\\EshopCommunity\\Application\\Model\\User"
    ]
  },
  "extra": {
    "requestId": "abc123"
  }
}
```
### `request.symbols`
```json
{
  "message": "request.finish",
  "context": {
    "requestId": "abc123",
    "durationMs": 148,
    "memoryMb": 24,
    "controller": "details",
    "action": "render"
  },
  "extra": {
    "context": {
      "ts": "2025-09-07T23:34:30+02:00",
      "shopId": 1,
      "shopUrl": "http://shop.local/",
      "sessionId": "cc57d264...",
      "userId": "no user",
      "lang": "de",
      "edition": "CE",
      "php": "8.3.22",
      "oxid": "7.3.1"
    }
  }
}
```
### Context vs. Extra

Monolog distinguishes between two namespaces for structured data:

- `context`  
  Contains everything explicitly passed at the time of logging, e.g. controller, action, parameters.

- `extra`  
  Contains information automatically added by processors or handlers.  
  In OXS Logger, the `RequestContextProcessor` attaches the full request context here  
  under `extra.context`.

**Important:**  
Do not confuse `context` (per-event data) with `extra.context` (global request metadata).  
Both are written deliberately to keep Monolog’s standard separation between application payload and automatic metadata.


### Benefits for Developers & Support
* Debugging: See which classes were loaded, in what order, with which controller.
* Support cases: Reconstruct exactly what the user did (controller + parameters).
* Performance monitoring: Duration and memory are logged per request.
* Compatibility checks: Identify which modules extend which classes (*_parent chains).

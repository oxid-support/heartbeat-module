# OXS :: Heartbeat

**OXS Heartbeat** is an OXID eShop module that provides **comprehensive logging capabilities**.
It includes detailed request logging, capturing what users do inside the shop, and a GraphQL API for remote configuration.

The goal: create a **complete trace of what happened in the shop** so developers, support engineers, and analysts can reconstruct a user's actions.
Logs are **minimally invasive**, stored locally on server, and produce **structured log entries** in Monolog's line format (timestamp, level, message, and JSON context), designed to be consumed by internal monitoring and analytics tools.

---

## Installation

### Step 1: Install via Composer

#### Live
```bash
composer config repositories.oxid-support/heartbeat vcs https://github.com/oxid-support/heartbeat-module
composer require oxid-support/heartbeat
```

#### Dev
```bash
git clone https://github.com/oxid-support/heartbeat-module.git repo/oxs/heartbeat
composer config repositories.oxid-support/heartbeat path repo/oxs/heartbeat
composer require oxid-support/heartbeat:@dev
```

> **Note**: The OXID GraphQL Base and GraphQL Configuration Access modules are installed automatically as dependencies.

### Step 2: Run Database Migrations

```bash
./vendor/bin/oe-eshop-doctrine_migration migrations:migrate oe_graphql_base
./vendor/bin/oe-eshop-doctrine_migration migrations:migrate oxsheartbeat
```

### Step 3: Clear Shop Cache

```bash
./vendor/bin/oe-console oe:c:c
```

### Step 4: Activate Modules

**Important**: The GraphQL modules must be activated **before** activating the Heartbeat module.

```bash
./vendor/bin/oe-console oe:module:activate oe_graphql_base
./vendor/bin/oe-console oe:module:activate oe_graphql_configuration_access
./vendor/bin/oe-console oe:module:activate oxsheartbeat
```

For more details on OXID GraphQL installation, see the [official documentation](https://docs.oxid-esales.com/interfaces/graphql/en/latest/install.html).

## Module Information

- **Module ID**: `oxsheartbeat`
- **Module Title**: OXS :: Heartbeat
- **Version**: 1.0.0
- **Author**: support@oxid-esales.com
- **Supported OXID Versions**: 7.1+
- **PHP Version**: 8.0 - 8.4

> **Local Storage Only**: This module writes logs exclusively to server's local filesystem (`OX_BASE_PATH/log/oxs-heartbeat/`). No data is transmitted to external services or third parties.

---

## Components

The Heartbeat module consists of several components:

### 1. Request Logger
Records controller actions, request parameters, and the classes loaded during the lifecycle of a request to local log files on server.

### 2. Request Logger Remote
Provides a GraphQL API for remote configuration and management of the Request Logger settings.

Both components can be enabled/disabled independently via the Admin interface.

---

## Features

### Request Logger Features

- **Request Route Logging**
    - Records controller (`cl`) and action (`fnc`)
    - Logs referer, user agent, GET and POST parameters
    - **Configurable redaction**: Choose between redacting all values (default) or selective redaction of sensitive parameters
    - Keys always remain visible for diagnostics
    - Arrays/objects converted to JSON (no length limits)
    - Scalar values logged unchanged when selective redaction is enabled

- **Correlation ID Tracking**
    - Unique ID assigned to each request for tracing across multiple requests
    - Correlation ID transmitted via HTTP header (`X-Correlation-Id`) and cookie
    - Cookie TTL: 30 days (2592000 seconds)
    - Allows tracking user sessions and multi-step flows
    - Each log file named by correlation ID for easy request grouping

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

- **Security & Privacy**
    - **Default maximum privacy**: All parameter values redacted by default
    - **Optional selective redaction**: Configure specific sensitive parameters (passwords, tokens, IDs) to mask
    - No session secrets or authentication data in logs
    - All logs stored locally on server filesystem only
    - No data transmission to external services

### Request Logger Remote Features

- **GraphQL API** for remote configuration
- Query and modify all module settings remotely
- Activate/deactivate logging via API
- Authenticate via JWT with dedicated API user
- Secure token-based setup workflow

---

## Module Configuration

The module provides configurable settings accessible via OXID Admin → Extensions → Modules → OXS :: Heartbeat.

### Request Logger Settings

Navigate to: **OXS :: Heartbeat → Request Logger → Settings**

#### 1. Component Activation
- Toggle to enable/disable the Request Logger component

#### 2. Log Frontend Requests
- **Default**: `false` (disabled)
- Enable logging for frontend (shop) requests

#### 3. Log Admin Requests
- **Default**: `false` (disabled)
- Enable logging for admin panel requests

#### 4. Detailed Logging
- **Default**: `false` (disabled)
- When enabled, additionally logs symbol tracking (request.symbols) showing all classes/interfaces/traits loaded during the request

#### 5. Redact all values
- **Default**: `true` (enabled)
- When enabled, redacts ALL request parameter values (GET/POST) in logs, showing only parameter keys
- When disabled, only parameters listed in the "Redact Fields" setting are masked

#### 6. Redact Fields
- **Default**: `['pwd', 'lgn_pwd', 'lgn_pwd2', 'newPassword']`
- List of parameter names (case-insensitive) whose values should be masked as `[redacted]` in logs
- Only applies when "Redact all values" is disabled

### Request Logger Remote Settings

Navigate to: **OXS :: Heartbeat → Request Logger Remote → Setup**

- **Component Activation**: Toggle to enable/disable the Remote component
- **Setup Workflow**: Follow the guided setup to configure remote access
- **API Reset**: Reset API credentials if needed

---

## Correlation ID System

The module implements a sophisticated correlation ID system that tracks requests across multiple page loads and API calls.

### How It Works

1. **ID Resolution**: The system attempts to resolve an existing correlation ID from:
   - HTTP Header `X-Correlation-Id`
   - Cookie `X-Correlation-Id`
   - If neither exists: Generate new UUID v4
2. **ID Emission**: The correlation ID is returned to the client via:
   - HTTP Response Header: `X-Correlation-Id: <id>`
   - Cookie: `X-Correlation-Id=<id>; Max-Age=2592000; Path=/; HttpOnly; SameSite=Lax`
3. **Log Association**: All log entries include the correlation ID in the `context` field

### Use Cases

- **Multi-step User Flows**: Track a user's journey from product page → cart → checkout → order completion
- **Error Debugging**: When a user reports an error, search logs by their correlation ID to see all recent actions
- **Session Analysis**: Group logs by correlation ID to analyze complete user sessions (up to 30 days)

---

## Log Events

A request usually emits three entries:

### 1. `request.start`

**Content:**
- HTTP method, URI, referer, user agent
- Redacted GET/POST parameters (sensitive values masked)
- Shop context: version, edition, shopId, shopUrl, language
- Session/user info: sessionId, userId, username
- Request metadata: IP address, PHP version
- Correlation ID for tracing

### 2. `request.symbols`

- Array of all newly declared FQCNs (fully-qualified class names) in load order
- Only logged when "Detailed Logging" is enabled
- Useful for diagnosing template/render paths and module extension chains

### 3. `request.finish`

- Request duration in milliseconds (`durationMs`)
- Peak memory usage in megabytes (`memoryMb`)

---

## Output Location & Format

### File Location
Logs are written to:
```
OX_BASE_PATH/log/oxs-heartbeat/oxs-heartbeat-<CorrelationID>.log
```

### File Organization
- **One file per correlation ID** - All requests sharing the same correlation ID write to the same file
- **Multiple entries per file** - Each request typically creates 2-3 entries: `request.start`, `request.symbols` (if detailed), `request.finish`
- **Monolog Line Format** - Each log entry follows Monolog's standard format: `[timestamp] channel.LEVEL: message {json_context}`

Each `.log` file contains newline-separated log entries in Monolog's format. The context data is JSON-encoded, making it parseable by log analysis tools.

---

## GraphQL API

The Request Logger Remote component provides a GraphQL API for remote management.

### Authentication

1. During module activation, an API user is created
2. Use the setup token from the Admin interface to set the API user password
3. Authenticate via GraphQL to receive a JWT token
4. Use the JWT token for subsequent API calls

### Available Operations

- Query current settings
- Modify logging settings
- Activate/deactivate the Request Logger component
- Reset API user password

---

## Testing

**Run tests from module directory:**
```bash
./vendor/bin/phpunit --configuration tests/phpunit.xml
```

---

## Development

See [COMPONENT_DEVELOPMENT_GUIDE.md](COMPONENT_DEVELOPMENT_GUIDE.md) for guidelines on developing new components for this module.

---

## License

See [LICENSE](LICENSE) file for details.

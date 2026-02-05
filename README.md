# OXS :: Heartbeat

**OXS Heartbeat** is an OXID eShop module that enables **remote monitoring and support** for OXID shops.

It provides:
- **Request Logger**: Detailed request logging with correlation ID tracking
- **Log Sender**: Collect and provide log files to external monitoring systems
- **Diagnostics Provider**: Shop diagnostic information (modules, PHP, server)
- **API User**: Secure GraphQL API access for OXID Support

All components are accessible via GraphQL API, allowing OXID Support to remotely analyze shop issues without direct server access.

> **Full Control**: Remote access is **completely optional**. Each component can be enabled/disabled independently by the shop operator. No data is transmitted unless explicitly activated. Access can be revoked at any time.

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
./vendor/bin/oe-console oe:cache:clear
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

### 1. API User
Manages the API user for remote access to Heartbeat components. **Required** for all remote-enabled components (Request Logger, Log Sender, Diagnostics Provider). Set this up first.

### 2. Request Logger
Records controller actions, request parameters and the classes loaded during the lifecycle of a request to local log files. Includes GraphQL API for remote configuration.

### 3. Log Sender
Collects log files from various sources and provides them to external monitoring systems via GraphQL API.

### 4. Diagnostics Provider
Provides shop diagnostic information (modules, PHP config, server info) via GraphQL API.

All components can be enabled/disabled independently via the Admin interface under **OXS :: Heartbeat**.

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

### Remote Configuration (via GraphQL API)

- Query and modify all Request Logger settings remotely
- Activate/deactivate logging via API
- Authenticate via JWT with dedicated API user
- Requires API User setup

---

## Module Configuration

The module provides configurable settings accessible via OXID Admin under **OXS :: Heartbeat**.

### API User Setup (Required First)

Navigate to: **OXS :: Heartbeat → API User → Setup**

The API User is required for all components that need remote access. Follow the setup workflow:

1. **Migrations**: Ensure database migrations are executed
2. **GraphQL Base**: Ensure GraphQL Base module is activated
3. **Setup Token**: Copy the setup token and send it to OXID Support
4. **Activation**: Wait for OXID Support to set the API password

Once complete, the API User status shows "Active" and other components can be enabled.

### Request Logger Settings

Navigate to: **OXS :: Heartbeat → Request Logger → Settings**

**Note**: Requires API User setup to be complete.

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

### Log Sender Settings

Navigate to: **OXS :: Heartbeat → Log Sender → Manage**

**Note**: Requires API User setup to be complete.

- **Component Activation**: Toggle to enable/disable the Log Sender
- **Log Sources**: View all recognized log sources with availability status
- **Source Toggle**: Enable/disable individual log sources for sending
- **Static Paths**: Configure additional log files or directories to monitor

Log sources can be registered via:
- **DI Tag Provider**: Services implementing `LogPathProviderInterface` with tag `oxs.logsender.provider`
- **Static Paths**: Manual configuration in the admin interface

### Diagnostics Provider Settings

Navigate to: **OXS :: Heartbeat → Diagnostics Provider → Manage**

**Note**: Requires API User setup to be complete.

- **Component Activation**: Toggle to enable/disable the Diagnostics Provider

Provides the following information via GraphQL API:
- Shop details (URL, edition, version, statistics)
- Installed modules
- System information
- PHP configuration
- Server information

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

The Heartbeat module provides GraphQL APIs for remote management of all components.

### Authentication

1. During module activation, an API user (`heartbeat-api@oxid-esales.com`) is created
2. To enable remote access, use the setup token from the Admin interface to set the API user password (via OXID Support: support@oxid-esales.com)
Note: The API user is only used for remote access of the Heartbeats data through the OXID Support and access can be revoked at any time.
### Available Operations

**Request Logger:**
- Query and modify logging settings
- Activate/deactivate the Request Logger component

**Log Sender:**
- Query available log sources
- Read log file contents

**Diagnostics Provider:**
- Query shop diagnostics (modules, PHP config, server info)

---

## Testing

Tests run standalone without requiring a full OXID shop installation. The module uses `oxideshop-ce` as a dev dependency to provide the necessary framework interfaces.

### Setup

```bash
cd repo/oxs/heartbeat
composer install
```

### Run Tests

```bash
./vendor/bin/phpunit --configuration tests/phpunit.xml
```

### Test Coverage

The test suite includes unit tests for all components. Some integration tests (e.g., `ModuleEvents`) are skipped in standalone mode as they require a full shop context.

---

## Development

See [COMPONENT_DEVELOPMENT_GUIDE.md](COMPONENT_DEVELOPMENT_GUIDE.md) for guidelines on developing new components for this module.

---

## License

See [LICENSE](LICENSE) file for details.

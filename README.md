# AIWAF Integration Guide

This README shows you exactly **how to plug AIWAF** into your existing PHP application, whether you installed via Composer or manually, and how to configure rate limiting, logging, and model loading.

---

## 1. Prerequisites

- PHP 7.4 or higher  
- (Optional) Composer for autoloading and dependency management  
- Web-accessible `resources/` folder for logs and model files  

---

## 2. Installation

### Composer (recommended)

```bash
composer require aayushgauba/aiwaf
```

This installs AIWAF under `vendor/aayushgauba/aiwaf` and sets up PSR-4 autoloading.

### Manual

Clone or download this repository alongside your code:

```bash
git clone https://github.com/aayushgauba/aiwaf-php.git aiwaf
```

Ensure your app can `require` the `src/` folder.

---

## 3. Setup

1. **Create** the `resources/` directory if it doesn’t exist:
   ```bash
   mkdir resources
   touch resources/blocked_ips.json
   ```
2. **(Optional)** Create an empty feature‐log CSV:
   ```bash
   touch resources/request_features.csv
   ```
3. **(Optional)** Copy and edit `src/Config.php` to adjust thresholds:
   ```php
   <?php
   namespace AIWAF;

   class Config
   {
       public static $exemptPaths                = ['/health', '/ping'];
       public static $rateLimitPerMinute         = 60;
       public static $keywordDetectionThreshold  = 5;
       public static $uuidTamperThreshold        = 3;
   }
   ```

---

## 4. Integration Snippet

Place the following code **at the very top** of your main entrypoint (e.g. `index.php`)—before any output or framework bootstrapping:

```php
<?php
declare(strict_types=1);

// 1) Autoloading
// If you used Composer:
require_once __DIR__ . '/vendor/autoload.php';

// If you installed manually, uncomment these:
// require_once __DIR__ . '/path/to/aiwaf/src/Config.php';
// require_once __DIR__ . '/path/to/aiwaf/src/Utils.php';
// require_once __DIR__ . '/path/to/aiwaf/src/IPBlocker.php';
// require_once __DIR__ . '/path/to/aiwaf/src/DynamicKeywordManager.php';
// require_once __DIR__ . '/path/to/aiwaf/src/FeatureExtractor.php';
// require_once __DIR__ . '/path/to/aiwaf/src/RateLimiter.php';
// require_once __DIR__ . '/path/to/aiwaf/src/UUIDTamperProtector.php';
// require_once __DIR__ . '/path/to/aiwaf/src/HoneypotChecker.php';
// require_once __DIR__ . '/path/to/aiwaf/src/IsolationForest.php';
// require_once __DIR__ . '/path/to/aiwaf/src/AIWAF.php';
// require_once __DIR__ . '/path/to/aiwaf/src/Logger.php';

use AIWAF\Config;
use AIWAF\RateLimiter;
use AIWAF\RateLimit\InMemoryDriver;
use AIWAF\AIWAF;
use AIWAF\Logger;

// 2) (Optional) Override the feature-log path:
// Logger::setLogFile(__DIR__ . '/logs/aiwaf_features.csv');

// 3) Initialize your rate-limiter backend
RateLimiter::init(new InMemoryDriver());

// For a shared Redis-based limiter, you could do:
// $redis = new Redis();
// $redis->connect('127.0.0.1', 6379);
// RateLimiter::init(new \AIWAF\RateLimit\RedisDriver($redis));

// 4) Protect the request
AIWAF::protect();

// 5) Carry on with your application...
// e.g., dispatch to your framework or echo your page
```

---

## 5. How It Works

1. **Early exit** for exempt paths (health checks, status pages).  
2. **IP blocking** via `resources/blocked_ips.json`.  
3. **Rate limiting** with your chosen backend (in-memory, Redis, APCu, or DB).  
4. **Dynamic keyword** detection and learning.  
5. **UUID tamper** and **honeypot** checks.  
6. **Isolation Forest** anomaly detection (if `resources/forest_model.json` exists).  
7. **Blocks** suspicious IPs with HTTP 403 or 429 and logs the event.

---

## 6. Logging & Model Retraining

- **Per-request logging**: call `Logger::log($features)` inside `AIWAF::protect()`.  
- **Nightly retrain script**: use `tools/train_iforest.php` (or your own script) to read `resources/request_features.csv`, fit a new model, and overwrite `resources/forest_model.json`.  

Schedule via cron:

```cron
0 3 * * * www-data /path/to/aiwaf/tools/train_iforest.php
```

---

## 7. Running Tests

If you installed via Composer:

```bash
composer test
```

Or with phpunit.phar:

```bash
php phpunit.phar --testdox tests/
```

---

## 8. Support & Contribution

- Open issues or pull requests on GitHub  
- Star the repo if you find this useful!  

---

*© 2025 Aayush Gauba*  
*Licensed under MIT*  

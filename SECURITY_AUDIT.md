# Laravel Script Security Audit Checklist

A practical checklist of every vulnerability category checked, what to look for, and how to fix it.  
Based on a full audit of a Laravel 8 commercial trading script.

---

## 1. File Upload → RCE

### 1a. Zip Slip (Theme/Archive Upload)
**What to look for:**
```php
// VULNERABLE — client name used as extraction path
$themeName = substr($this->theme->getClientOriginalName(), 0, -4);
$zip->extractTo(base_path("themes/{$themeName}"));

// ALSO VULNERABLE — zip entries not checked before extraction
$zip->extractTo($destination); // no per-entry path validation
```
**Fix:**
```php
// Sanitize the name
$rawName   = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
$themeName = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $rawName);

// Check every entry BEFORE extracting
$resolvedBase = realpath(base_path('themes')) . DIRECTORY_SEPARATOR;
for ($i = 0; $i < $zip->numFiles; $i++) {
    $entry     = $zip->getNameIndex($i);
    $entryFull = str_replace('\\', '/', $resolvedBase . $themeName . '/' . $entry);
    $base      = str_replace('\\', '/', $resolvedBase);
    if (strpos($entryFull, $base) !== 0) {
        $zip->close();
        abort(422, 'Path traversal detected in archive.');
    }
}
$zip->extractTo(base_path("themes/{$themeName}"));
```

---

### 1b. User-Controlled Filename in storeAs()
**What to look for:**
```php
// VULNERABLE
$filename = time() . '_' . $file->getClientOriginalName();
$path     = $file->storeAs('payment_proofs', $filename, 'public');
```
**Fix:**
```php
// Use server-generated name; extension() uses MIME detection, not client header
$filename = time() . '_' . bin2hex(random_bytes(8)) . '.' . $file->extension();
$path     = $file->storeAs('payment_proofs', $filename, 'public');
```

---

### 1c. Overly Permissive Extension Whitelist
**What to look for:**
```php
// VULNERABLE — pdf/doc are not image types
$whitelist = array('pdf', 'doc', 'jpeg', 'jpg', 'png');
```
**Fix:**
```php
// Match the whitelist to the Laravel validator
$this->validate($request, ['proof' => 'image|mimes:jpg,jpeg,png|max:1000']);
$whitelist = ['jpeg', 'jpg', 'png'];
```

---

### 1d. No PHP Execution Block in Upload Directories
**What to look for:**  
Check `storage/app/public/uploads/`, `storage/app/public/payment_proofs/`, and any other publicly symlinked upload folder for the absence of an `.htaccess` file.

**Fix — create `.htaccess` in every upload directory:**
```apache
<FilesMatch "\.ph(p[2-9]?|tml|ar|ps|t)$">
    Order allow,deny
    Deny from all
</FilesMatch>

<IfModule mod_php.c>
    php_flag engine off
</IfModule>
<IfModule mod_php7.c>
    php_flag engine off
</IfModule>
<IfModule mod_php8.c>
    php_flag engine off
</IfModule>

Options -ExecCGI
RemoveHandler .php .php3 .php4 .php5 .php6 .php7 .php8 .phtml .phar
RemoveType   .php .php3 .php4 .php5 .php6 .php7 .php8 .phtml .phar
```

---

## 2. SQL Injection

### 2a. Raw Interpolation in whereRaw / FULLTEXT Search
**What to look for:**
```php
// VULNERABLE — $searchItem is user input interpolated directly
$result = Model::whereRaw("MATCH(col1,col2) AGAINST('$searchItem')")->get();

// Also check: DB::statement, DB::unprepared, selectRaw, havingRaw, orderByRaw
// with unbound user variables
```
**Fix:**
```php
// Use bound parameters
$result = Model::whereRaw('MATCH(col1,col2) AGAINST(? IN BOOLEAN MODE)', [$searchItem])->get();
```

---

### 2b. Mass Assignment
**What to look for:**
```php
// DANGEROUS — accepts everything from request
protected $guarded = [];

// Also check create() / update() calls with $request->all()
Model::create($request->all());
```
**Fix:**
```php
// Explicitly whitelist fillable fields
protected $fillable = ['name', 'email', 'status'];

// Or use only() in controllers
Model::create($request->only(['name', 'email']));
```

---

## 3. CSRF Exceptions

**What to look for:**
```php
// app/Http/Middleware/VerifyCsrfToken.php
protected $except = [
    'get-started/',
    'https://external-site.com/some-path',  // DANGEROUS — external URL exception
    'api/*',                                 // DANGEROUS — too broad
];
```
**Fix:**
```php
// Only exempt webhooks/IPN endpoints that legitimately can't send CSRF tokens
protected $except = [
    'webhooks/stripe',
    'webhooks/paystack',
];
// Never add external URLs or broad wildcards
```

---

## 4. Debug Routes / Controllers Left in Production

**What to look for:**
```php
// routes/user/debug.php or similar
Route::get('debug-trading-history', [DebugController::class, 'debugTradingHistory']);
// No auth middleware!

// app/Providers/RouteServiceProvider.php
// Check if debug routes are conditionally loaded:
if (app()->environment('local')) { ... }
// But also check the condition is actually correct
```
**Fix:**
```php
// Wrap ALL debug routes in auth middleware at the route level
Route::middleware(['auth'])->group(function () {
    Route::get('debug-trading-history', [DebugController::class, 'debugTradingHistory'])
         ->name('debug.tradinghistory');
});

// In RouteServiceProvider, ensure it's only loaded in non-production:
if (app()->environment('local', 'development')) {
    // load debug routes
}
```

---

## 5. Exposed Sensitive Files in Webroot

**What to look for:**
- `rekey.php`, `install.php`, `setup.php`, `phpinfo.php` in the project root or `public/`
- Any `.php` file that reads/writes `.env`, runs Artisan, or accesses the DB without auth

**Fix:**
- Delete one-time scripts immediately after use
- If you must keep them, block access in `.htaccess`:
```apache
<Files "rekey.php">
    Order allow,deny
    Deny from all
</Files>
```
- Prefer using `php artisan key:generate` from CLI instead of web-based key rotators

---

## 6. Weak Cron/API Secret Keys

**What to look for:**
```bash
# .env
CRON_KEY=8753   # WEAK — 4 digits, brute-forceable
```
```php
// routes/web.php
if (request('key') !== env('CRON_KEY')) {
    abort(403);
}
```
**Fix:**
```bash
# .env — use a cryptographically random string (32+ chars)
CRON_KEY=xK9mP2qL7vRnT4wY8jBdF6hZ0cA3eGiN
```
Generate one with: `php -r "echo bin2hex(random_bytes(32));"`

---

## 7. Hardcoded Default Passwords

**What to look for:**
```php
// Admin reset password to known default
'password' => Hash::make('user01236'),
'password' => Hash::make('01236admin7'),
```
**Fix:**
```php
// Generate a random temporary password and email it to the user
$tempPassword = Str::random(12);
$user->update(['password' => Hash::make($tempPassword)]);
Mail::to($user->email)->send(new PasswordResetNotification($tempPassword));
```

---

## 8. .env File Permissions

**What to look for:**
```bash
# Windows — check with:
icacls .env
# Bad: BUILTIN\Users:(I)(RX)  — any local user can read

# Linux — check with:
stat .env
# Bad: -rw-r--r-- (644) — world-readable
```
**Fix:**
```bash
# Linux
chmod 600 .env

# Windows
icacls .env /inheritance:r
icacls .env /grant:r Administrators:F
icacls .env /grant:r SYSTEM:F
```

---

## 9. APP_DEBUG in Production

**What to look for:**
```bash
# .env
APP_DEBUG=true   # NEVER in production — leaks stack traces, DB queries, .env values
```
**Fix:**
```bash
APP_DEBUG=false
LOG_LEVEL=error   # Also tighten log level — avoid 'debug' in production
```

---

## 10. Laravel Version / CVEs

**What to look for:**
```json
// composer.json
"laravel/framework": "^8.75"   // EOL — no more security patches
```
**Fix:**
- Check current Laravel version: `php artisan --version`
- Check known CVEs: https://laravel.com/docs/releases and https://nvd.nist.gov
- Plan upgrade path: Laravel 8 → 9 → 10 → 11
- Run `composer audit` to check all dependencies for known vulnerabilities

---

## 11. IP Spoofing (X-Forwarded-For)

**What to look for:**
```php
// VULNERABLE — HTTP_X_FORWARDED_FOR and HTTP_CLIENT_IP can be forged by any client
if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
    $ip = $_SERVER['HTTP_CLIENT_IP'];
```
**Fix:**
- Use Laravel's `$request->ip()` which respects the trusted proxy configuration
- Configure trusted proxies properly in `app/Http/Middleware/TrustProxies.php`:
```php
protected $proxies = ['10.0.0.0/8', '172.16.0.0/12', '192.168.0.0/16'];
protected $headers = Request::HEADER_X_FORWARDED_FOR;
```

---

## 12. XSS via {!! !!} in Blade Templates

**What to look for:**
```blade
{{-- DANGEROUS — outputs raw unescaped HTML --}}
{!! $user->description !!}
{!! $body !!}
{!! $terms !!}
```
**Fix:**
```blade
{{-- Safe — auto-escapes HTML entities --}}
{{ $user->description }}

{{-- Only use {!! !!} for trusted, admin-controlled rich text content
     that has been sanitized server-side with an HTML purifier --}}
```
- Install HTMLPurifier: `composer require ezyang/htmlpurifier`
- Sanitize before saving: `$clean = \Purifier::clean($request->description);`

---

## 13. Rate Limiting on Auth Endpoints

**What to look for:**
```php
// app/Providers/FortifyServiceProvider.php — check limits aren't too high
RateLimiter::for('login', function (Request $request) {
    return Limit::perMinute(100)->by($request->email.$request->ip()); // Too high
});
```
**Fix:**
```php
// 5 attempts per minute per email+IP is reasonable
RateLimiter::for('login', function (Request $request) {
    return Limit::perMinute(5)->by($request->email.$request->ip());
});
```

---

## 14. Admin Panel Route Exposure

**What to look for:**
- Is the admin prefix guessable? (`/admin`, `/dashboard/admin`)
- Does the `isadmin` middleware check role correctly?
- Is 2FA enforced for admin?

```php
// Check middleware stack on admin routes:
Route::middleware(['isadmin', '2fa'])->prefix('admin')->group(function () { ... });
```
**Fix:**
- Use an obscure admin prefix (e.g., `/mgmt-xk9p`)  
- Enforce 2FA for all admin accounts
- Add IP allowlist middleware for admin routes if on a fixed IP

---

## 15. Unprotected Artisan / Cron Routes

**What to look for:**
```php
// DANGEROUS — no key check
Route::get('/cron', [AutoTaskController::class, 'autotopup']);

// Weak key check
Route::get('/run-crypto-update', function () {
    if (request('key') !== env('CRON_KEY')) { abort(403); }
    // ...
});
```
**Fix:**
- Use a strong `CRON_KEY` (see #6)
- Prefer server-side cron (`crontab`) calling `php artisan schedule:run` instead of HTTP cron endpoints
- If HTTP cron is required, add IP restriction at the web server level

---

## 16. Backdoor / Malicious Code Detection

**Grep commands to run:**
```bash
# Obfuscation patterns
grep -rn "eval(" app/ --include="*.php"
grep -rn "base64_decode(" app/ --include="*.php"
grep -rn "gzinflate\|gzuncompress\|gzdecode" app/ --include="*.php"
grep -rn "str_rot13\|hex2bin\|rawurldecode" app/ --include="*.php"
grep -rn "assert(" app/ --include="*.php"

# Shell execution
grep -rn "exec(\|shell_exec(\|system(\|passthru(\|popen(\|proc_open(" app/ --include="*.php"

# Webshells in upload storage
find storage/app/public -name "*.php" -o -name "*.phtml" -o -name "*.phar"
find public -name "*.php" ! -name "index.php"

# PHP files in unexpected places
find . -name "*.php" -not -path "./vendor/*" -not -path "./app/*" \
       -not -path "./bootstrap/*" -not -path "./config/*" \
       -not -path "./routes/*" -not -path "./database/*" | grep -v "artisan\|index.php\|server.php"
```

---

## 17. XSS via `{{ }}` Inside `<script>` Tags

**What to look for:**
```blade
{{-- VULNERABLE — {{ }} HTML-encodes, but inside a JS string that is not sufficient --}}
<script>
    var userName = "{{ Auth::user()->name }}";  // " breaks out of JS string
    var amount   = {{ $amount }};               // if $amount contains ; alert(1)//
    var key      = "{{ $settings->stripe_pk }}";
    fetch("{{ url('/api/pay') }}", {
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    });
</script>
```
`{{ }}` applies `htmlspecialchars()` which converts `<`, `>`, `&` but does **not** escape `\`, `"`, newlines, or Unicode escape sequences — all of which can break out of a JavaScript string context.

**Fix — use `@json()` for any PHP value placed inside JavaScript:**
```blade
<script>
    var userName = @json(Auth::user()->name);       // properly JSON-encodes the string
    var amount   = @json($amount);
    var key      = @json($settings->stripe_pk);
    fetch(@json(url('/api/pay')), {
        headers: { 'X-CSRF-TOKEN': @json(csrf_token()) }
    });
</script>
```
`@json()` calls `json_encode()` with `JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT` — safe for direct inline JS embedding.

**Grep to find all occurrences:**
```bash
grep -rn "{{\s*\$\|{{\s*Auth::\|{{\s*csrf\|{{\s*url(\|{{\s*route(" \
     resources/views --include="*.php" | grep -i "<script\|\.js\b\|JSON\|fetch\|axios\|var \|let \|const "
```

---

## 18. SVG / GIF Upload → Stored XSS

**What to look for:**
```php
// VULNERABLE — SVG files can contain inline <script> tags
$this->validate($request, [
    'image' => 'image|mimes:jpeg,png,jpg,gif,svg,webp',
]);
```
SVG is an XML format. An uploaded `evil.svg` containing `<script>alert(1)</script>` will execute when the file is served directly by the browser. GIF can also contain embedded HTML/JavaScript in comment blocks that some browsers execute.

**Fix — exclude SVG (and GIF if not needed) from image uploads:**
```php
$this->validate($request, [
    'image' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
]);
```
If SVG is required, sanitize it server-side before storing:
```bash
composer require darylldoyle/svg-sanitizer
```
```php
use enshrined\svgSanitize\Sanitizer;
$sanitizer = new Sanitizer();
$cleanSvg  = $sanitizer->sanitize(file_get_contents($file->getRealPath()));
Storage::put('images/' . $name . '.svg', $cleanSvg);
```

---

## 19. Backup / Archive Files Exposed in Webroot

**What to look for:**
- `.zip`, `.tar.gz`, `.tar`, `.bak`, `.sql`, `.rar`, `.7z` files anywhere under the document root
- Common filenames: `backup.zip`, `projectname.zip`, `db_backup.sql`, `dump.sql`
- These typically contain full source code **including `.env`** — meaning DB credentials, `APP_KEY`, and all payment API keys are leaked

```bash
# Find archives in webroot
find . -not -path "./vendor/*" -not -path "./node_modules/*" \
   \( -name "*.zip" -o -name "*.tar.gz" -o -name "*.sql" \
      -o -name "*.bak" -o -name "*.rar" -o -name "*.7z" \) 2>/dev/null
```

**Fix:**
1. Delete all backup archives from the webroot immediately
2. Rotate **all** credentials from `.env` (assume leaked): `APP_KEY`, DB password, all payment API keys
3. Block downloads at the web server level in `.htaccess` / `nginx.conf`:
```apache
# .htaccess
<FilesMatch "\.(zip|tar|gz|bak|sql|rar|7z|tar\.gz)$">
    Order deny,allow
    Deny from all
</FilesMatch>
```
```nginx
# nginx.conf
location ~* \.(zip|tar|gz|bak|sql|rar|7z)$ {
    deny all;
    return 404;
}
```
4. Store backups outside the webroot, or in a private object-storage bucket with no public URL

---

## 20. Composer CVE Audit & Dependency Pinning

**Step 1 — Run the audit:**
```bash
composer audit
```
This checks `composer.lock` against the PHP Security Advisory Database and reports CVEs by package + affected version range.

**Step 2 — Identify which are patchable:**
Some packages cannot be updated because a parent dependency pins them (common in Laravel 8 with `league/commonmark`, `aws/aws-sdk-php`, `firebase/php-jwt`). For those, assess whether the vulnerable feature is actually used.

**Step 3 — Pin minimum safe versions in `composer.json`:**
```json
{
    "require": {
        "phpoffice/phpspreadsheet": "^1.30.4",
        "phpseclib/phpseclib":      "^3.0.51",
        "symfony/http-foundation":  "^5.4.50",
        "symfony/process":          "^5.4.51"
    },
    "require-dev": {
        "phpunit/phpunit": "^9.6.33",
        "psy/psysh":       "^0.12.19"
    }
}
```
This prevents future `composer update` from regressing to a vulnerable version if a transitive constraint would otherwise allow it.

**Step 4 — Update only targeted packages:**
```bash
composer update vendor/package1 vendor/package2 --with-all-dependencies
```
The `--with-all-dependencies` flag allows composer to also update transitive deps of those packages to satisfy the new version constraints.

**Step 5 — Verify:**
```bash
composer audit   # should show fewer or zero advisories
```

**Common CVE-prone packages to check in Laravel apps:**

| Package | Risk |
|---------|------|
| `phpoffice/phpspreadsheet` | SSRF, XSS, DoS via crafted Excel files |
| `phpseclib/phpseclib` | Padding oracle, HMAC timing in AES-CBC |
| `symfony/http-foundation` | PATH_INFO auth bypass |
| `symfony/process` | Argument injection on Windows |
| `laravel/framework` | Query string injection, file validation bypass |
| `league/commonmark` | XSS in Attributes/Embed extensions |
| `aws/aws-sdk-php` | CloudFront policy injection, S3 key commitment |

---

## Quick Audit Checklist

### File Uploads
- [ ] All file uploads: `mimes:` validator + server-generated filenames + upload dir `.htaccess`
- [ ] Zip/archive uploads: per-entry path traversal check before extraction; `.blade.php`-only restriction when processing nested view zips
- [ ] No `pdf`/`doc`/`exe`/`svg`/`gif` in image upload whitelists (SVG can carry `<script>` tags)
- [ ] Upload directories have `.htaccess` blocking PHP execution (`Deny from all` on `.php*`, `.phar`, `.phtml`)
- [ ] Filenames use `$file->extension()` (MIME-based), never `getClientOriginalExtension()` (user-controlled)

### SQL & Database
- [ ] No raw string interpolation in `whereRaw`, `selectRaw`, `havingRaw`, `orderByRaw`, `DB::statement`
- [ ] `$fillable` used on all models; never `$guarded = []` with `$request->all()`

### Authentication & Routes
- [ ] CSRF exceptions contain only legitimate webhook endpoints, no external URLs or broad wildcards
- [ ] Debug routes wrapped in `auth` middleware and environment-gated (`local`/`development` only)
- [ ] Admin panel has 2FA and rate limiting (≤5 attempts/min)
- [ ] Admin prefix is not the default `/admin`; consider IP allowlist middleware

### Secrets & Configuration
- [ ] No one-time scripts (`rekey.php`, `install.php`) accessible in webroot — delete after use
- [ ] No backup archives (`.zip`, `.sql`, `.tar.gz`) in webroot — block in `.htaccess` + rotate credentials if ever present
- [ ] `CRON_KEY` / `CRON_SECRET` is 32+ chars random, with no hardcoded fallback default in `env('KEY', 'default')`
- [ ] No hardcoded passwords in controllers
- [ ] `.env` has `APP_DEBUG=false` and `LOG_LEVEL=error`
- [ ] `.env` file permissions: `600` (Linux) or deny `Users` group (Windows)

### XSS / Output Encoding
- [ ] No `{!! !!}` on user-controlled data in Blade views (use `{{ }}` or purifier for trusted rich text)
- [ ] No `{{ $var }}` inside `<script>` tags or JS strings — use `@json($var)` instead
- [ ] Email templates using `{!! $body !!}` replaced with `{!! nl2br(e($body)) !!}` or `{{ $body }}`

### Dependencies
- [ ] Run `composer audit` — address all HIGH/CRITICAL advisories
- [ ] Pin minimum safe versions in `composer.json` for vulnerable packages found by audit
- [ ] Update with `composer update vendor/pkg --with-all-dependencies` then re-audit

### IP & Proxy
- [ ] `TrustProxies` configured correctly — don't blindly trust `X-Forwarded-For` or `HTTP_CLIENT_IP`
- [ ] Use `$request->ip()` not `$_SERVER['HTTP_X_FORWARDED_FOR']`

### Backdoor Detection
- [ ] Run grep backdoor checks on `app/`, `public/`, `storage/app/public/`:
  ```bash
  grep -rn "eval(\|base64_decode(\|gzinflate\|shell_exec\|exec(\|system(" app/ --include="*.php"
  find storage/app/public -name "*.php" -o -name "*.phtml" -o -name "*.phar"
  find . -name "*.php" -not -path "./vendor/*" -not -path "./app/*" \
         -not -path "./bootstrap/*" -not -path "./config/*" \
         -not -path "./routes/*" | grep -v "artisan\|index.php\|server.php"
  ```

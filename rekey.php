<?php
/**
 * ONE-TIME APP KEY REGENERATOR
 * Upload this file to your /public folder, visit it once in your browser,
 * then it deletes itself automatically.
 * 
 * URL: https://yourdomain.com/rekey.php
 */

// ── Safety: only run when confirmed ──────────────────────────────────────────
$confirmed = isset($_POST['confirm']) && $_POST['confirm'] === 'yes';

// ── Locate .env (search up to 3 levels above this script) ───────────────────
$envPath = null;
$search  = __DIR__;
for ($i = 0; $i < 4; $i++) {
    $candidate = $search . DIRECTORY_SEPARATOR . '.env';
    if (file_exists($candidate)) {
        $envPath = $candidate;
        break;
    }
    $parent = dirname($search);
    if ($parent === $search) break; // reached filesystem root
    $search = $parent;
}

if (!$envPath) {
    die('<p style="color:red">ERROR: .env file not found. Searched from ' . htmlspecialchars(__DIR__) . ' up 4 levels. Make sure this file is inside your Laravel <code>public/</code> folder.</p>');
}

if (!$confirmed) {
    // Show confirmation form
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Regenerate App Key</title>
        <style>
            body { font-family: Arial, sans-serif; max-width: 600px; margin: 60px auto; padding: 20px; }
            .warning { background: #fff3cd; border: 1px solid #ffc107; padding: 15px; border-radius: 6px; margin-bottom: 20px; }
            .info    { background: #d1ecf1; border: 1px solid #bee5eb; padding: 15px; border-radius: 6px; margin-bottom: 20px; }
            button   { background: #dc3545; color: white; border: none; padding: 12px 24px; font-size: 16px; border-radius: 6px; cursor: pointer; }
            button:hover { background: #c82333; }
        </style>
    </head>
    <body>
        <h2>Regenerate APP_KEY</h2>
        <div class="warning">
            <strong>Warning:</strong> This will log out ALL currently logged-in users (including you).
            Any active admin session from an attacker will be destroyed.
        </div>
        <div class="info">
            <strong>What this does:</strong><br>
            1. Generates a new secure APP_KEY<br>
            2. Updates your <code>.env</code> file<br>
            3. Clears all active sessions from the database<br>
            4. Deletes this script permanently
        </div>
        <form method="POST">
            <input type="hidden" name="confirm" value="yes">
            <button type="submit">Yes, regenerate my APP_KEY now</button>
        </form>
    </body>
    </html>
    <?php
    exit;
}

// ── Generate new key (same as `php artisan key:generate`) ────────────────────
$newKey = 'base64:' . base64_encode(random_bytes(32));

// ── Update .env ───────────────────────────────────────────────────────────────
$envContent = file_get_contents($envPath);
$updated = preg_replace(
    '/^APP_KEY=.*/m',
    'APP_KEY=' . $newKey,
    $envContent
);

if ($updated === null || $updated === $envContent) {
    die('<p style="color:red">ERROR: Could not find APP_KEY line in .env — please update it manually.</p>');
}

if (file_put_contents($envPath, $updated) === false) {
    die('<p style="color:red">ERROR: Could not write to .env — check file permissions.</p>');
}

// ── Clear sessions from DB ────────────────────────────────────────────────────
$sessionCleared = false;
$sessionError   = '';

// Parse DB credentials from .env
preg_match('/^DB_HOST=(.+)$/m',     $updated, $mHost);
preg_match('/^DB_PORT=(.+)$/m',     $updated, $mPort);
preg_match('/^DB_DATABASE=(.+)$/m', $updated, $mDb);
preg_match('/^DB_USERNAME=(.+)$/m', $updated, $mUser);
preg_match('/^DB_PASSWORD=(.*)$/m', $updated, $mPass);

$dbHost = trim($mHost[1] ?? 'localhost');
$dbPort = trim($mPort[1] ?? '3306');
$dbName = trim($mDb[1]   ?? '');
$dbUser = trim($mUser[1] ?? 'root');
$dbPass = trim($mPass[1] ?? '');

if ($dbName) {
    try {
        $pdo = new PDO(
            "mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset=utf8",
            $dbUser,
            $dbPass,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
        $count = $pdo->exec("DELETE FROM sessions");
        $sessionCleared = true;
        $sessionCount   = (int) $count;
    } catch (Exception $e) {
        $sessionError = htmlspecialchars($e->getMessage());
    }
}

// ── Self-destruct ─────────────────────────────────────────────────────────────
@unlink(__FILE__);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Done</title>
    <style>
        body  { font-family: Arial, sans-serif; max-width: 600px; margin: 60px auto; padding: 20px; }
        .ok   { background: #d4edda; border: 1px solid #c3e6cb; padding: 15px; border-radius: 6px; margin-bottom: 12px; }
        .warn { background: #fff3cd; border: 1px solid #ffc107; padding: 15px; border-radius: 6px; margin-bottom: 12px; }
        code  { background: #f8f9fa; padding: 2px 6px; border-radius: 3px; font-size: 13px; }
    </style>
</head>
<body>
    <h2>Done!</h2>

    <div class="ok">
        <strong>✔ New APP_KEY generated</strong><br>
       
    </div>

    <?php if ($sessionCleared): ?>
    <div class="ok">
        <strong>✔ Sessions cleared</strong> — <?= $sessionCount ?> session(s) removed from database.
        All logged-in users (including any attacker) have been logged out.
    </div>
    <?php elseif ($sessionError): ?>
    <div class="warn">
        <strong>⚠ Could not clear sessions:</strong> <?= $sessionError ?><br>
        Please manually run: <code>DELETE FROM sessions;</code> in phpMyAdmin.
    </div>
    <?php endif; ?>

    <div class="ok">
        <strong>✔ This script has been deleted.</strong>
    </div>

    <p><strong>Next step:</strong> Log back into your admin panel and verify your deposit wallet addresses are correct.</p>
</body>
</html>

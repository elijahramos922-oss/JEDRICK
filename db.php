<?php
// Database connection with environment configuration and simple .env loader.
// This makes the project easier to deploy to shared hosts or Render.

// Load a .env file if present (simple parser, not for secrets in production)
if (file_exists(__DIR__ . '/.env')) {
    $lines = file(__DIR__ . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || strpos($line, '#') === 0) continue;
        if (strpos($line, '=') === false) continue;
        list($k, $v) = explode('=', $line, 2);
        $k = trim($k);
        $v = trim($v);
        // remove surrounding quotes
        if ((substr($v,0,1) === '"' && substr($v,-1) === '"') || (substr($v,0,1) === "'" && substr($v,-1) === "'")) {
            $v = substr($v,1,-1);
        }
        putenv("$k=$v");
        $_ENV[$k] = $v;
    }
}

// Read configuration from environment, with sane defaults for local dev
$db_host = getenv('DB_HOST') !== false ? getenv('DB_HOST') : '127.0.0.1';
$db_port = getenv('DB_PORT') !== false ? intval(getenv('DB_PORT')) : 3306;
$db_user = getenv('DB_USER') !== false ? getenv('DB_USER') : 'root';
$db_pass = getenv('DB_PASS') !== false ? getenv('DB_PASS') : '';
$db_name = getenv('DB_NAME') !== false ? getenv('DB_NAME') : 'birthday_jedrick';

$conn = null;
$db_error = null;

// Try connecting; if port is 0 or missing, let mysqli use defaults
try {
    if ($db_port > 0) {
        $conn = @new mysqli($db_host, $db_user, $db_pass, $db_name, $db_port);
    } else {
        $conn = @new mysqli($db_host, $db_user, $db_pass, $db_name);
    }

    if ($conn && !$conn->connect_error) {
        $conn->set_charset('utf8mb4');
    } else {
        $db_error = $conn ? $conn->connect_error : 'Unknown connection error';
        if ($conn instanceof mysqli) { $conn->close(); $conn = null; }
    }
} catch (Throwable $e) {
    $db_error = $e->getMessage();
    $conn = null;
}

// $conn is either a valid mysqli connection or null; save_wish.php handles fallback.

<?php
require_once 'db.php';
if ($conn instanceof mysqli && !$conn->connect_error) {
    echo "OK: connected to database\n";
} else {
    echo "FAIL: cannot connect to database\n";
    if (isset($db_error)) echo "ERROR: $db_error\n";
}

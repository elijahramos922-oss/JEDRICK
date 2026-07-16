<?php

$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'birthday_jedrick';

$conn = null;
$db_error = null;

$ports_to_try = [null, 3306, 3307];

foreach ($ports_to_try as $port) {
    try {
        if ($port === null) {

            $conn = @new mysqli($db_host, $db_user, $db_pass, $db_name);
        } else {
   
            $conn = @new mysqli('127.0.0.1', $db_user, $db_pass, $db_name, $port);
        }

        if ($conn && !$conn->connect_error) {
            $conn->set_charset('utf8mb4');
            $db_error = null;
            break;
        } else {
            $db_error = $conn ? $conn->connect_error : 'Unknown connection error';
            if ($conn instanceof mysqli) {
                $conn->close();
                $conn = null;
            }
        }
    } catch (Throwable $e) {
        $db_error = $e->getMessage();
        $conn = null;
    }
}
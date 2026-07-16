<?php
header('Content-Type: application/json');
require_once 'db.php';

// Password check happens server-side too, hindi lang sa JS,
// dahil madaling i-bypass ang client-side check.
$correct_password = '1412';

$data = json_decode(file_get_contents('php://input'), true);

$password = isset($data['password']) ? $data['password'] : '';
$wish = isset($data['wish']) ? trim($data['wish']) : '';

if ($password !== $correct_password) {
    echo json_encode(['success' => false, 'message' => 'Mali ang password.']);
    exit;
}

if ($wish === '') {
    echo json_encode(['success' => false, 'message' => 'Wala kang nilagay na wish.']);
    exit;
}

if (mb_strlen($wish) > 500) {
    echo json_encode(['success' => false, 'message' => 'Ang haba naman, paikliin mo konti.']);
    exit;
}

$save_success = false;
$message = '';

if ($conn instanceof mysqli && !$conn->connect_error) {
    $stmt = $conn->prepare('INSERT INTO wishes (wish_text) VALUES (?)');

    if ($stmt) {
        $stmt->bind_param('s', $wish);

        if ($stmt->execute()) {
            $save_success = true;
            $message = 'Na-save ang wish mo!';
        } else {
            $message = 'Hindi na-save: ' . $stmt->error;
        }

        $stmt->close();
    } else {
        $message = 'Hindi ma-prepare ang query: ' . $conn->error;
    }
} else {
    $storage_file = __DIR__ . '/wishes.json';
    $wishes = [];

    if (file_exists($storage_file)) {
        $contents = file_get_contents($storage_file);
        $decoded = json_decode($contents, true);

        if (is_array($decoded)) {
            $wishes = $decoded;
        }
    }

    $wishes[] = [
        'wish_text' => $wish,
        'created_at' => date('Y-m-d H:i:s'),
        'source' => 'file_fallback'
    ];

    file_put_contents($storage_file, json_encode($wishes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);

    $save_success = true;
    $message = 'Na-save ang wish mo! (saved locally because the database was unavailable)';
}

if ($conn instanceof mysqli) {
    $conn->close();
}

echo json_encode(['success' => $save_success, 'message' => $message]);
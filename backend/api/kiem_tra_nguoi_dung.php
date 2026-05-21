<?php
// backend/api/check_user.php
header('Content-Type: application/json');
require_once '../config/database.php';

$username = $_GET['username'] ?? '';

if (empty($username)) {
    echo json_encode(['status' => 'error', 'message' => 'Username empty']);
    exit;
}

$db = getDBConnection();
$stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
$stmt->execute([$username]);
$exists = $stmt->fetchColumn() > 0;

echo json_encode([
    'status' => 'success',
    'exists' => $exists
]);

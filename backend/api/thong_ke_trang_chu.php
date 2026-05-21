<?php
// backend/api/home_stats.php
session_start();
header('Content-Type: application/json');

require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$db = getDBConnection();
$userId = $_SESSION['user_id'];

// Count pending bookings for this user
$stmt1 = $db->prepare("SELECT COUNT(*) FROM bookings WHERE user_id = ? AND status = 'pending'");
$stmt1->execute([$userId]);
$pendingCount = $stmt1->fetchColumn();

// Count broken equipment (global or relevant to user? usually global for reporting)
$stmt2 = $db->query("SELECT COUNT(*) FROM equipment WHERE condition_status != 'good'");
$brokenCount = $stmt2->fetchColumn();

echo json_encode([
    'status' => 'success',
    'pending_count' => (int)$pendingCount,
    'broken_count' => (int)$brokenCount
]);

<?php
// api/stats.php
session_start();
header('Content-Type: application/json');

require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role_name'] !== 'admin') {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$db = getDBConnection();

// Thống kê số lượng đặt phòng trong 7 ngày gần nhất
$stats = [];
for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $displayDate = date('d/m', strtotime("-$i days"));
    
    $stmt = $db->prepare("SELECT COUNT(*) FROM bookings WHERE booking_date = ?");
    $stmt->execute([$date]);
    $count = $stmt->fetchColumn();
    
    $stats['labels'][] = $displayDate;
    $stats['data'][] = (int)$count;
}

echo json_encode(['status' => 'success', 'data' => $stats]);
?>

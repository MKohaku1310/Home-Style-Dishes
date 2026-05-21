<?php
// backend/api/equipment_report.php
session_start();
header('Content-Type: application/json');
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$db = getDBConnection();
$userId = $_SESSION['user_id'];
$action = $_POST['action'] ?? $_GET['action'] ?? 'submit';

if ($action === 'submit') {
    $roomId = $_POST['room_id'] ?? null;
    $equipmentName = $_POST['equipment_name'] ?? '';
    $description = $_POST['description'] ?? '';
    $urgency = $_POST['urgency'] ?? 'medium';

    if (!$roomId || !$equipmentName || !$description) {
        echo json_encode(['status' => 'error', 'message' => 'Vui lòng điền đầy đủ thông tin']);
        exit;
    }

    $stmt = $db->prepare("INSERT INTO incident_reports (user_id, room_id, equipment_name, description, urgency) 
                          VALUES (?, ?, ?, ?, ?)");
    
    if ($stmt->execute([$userId, $roomId, $equipmentName, $description, $urgency])) {
        echo json_encode(['status' => 'success', 'message' => 'Báo cáo đã được gửi thành công. Cảm ơn bạn!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Có lỗi xảy ra khi lưu báo cáo']);
    }

} elseif ($action === 'list_rooms') {
    $stmt = $db->query("SELECT id, room_number FROM rooms ORDER BY room_number ASC");
    $rooms = $stmt->fetchAll();
    echo json_encode(['status' => 'success', 'data' => $rooms]);
}
?>

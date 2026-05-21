<?php
// api/toggle_room_status.php
session_start();
header('Content-Type: application/json');

require_once '../config/database.php';
require_once '../models/Room.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role_name'] !== 'admin') {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$room_id = $_POST['room_id'] ?? 0;
$status = $_POST['status'] ?? '';

if ($room_id > 0 && in_array($status, ['available', 'maintenance', 'closed'])) {
    $roomModel = new Room();
    $result = $roomModel->toggleStatus($room_id, $status);
    
    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'Trạng thái đã cập nhật']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Cập nhật thất bại']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Dữ liệu không hợp lệ']);
}
?>

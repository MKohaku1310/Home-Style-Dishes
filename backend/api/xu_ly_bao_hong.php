<?php
session_start();
header('Content-Type: application/json');
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Bạn cần đăng nhập để thực hiện tính năng này.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'];
    $roomId = $_POST['room_id'] ?? null;
    $description = $_POST['description'] ?? '';

    if (!$roomId || !$description) {
        echo json_encode(['status' => 'error', 'message' => 'Vui lòng điền đầy đủ thông tin.']);
        exit;
    }

    try {
        $db = getDBConnection();
        $stmt = $db->prepare("INSERT INTO incident_reports (user_id, room_id, description, status) VALUES (?, ?, ?, 'pending')");
        $stmt->execute([$userId, $roomId, $description]);

        echo json_encode(['status' => 'success', 'message' => 'Cảm ơn bạn! Báo cáo sự cố đã được gửi đến quản trị viên.']);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Yêu cầu không hợp lệ.']);
}

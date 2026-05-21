<?php
// api/thong_bao.php
session_start();
header('Content-Type: application/json');

require_once '../config/database.php';
require_once '../models/Notification.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$notifModel = new Notification();
$user_id = $_SESSION['user_id'];
$action = $_GET['action'] ?? 'get_count';

if ($action === 'get_count') {
    $count = $notifModel->getUnreadCount($user_id);
    echo json_encode(['status' => 'success', 'count' => (int)$count]);
} elseif ($action === 'list') {
    $limit = $_GET['limit'] ?? 5;
    $list = $notifModel->getLatest($user_id, $limit);
    echo json_encode(['status' => 'success', 'data' => $list]);
} elseif ($action === 'mark_read') {
    $id = $_POST['id'] ?? null;
    $notifModel->markAsRead($user_id, $id);
    echo json_encode(['status' => 'success']);
}
?>

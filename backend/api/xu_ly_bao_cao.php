<?php
session_start();
header('Content-Type: application/json');
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role_name'] !== 'admin') {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
    $offset = ($page - 1) * $limit;

    $db = getDBConnection();
    
    // Total count
    $totalItems = $db->query("SELECT COUNT(*) FROM incident_reports")->fetchColumn();
    $totalPages = ceil($totalItems / $limit);

    // Data
    $stmt = $db->prepare("
        SELECT ir.*, u.fullname, r.room_number 
        FROM incident_reports ir 
        JOIN users u ON ir.user_id = u.id 
        JOIN rooms r ON ir.room_id = r.id 
        ORDER BY ir.created_at DESC 
        LIMIT ? OFFSET ?
    ");
    $stmt->execute([$limit, $offset]);
    $reports = $stmt->fetchAll();

    echo json_encode([
        'status' => 'success', 
        'data' => $reports,
        'pagination' => [
            'total_items' => (int)$totalItems,
            'total_pages' => (int)$totalPages,
            'current_page' => $page,
            'limit' => $limit
        ]
    ]);
    exit;
}

$id = $_POST['id'] ?? null;
$action = $_POST['action'] ?? '';


if ($id && $action === 'resolve') {
    try {
        $db = getDBConnection();
        $stmt = $db->prepare("UPDATE incident_reports SET status = 'resolved' WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode(['status' => 'success', 'message' => 'Đã cập nhật trạng thái xử lý.']);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid parameters.']);
}

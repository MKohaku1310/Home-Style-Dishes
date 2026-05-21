<?php
// backend/api/admin_bookings.php
session_start();
header('Content-Type: application/json');

require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role_name'] !== 'admin') {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$db = getDBConnection();
$status = $_GET['status'] ?? 'all';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$offset = ($page - 1) * $limit;

$baseSql = "FROM bookings b 
            JOIN rooms r ON b.room_id = r.id 
            JOIN room_sessions s ON b.session_id = s.id 
            JOIN users u ON b.user_id = u.id";
$params = [];

if ($status !== 'all') {
    $baseSql .= " WHERE b.status = ?";
    $params[] = $status;
}

// Get total count
$countStmt = $db->prepare("SELECT COUNT(*) " . $baseSql);
$countStmt->execute($params);
$totalItems = $countStmt->fetchColumn();
$totalPages = ceil($totalItems / $limit);

// Get paginated data
$sql = "SELECT b.*, r.room_number, s.session_name, u.username, u.fullname " . $baseSql . " 
        ORDER BY b.status = 'pending' DESC, b.created_at DESC 
        LIMIT ? OFFSET ?";
$params[] = $limit;
$params[] = $offset;

$stmt = $db->prepare($sql);
$stmt->execute($params);
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalPending = $db->query("SELECT COUNT(*) FROM bookings WHERE status = 'pending'")->fetchColumn();

echo json_encode([
    'status' => 'success', 
    'data' => $bookings,
    'total_pending' => (int)$totalPending,
    'pagination' => [
        'total_items' => (int)$totalItems,
        'total_pages' => (int)$totalPages,
        'current_page' => $page,
        'limit' => $limit
    ]
]);


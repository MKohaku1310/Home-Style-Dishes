<?php
// backend/api/user_history.php
session_start();
header('Content-Type: application/json');

require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$db = getDBConnection();
$userId = $_SESSION['user_id'];
$status = $_GET['status'] ?? 'all';
$action = $_GET['action'] ?? 'list';

if ($action === 'detail') {
    $id = $_GET['id'] ?? 0;
    $stmt = $db->prepare("SELECT b.*, r.room_number, s.session_name, s.start_time, s.end_time 
                          FROM bookings b 
                          JOIN rooms r ON b.room_id = r.id 
                          JOIN room_sessions s ON b.session_id = s.id 
                          WHERE b.id = ? AND b.user_id = ?");
    $stmt->execute([$id, $userId]);
    $booking = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($booking) {
        echo json_encode(['status' => 'success', 'data' => $booking]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Not found']);
    }
    exit;
}

// Default action: list
$isToday = isset($_GET['today']) && $_GET['today'] == '1';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$offset = ($page - 1) * $limit;

$baseSql = "FROM bookings b 
            JOIN rooms r ON b.room_id = r.id 
            JOIN room_sessions s ON b.session_id = s.id 
            WHERE b.user_id = ?";
$params = [$userId];

if ($isToday) {
    $baseSql .= " AND b.booking_date = CURRENT_DATE";
}

if ($status !== 'all') {
    $baseSql .= " AND b.status = ?";
    $params[] = $status;
}

// Get total count
$countStmt = $db->prepare("SELECT COUNT(*) " . $baseSql);
$countStmt->execute($params);
$totalItems = $countStmt->fetchColumn();
$totalPages = ceil($totalItems / $limit);

// Get paginated data
$sql = "SELECT b.*, r.room_number, s.session_name, s.start_time, s.end_time " . $baseSql . " ORDER BY b.created_at DESC LIMIT ? OFFSET ?";
$params[] = $limit;
$params[] = $offset;

$stmt = $db->prepare($sql);
$stmt->execute($params);
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Add status_text helper
$statusMap = [
    'pending' => 'Đang chờ',
    'approved' => 'Đã duyệt',
    'rejected' => 'Bị từ chối',
    'cancelled' => 'Đã hủy'
];

foreach ($bookings as &$b) {
    $b['status_text'] = $statusMap[$b['status']] ?? $b['status'];
}

echo json_encode([
    'status' => 'success', 
    'data' => $bookings,
    'pagination' => [
        'total_items' => (int)$totalItems,
        'total_pages' => (int)$totalPages,
        'current_page' => $page,
        'limit' => $limit
    ]
]);


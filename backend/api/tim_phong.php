<?php
/**
 * API Tìm kiếm phòng trống dựa trên nhiều tiêu chí (Ngày, Ca học, Tầng, Sức chứa)
 */
header('Content-Type: application/json');
require_once '../config/database.php';

// Tiếp nhận các tham số tìm kiếm từ URL
$query = $_GET['q'] ?? '';
$date = $_GET['date'] ?? date('Y-m-d');
$sessionId = $_GET['session_id'] ?? 1;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 6; 
$offset = ($page - 1) * $limit;

$floor = $_GET['floor'] ?? '';
$capacity = $_GET['capacity'] ?? 0;

$db = getDBConnection();

/**
 * Xây dựng câu lệnh điều kiện (WHERE) cơ bản:
 * 1. Phòng phải đang ở trạng thái 'available'
 * 2. Tên phòng hoặc loại phòng khớp với từ khóa tìm kiếm
 * 3. QUAN TRỌNG: ID phòng KHÔNG được nằm trong danh sách các phòng đã được đặt (và đã duyệt hoặc đang chờ duyệt) 
 *    tại đúng ngày và ca học đã chọn.
 */
$whereClause = "status = 'available' 
                AND (room_number LIKE :search1 OR room_type LIKE :search2)
                AND id NOT IN (
                    SELECT room_id FROM bookings 
                    WHERE date(booking_date) = date(:bdate)
                    AND CAST(session_id AS INTEGER) = CAST(:sid AS INTEGER)
                    AND status IN ('approved', 'pending')
                )";

// Bổ sung các bộ lọc nâng cao nếu có
if ($floor !== '') {
    $whereClause .= " AND floor = :floor";
}
if ($capacity > 0) {
    $whereClause .= " AND capacity >= :capacity";
}

// BƯỚC 1: Đếm tổng số lượng kết quả để tính toán phân trang
$countSql = "SELECT COUNT(*) FROM rooms WHERE $whereClause";
$countStmt = $db->prepare($countSql);
$search = "%" . trim($query) . "%";
$params = [
    ':search1' => $search, 
    ':search2' => $search,
    ':bdate' => trim($date), 
    ':sid' => (int)$sessionId
];
if ($floor !== '') $params[':floor'] = $floor;
if ($capacity > 0) $params[':capacity'] = (int)$capacity;

$countStmt->execute($params);
$totalItems = $countStmt->fetchColumn();
$totalPages = ceil($totalItems / $limit);

// BƯỚC 2: Truy vấn lấy dữ liệu chi tiết của trang hiện tại
$sql = "SELECT * FROM rooms 
        WHERE $whereClause
        ORDER BY room_number ASC
        LIMIT :limit OFFSET :offset";

$stmt = $db->prepare($sql);
$stmt->bindValue(':search1', $search, PDO::PARAM_STR);
$stmt->bindValue(':search2', $search, PDO::PARAM_STR);
$stmt->bindValue(':bdate', trim($date), PDO::PARAM_STR);
$stmt->bindValue(':sid', (int)$sessionId, PDO::PARAM_INT);
if ($floor !== '') $stmt->bindValue(':floor', $floor, PDO::PARAM_INT);
if ($capacity > 0) $stmt->bindValue(':capacity', (int)$capacity, PDO::PARAM_INT);
$stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
$stmt->execute();
$rooms = $stmt->fetchAll();

// Trả về kết quả dạng JSON cho Frontend xử lý
echo json_encode([
    'status' => 'success', 
    'data' => $rooms,
    'pagination' => [
        'total_items' => (int)$totalItems,
        'total_pages' => (int)$totalPages,
        'current_page' => $page,
        'limit' => $limit
    ]
]);
?>


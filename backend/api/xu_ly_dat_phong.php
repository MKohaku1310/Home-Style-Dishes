<?php
/**
 * API xử lý các hành động liên quan đến yêu cầu đặt phòng (Bookings)
 */
session_start();
header('Content-Type: application/json');

require_once '../config/database.php';
require_once '../models/Booking.php';
require_once '../models/Notification.php';

// Xác định hành động cần thực hiện (create, bulk_approve, bulk_reject, reset_status)
$action = $_GET['action'] ?? '';

// Đồng bộ dữ liệu đầu vào (Hỗ trợ cả POST truyền thống và dữ liệu JSON từ Fetch API)
if (empty($_POST)) {
    $_POST = json_decode(file_get_contents('php://input'), true) ?? [];
}

$bookingModel = new Booking();

/**
 * HÀNH ĐỘNG: Tạo yêu cầu đặt phòng mới (Dành cho Sinh viên/Giảng viên)
 */
if ($action === 'create') {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['status' => 'error', 'message' => 'Bạn cần đăng nhập.']);
        exit;
    }

    $data = [
        'user_id' => $_SESSION['user_id'],
        'room_id' => $_POST['room_id'] ?? 0,
        'booking_date' => $_POST['booking_date'] ?? '',
        'session_id' => $_POST['session_id'] ?? 0,
        'purpose' => $_POST['purpose'] ?? ''
    ];

    // Kiểm tra tính đầy đủ của dữ liệu
    if (!$data['room_id'] || !$data['booking_date'] || !$data['session_id']) {
        echo json_encode(['status' => 'error', 'message' => 'Dữ liệu không đầy đủ.']);
        exit;
    }

    // Gọi Model để thực hiện lưu trữ (Đã có logic kiểm tra trùng lịch bên trong Model)
    $result = $bookingModel->createBooking($data);

    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'Đã gửi yêu cầu đặt phòng.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Phòng này đã có người đăng ký hoặc đang chờ duyệt vào thời gian bạn chọn. Vui lòng chọn phòng khác!']);
    }

/**
 * HÀNH ĐỘNG: Duyệt hàng loạt (Dành cho Admin)
 */
} elseif ($action === 'bulk_approve') {
    if (!isset($_SESSION['user_id']) || $_SESSION['role_name'] !== 'admin') {
        echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
        exit;
    }

    $ids = $_POST['ids'] ?? [];
    if (empty($ids) || !is_array($ids)) {
        echo json_encode(['status' => 'error', 'message' => 'Chưa chọn yêu cầu nào.']);
        exit;
    }

    $db = getDBConnection();
    $notifModel = new Notification();

    // Bước 1: Tìm danh sách ID người dùng của các yêu cầu được chọn để gửi thông báo sau này
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmtUser = $db->prepare("SELECT DISTINCT user_id FROM bookings WHERE id IN ($placeholders)");
    $stmtUser->execute($ids);
    $userIds = $stmtUser->fetchAll(PDO::FETCH_COLUMN);

    // Bước 2: Cập nhật trạng thái 'approved' cho tất cả các bản ghi đang ở trạng thái 'pending'
    $stmt = $db->prepare("UPDATE bookings SET status = 'approved' WHERE id IN ($placeholders) AND status = 'pending'");
    
    if ($stmt->execute($ids)) {
        // Bước 3: Gửi thông báo hệ thống cho từng người dùng liên quan
        foreach ($userIds as $uid) {
            $notifModel->create($uid, 'Yêu cầu được duyệt', 'Yêu cầu mượn phòng học của bạn đã được Admin phê duyệt.');
        }
        echo json_encode(['status' => 'success', 'message' => 'Đã duyệt ' . $stmt->rowCount() . ' yêu cầu.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Lỗi khi duyệt.']);
    }

/**
 * HÀNH ĐỘNG: Từ chối hàng loạt kèm lý do (Dành cho Admin)
 */
} elseif ($action === 'bulk_reject') {
    if (!isset($_SESSION['user_id']) || $_SESSION['role_name'] !== 'admin') {
        echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
        exit;
    }

    $ids = $_POST['ids'] ?? [];
    if (empty($ids) || !is_array($ids)) {
        echo json_encode(['status' => 'error', 'message' => 'Chưa chọn yêu cầu nào.']);
        exit;
    }

    $db = getDBConnection();
    $notifModel = new Notification();

    $admin_note = $_POST['admin_note'] ?? 'Không đủ điều kiện hoặc trùng lịch';

    // Tìm danh sách người dùng bị từ chối
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmtUser = $db->prepare("SELECT DISTINCT user_id FROM bookings WHERE id IN ($placeholders)");
    $stmtUser->execute($ids);
    $userIds = $stmtUser->fetchAll(PDO::FETCH_COLUMN);

    // Cập nhật trạng thái 'rejected' và thêm ghi chú lý do của Admin
    $stmt = $db->prepare("UPDATE bookings SET status = 'rejected', admin_note = ? WHERE id IN ($placeholders) AND status = 'pending'");
    
    $params = array_merge([$admin_note], $ids);
    if ($stmt->execute($params)) {
        // Gửi thông báo cho người dùng
        foreach ($userIds as $uid) {
            $notifModel->create($uid, 'Yêu cầu bị từ chối', 'Admin đã từ chối yêu cầu mượn phòng của bạn. Xem chi tiết trong lịch sử.');
        }
        echo json_encode(['status' => 'success', 'message' => 'Đã từ chối ' . $stmt->rowCount() . ' yêu cầu.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Lỗi cập nhật.']);
    }

/**
 * HÀNH ĐỘNG: Hoàn tác trạng thái (Đặt lại về Chờ duyệt)
 */
} elseif ($action === 'reset_status') {
    if (!isset($_SESSION['user_id']) || $_SESSION['role_name'] !== 'admin') {
        echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
        exit;
    }

    $id = $_POST['id'] ?? null;
    if (!$id) {
        echo json_encode(['status' => 'error', 'message' => 'Thiếu ID yêu cầu.']);
        exit;
    }

    $db = getDBConnection();
    $stmt = $db->prepare("UPDATE bookings SET status = 'pending', admin_note = NULL WHERE id = ?");
    
    if ($stmt->execute([$id])) {
        echo json_encode(['status' => 'success', 'message' => 'Đã đặt lại trạng thái về Chờ duyệt.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Lỗi cập nhật.']);
    }

} else {
    echo json_encode(['status' => 'error', 'message' => 'Action không hợp lệ.']);
}
?>

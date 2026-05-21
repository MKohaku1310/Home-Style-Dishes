<?php
/**
 * API thực hiện các thao tác CRUD (Thêm, Sửa, Xóa, Liệt kê) danh mục Phòng học
 */
session_start();
header('Content-Type: application/json');

require_once '../config/database.php';
require_once '../models/Room.php';

// Kiểm tra quyền: Chỉ tài khoản Admin mới được phép thực hiện các thao tác này
if (!isset($_SESSION['user_id']) || $_SESSION['role_name'] !== 'admin') {
    echo json_encode(['status' => 'error', 'message' => 'Bạn không có quyền thực hiện hành động này.']);
    exit;
}

$roomModel = new Room();

/**
 * XỬ LÝ YÊU CẦU GET: Lấy danh sách phòng học có phân trang
 */
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
    
    $result = $roomModel->getRoomsPaginated($page, $limit);
    echo json_encode(['status' => 'success', 'data' => $result['data'], 'pagination' => $result['pagination']]);
    exit;
}

// Lấy hành động cụ thể từ POST data
$action = $_POST['action'] ?? '';

try {
    switch ($action) {
        case 'create':
            // Thêm phòng mới
            $result = $roomModel->createRoom($_POST);
            if ($result) {
                echo json_encode(['status' => 'success', 'message' => 'Phòng học đã được tạo thành công.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Lỗi khi tạo phòng học.']);
            }
            break;

        case 'update':
            // Cập nhật thông tin phòng học qua ID
            $id = $_POST['id'] ?? 0;
            if ($id > 0) {
                $result = $roomModel->updateRoom($id, $_POST);
                if ($result) {
                    echo json_encode(['status' => 'success', 'message' => 'Cập nhật thông tin phòng thành công.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Lỗi khi cập nhật phòng học.']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Thiếu ID phòng học cần cập nhật.']);
            }
            break;

        case 'delete':
            // Xóa phòng học
            $id = $_POST['id'] ?? 0;
            if ($id > 0) {
                $result = $roomModel->deleteRoom($id);
                if ($result) {
                    echo json_encode(['status' => 'success', 'message' => 'Đã xóa phòng học khỏi hệ thống.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Lỗi khi xóa phòng học.']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Thiếu ID phòng học cần xóa.']);
            }
            break;

        default:
            echo json_encode(['status' => 'error', 'message' => 'Hành động không hợp lệ.']);
            break;
    }
} catch (PDOException $e) {
    // Xử lý các lỗi đặc thù từ Cơ sở dữ liệu (SQLite)
    $errorMsg = $e->getMessage();
    if (strpos($errorMsg, 'UNIQUE constraint failed') !== false) {
        echo json_encode(['status' => 'error', 'message' => 'Số phòng này đã tồn tại trong hệ thống. Vui lòng chọn số khác.']);
    } elseif (strpos($errorMsg, 'FOREIGN KEY constraint failed') !== false) {
        echo json_encode(['status' => 'error', 'message' => 'Không thể xóa do có dữ liệu liên quan (lịch đặt hoặc thiết bị) đang sử dụng phòng này.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Lỗi CSDL: ' . $errorMsg]);
    }
}
?>

<?php
// backend/api/quan_ly_nguoi_dung.php

require_once '../config/database.php';
session_start();

// Kiểm tra quyền Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role_name'] !== 'admin') {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Không có quyền truy cập.']);
    exit;
}

$db = getDBConnection();
$action = $_POST['action'] ?? ($_GET['action'] ?? 'list');

header('Content-Type: application/json');

try {
    switch ($action) {
        case 'list':
            $page = (int)($_GET['page'] ?? 1);
            $limit = 10;
            $offset = ($page - 1) * $limit;
            $search = $_GET['q'] ?? '';
            $role_id = $_GET['role_id'] ?? 'all';

            $where = "WHERE 1=1";
            $params = [];
            if ($search) {
                $where .= " AND (u.username LIKE ? OR u.fullname LIKE ? OR u.email LIKE ?)";
                $params = array_merge($params, ["%$search%", "%$search%", "%$search%"]);
            }
            if ($role_id !== 'all') {
                $where .= " AND u.role_id = ?";
                $params[] = $role_id;
            }

            // Đếm tổng
            $stmtCount = $db->prepare("SELECT COUNT(*) FROM users u $where");
            $stmtCount->execute($params);
            $totalItems = $stmtCount->fetchColumn();
            $totalPages = ceil($totalItems / $limit);

            // Lấy dữ liệu
            $stmt = $db->prepare("
                SELECT u.id, u.username, u.fullname, u.email, u.role_id, u.status, r.role_name 
                FROM users u 
                JOIN roles r ON u.role_id = r.id 
                $where 
                ORDER BY u.id DESC 
                LIMIT $limit OFFSET $offset
            ");
            $stmt->execute($params);
            $users = $stmt->fetchAll();

            echo json_encode([
                'status' => 'success',
                'data' => $users,
                'pagination' => [
                    'total_items' => (int)$totalItems,
                    'total_pages' => (int)$totalPages,
                    'current_page' => $page,
                    'limit' => $limit
                ]
            ]);
            break;

        case 'create':
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $fullname = $_POST['fullname'] ?? '';
            $email = $_POST['email'] ?? '';
            $role_id = $_POST['role_id'] ?? '';
            $status = $_POST['status'] ?? 'active';

            if (empty($username) || empty($password) || empty($fullname) || empty($email) || empty($role_id)) {
                echo json_encode(['status' => 'error', 'message' => 'Vui lòng nhập đầy đủ thông tin.']);
                exit;
            }

            // Kiểm tra trùng
            $stmtCheck = $db->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $stmtCheck->execute([$username, $email]);
            if ($stmtCheck->rowCount() > 0) {
                echo json_encode(['status' => 'error', 'message' => 'Mã người dùng hoặc Email đã tồn tại.']);
                exit;
            }

            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmtInsert = $db->prepare("INSERT INTO users (username, password, fullname, email, role_id, status) VALUES (?, ?, ?, ?, ?, ?)");
            $stmtInsert->execute([$username, $hashed, $fullname, $email, $role_id, $status]);

            echo json_encode(['status' => 'success', 'message' => 'Thêm người dùng thành công.']);
            break;

        case 'update':
            $id = $_POST['id'] ?? '';
            $username = $_POST['username'] ?? '';
            $fullname = $_POST['fullname'] ?? '';
            $email = $_POST['email'] ?? '';
            $role_id = $_POST['role_id'] ?? '';
            $status = $_POST['status'] ?? 'active';
            $password = $_POST['password'] ?? '';

            if (empty($id) || empty($username) || empty($fullname) || empty($email) || empty($role_id)) {
                echo json_encode(['status' => 'error', 'message' => 'Vui lòng nhập đầy đủ thông tin.']);
                exit;
            }

            // Cập nhật thông tin cơ bản
            $sql = "UPDATE users SET username = ?, fullname = ?, email = ?, role_id = ?, status = ? WHERE id = ?";
            $params = [$username, $fullname, $email, $role_id, $status, $id];
            
            $stmtUpdate = $db->prepare($sql);
            $stmtUpdate->execute($params);

            // Cập nhật mật khẩu nếu có nhập mới
            if (!empty($password)) {
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $stmtPass = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
                $stmtPass->execute([$hashed, $id]);
            }

            echo json_encode(['status' => 'success', 'message' => 'Cập nhật thành công.']);
            break;

        case 'delete':
            $id = $_POST['id'] ?? '';
            if (empty($id)) {
                echo json_encode(['status' => 'error', 'message' => 'Thiếu ID.']);
                exit;
            }

            // Không cho phép tự xóa chính mình
            if ($id == $_SESSION['user_id']) {
                echo json_encode(['status' => 'error', 'message' => 'Bạn không thể tự xóa tài khoản của chính mình.']);
                exit;
            }

            $stmtDel = $db->prepare("DELETE FROM users WHERE id = ?");
            $stmtDel->execute([$id]);

            echo json_encode(['status' => 'success', 'message' => 'Đã xóa người dùng.']);
            break;

        default:
            echo json_encode(['status' => 'error', 'message' => 'Hành động không hợp lệ.']);
            break;
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
}

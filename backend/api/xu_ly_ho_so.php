<?php
session_start();
header('Content-Type: application/json');
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$action = $_GET['action'] ?? '';
$db = getDBConnection();
$userId = $_SESSION['user_id'];

if ($action === 'update_profile') {
    $fullname = $_POST['fullname'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';

    if (!$fullname) {
        echo json_encode(['status' => 'error', 'message' => 'Họ tên không được để trống.']);
        exit;
    }

    try {
        $stmt = $db->prepare("UPDATE users SET fullname = ?, email = ?, phone = ? WHERE id = ?");
        $stmt->execute([$fullname, $email, $phone, $userId]);
        
        $_SESSION['fullname'] = $fullname; // Update session
        
        echo json_encode(['status' => 'success', 'message' => 'Cập nhật thông tin thành công.']);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
    }

} elseif ($action === 'change_password') {
    $current = $_POST['current_password'] ?? '';
    $new = $_POST['new_password'] ?? '';

    if (!$current || !$new) {
        echo json_encode(['status' => 'error', 'message' => 'Vui lòng nhập đầy đủ thông tin.']);
        exit;
    }

    try {
        // Lấy pass cũ
        $stmt = $db->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();

        // Trong demo này, giả sử mật khẩu chưa hash (hoặc dùng password_verify nếu đã hash)
        // Nếu đã hash: if (!password_verify($current, $user['password']))
        if ($current !== $user['password']) {
            echo json_encode(['status' => 'error', 'message' => 'Mật khẩu hiện tại không chính xác.']);
            exit;
        }

        $stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->execute([$new, $userId]);
        
        echo json_encode(['status' => 'success', 'message' => 'Đổi mật khẩu thành công.']);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
    }

} else {
    echo json_encode(['status' => 'error', 'message' => 'Action không hợp lệ.']);
}

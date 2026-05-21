<?php
// index.php

session_start();
require_once 'backend/config/database.php';

$page = isset($_GET['page']) ? $_GET['page'] : 'trang_chu';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

// Định tuyến cơ bản
switch ($page) {
    case 'xac_thuc':
        require_once 'backend/controllers/DieuKhienXacThuc.php';
        $controller = new DieuKhienXacThuc();
        break;
    case 'quan_tri':
        if (!isset($_SESSION['user_id']) || $_SESSION['role_name'] !== 'admin') {
            header("Location: index.php?page=xac_thuc&action=dang_nhap");
            exit;
        }
        require_once 'backend/controllers/DieuKhienQuanTri.php';
        $controller = new DieuKhienQuanTri();
        break;
    case 'nguoi_dung':
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?page=xac_thuc&action=dang_nhap");
            exit;
        }
        require_once 'backend/controllers/DieuKhienNguoiDung.php';
        $controller = new DieuKhienNguoiDung();
        break;
    case 'trang_chu':
    default:
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?page=xac_thuc&action=dang_nhap");
            exit;
        }
        require_once 'backend/controllers/DieuKhienTrangChu.php';
        $controller = new DieuKhienTrangChu();
        break;
}

if (method_exists($controller, $action)) {
    $controller->$action();
} else {
    http_response_code(404);
    echo "<h1>404 - Trang không tồn tại</h1>";
}
?>

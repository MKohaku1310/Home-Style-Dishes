<?php
/**
 * Controller xử lý các nghiệp vụ dành cho Người dùng thông thường (Sinh viên/Giảng viên)
 */

require_once 'backend/models/Room.php';
require_once 'backend/models/Booking.php';

class DieuKhienNguoiDung {
    private $roomModel;
    private $bookingModel;

    public function __construct() {
        // Kiểm tra xem người dùng đã đăng nhập chưa trước khi cho phép truy cập
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?page=xac_thuc&action=dang_nhap");
            exit;
        }
        $this->roomModel = new Room();
        $this->bookingModel = new Booking();
    }

    /**
     * Hiển thị giao diện Đăng ký đặt phòng
     */
    public function dat_phong() {
        // Lấy danh sách các phòng đang ở trạng thái 'available' (Sẵn sàng phục vụ)
        $rooms = $this->roomModel->getAllRooms();
        $available_rooms = array_filter($rooms, function($r) {
            return $r['status'] === 'available';
        });

        // Lấy danh sách các ca học (Time sessions) từ CSDL
        $db = getDBConnection();
        $sessions = $db->query("SELECT * FROM room_sessions ORDER BY id")->fetchAll();

        require_once 'frontend/views/user/dat_phong.php';
    }

    /**
     * Hiển thị lịch sử các lần đăng ký phòng của chính người dùng hiện tại
     */
    public function lich_su() {
        $bookings = $this->bookingModel->getUserBookings($_SESSION['user_id']);
        require_once 'frontend/views/user/lich_su.php';
    }

    /**
     * Trang Báo hỏng thiết bị
     */
    public function bao_hong() {
        require_once 'frontend/views/user/bao_hong.php';
    }

    /**
     * Phương thức alias cho báo hỏng
     */
    public function bao_cao() {
        $this->bao_hong();
    }

    /**
     * Trang hiển thị danh sách tất cả thông báo của người dùng
     */
    public function thong_bao() {
        require_once 'frontend/views/user/thong_bao.php';
    }

    /**
     * Xem thông tin hồ sơ cá nhân
     */
    public function ho_so() {
        $db = getDBConnection();
        $stmt = $db->prepare("
            SELECT u.*, r.role_name 
            FROM users u 
            JOIN roles r ON u.role_id = r.id 
            WHERE u.id = ?
        ");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();
        require_once 'frontend/views/user/ho_so.php';
    }

    /**
     * Trang đổi mật khẩu
     */
    public function doi_mat_khau() {
        require_once 'frontend/views/user/doi_mat_khau.php';
    }
}
?>

<?php
/**
 * Controller xử lý các nghiệp vụ dành riêng cho quản trị viên (Admin)
 */

require_once 'backend/models/Room.php';
require_once 'backend/models/Booking.php';
require_once 'backend/models/Equipment.php';

class DieuKhienQuanTri {
    private $roomModel;
    private $bookingModel;
    private $equipmentModel;
    private $db;

    public function __construct() {
        // Kiểm tra quyền Admin trước khi cho phép truy cập bất kỳ phương thức nào trong Controller này
        if (!isset($_SESSION['user_id']) || $_SESSION['role_name'] !== 'admin') {
            header("Location: index.php?page=xac_thuc&action=dang_nhap");
            exit;
        }
        $this->roomModel = new Room();
        $this->bookingModel = new Booking();
        $this->equipmentModel = new Equipment();
        $this->db = getDBConnection();
    }

    /**
     * Hiển thị Bảng điều khiển (Dashboard) với các số liệu thống kê tổng hợp
     */
    public function bang_dieu_khien() {
        // Đếm tổng số phòng học
        $stmtTotal = $this->db->query("SELECT COUNT(*) FROM rooms");
        $totalRooms = $stmtTotal->fetchColumn();
        
        // Xác định ca học hiện tại để tính số phòng đang sử dụng
        $now = date('H:i');
        $today = date('Y-m-d');
        $stmtSession = $this->db->prepare("SELECT id, session_name FROM room_sessions WHERE start_time <= ? AND end_time >= ? LIMIT 1");
        $stmtSession->execute([$now, $now]);
        $currentSess = $stmtSession->fetch();
        
        $occupiedRooms = 0;
        $currentSessionName = 'Ngoài giờ';
        if ($currentSess) {
            $currentSessionName = $currentSess['session_name'];
            $stmtOccupied = $this->db->prepare("
                SELECT COUNT(DISTINCT room_id) 
                FROM bookings 
                WHERE booking_date = ? 
                AND session_id = ? 
                AND status = 'approved'
            ");
            $stmtOccupied->execute([$today, $currentSess['id']]);
            $occupiedRooms = $stmtOccupied->fetchColumn();
        }
        
        // Đếm số lượng yêu cầu đặt phòng đang chờ duyệt
        $stmtPending = $this->db->query("SELECT COUNT(*) FROM bookings WHERE status = 'pending'");
        $pendingBookings = $stmtPending->fetchColumn();
        
        // Đếm số lượng báo cáo sự cố thiết bị chưa xử lý
        $stmtBroken = $this->db->query("SELECT COUNT(*) FROM incident_reports WHERE status = 'pending'");
        $brokenEquipment = $stmtBroken->fetchColumn();
        
        require_once 'frontend/views/admin/bang_dieu_khien.php';
    }

    /**
     * Quản lý Lịch mượn phòng (Dưới dạng thời khóa biểu tuần)
     */
    public function lich_muon_phong() {
        $roomId = $_GET['room_id'] ?? null;
        $week = $_GET['week'] ?? date('Y-\WW'); // Lấy tuần hiện tại nếu không chỉ định

        $rooms = $this->roomModel->getAllRooms();
        // Nếu chưa chọn phòng, mặc định lấy phòng đầu tiên trong danh sách
        if (!$roomId && !empty($rooms)) {
            $roomId = $rooms[0]['id'];
        }

        // Nhóm các phòng theo tầng để hiển thị trên menu điều hướng tầng
        $roomsByFloor = [];
        foreach ($rooms as $r) {
            $roomsByFloor[$r['floor']][] = $r;
        }
        
        $currentRoom = array_filter($rooms, fn($r) => $r['id'] == $roomId);
        $currentRoom = reset($currentRoom);
        $currentFloor = $currentRoom ? $currentRoom['floor'] : (array_keys($roomsByFloor)[0] ?? 1);

        // Tính toán danh sách 6 ngày trong tuần (Thứ 2 - Thứ 7) dựa trên số tuần được chọn
        $year = substr($week, 0, 4);
        $weekNum = substr($week, 6);
        $dto = new DateTime();
        $dto->setISODate($year, $weekNum);
        $weekDays = [];
        $dayNames = ['Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7'];
        for ($i = 0; $i < 6; $i++) {
            $weekDays[] = [
                'name' => $dayNames[$i],
                'date' => $dto->format('Y-m-d')
            ];
            $dto->modify('+1 day');
        }

        // Lấy danh sách các ca học (Time sessions)
        $sessions = $this->db->query("SELECT * FROM room_sessions ORDER BY id")->fetchAll();

        // Lấy toàn bộ lịch trình đặt phòng của phòng đang chọn trong khoảng thời gian tuần đó
        $roomSchedule = [];
        if ($roomId) {
            $stmt = $this->db->prepare("
                SELECT b.*, u.fullname 
                FROM bookings b 
                JOIN users u ON b.user_id = u.id 
                WHERE b.room_id = ? AND b.booking_date BETWEEN ? AND ?
                AND b.status IN ('approved', 'pending')
            ");
            $stmt->execute([$roomId, $weekDays[0]['date'], $weekDays[5]['date']]);
            $bookings = $stmt->fetchAll();
            foreach ($bookings as $b) {
                // Sắp xếp dữ liệu theo [Ngày][Ca học] để dễ dàng render ra bảng
                $roomSchedule[$b['booking_date']][$b['session_id']] = $b;
            }
        }

        // Tính năng gợi ý: Tìm các phòng còn trống trong ca học hiện tại hoặc sắp tới
        $now = date('H:i');
        $sessionId = 1;
        $sessionName = "Ca 1";
        
        // Tìm ca học đang diễn ra
        $stmtSession = $this->db->prepare("SELECT * FROM room_sessions WHERE end_time > ? ORDER BY start_time LIMIT 1");
        $stmtSession->execute([$now]);
        $currentSess = $stmtSession->fetch();
        
        if ($currentSess) {
            $sessionId = $currentSess['id'];
            $sessionName = $currentSess['session_name'];
        }

        // Truy vấn các phòng KHÔNG có trong bảng bookings tại ca học này
        $stmtFree = $this->db->prepare("
            SELECT * FROM rooms 
            WHERE status = 'available' 
            AND id NOT IN (
                SELECT room_id FROM bookings 
                WHERE date(booking_date) = date('now', 'localtime') 
                AND session_id = ? 
                AND status IN ('approved', 'pending')
            )
            LIMIT 6
        ");
        $stmtFree->execute([$sessionId]);
        $freeRooms = $stmtFree->fetchAll();

        require_once 'frontend/views/admin/lich_muon_phong.php';
    }

    /**
     * Trang Phê duyệt yêu cầu đặt phòng
     */
    public function duyet_dat_phong() {
        require_once 'frontend/views/admin/duyet_dat_phong.php';
    }

    /**
     * Trang Quản lý thông tin danh mục Phòng học
     */
    public function quan_ly_phong() {
        $rooms = $this->roomModel->getAllRooms();
        // Lấy danh sách ID các phòng đang có người sử dụng ngay lúc này
        $stmt = $this->db->prepare("SELECT room_id FROM bookings WHERE booking_date = date('now') AND status = 'approved'");
        $stmt->execute();
        $occupiedIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

        require_once 'frontend/views/admin/quan_ly_phong.php';
    }

    /**
     * Trang Quản lý danh mục Trang thiết bị
     */
    public function quan_ly_thiet_bi() {
        $equipment = $this->equipmentModel->getAllEquipment();
        $rooms = $this->roomModel->getAllRooms();
        require_once 'frontend/views/admin/quan_ly_thiet_bi.php';
    }

    /**
     * Trang Quản lý và xử lý các báo cáo hỏng hóc từ người dùng
     */
    public function quan_ly_bao_hong() {
        $stmt = $this->db->query("
            SELECT ir.*, u.fullname, r.room_number 
            FROM incident_reports ir 
            JOIN users u ON ir.user_id = u.id 
            JOIN rooms r ON ir.room_id = r.id 
            ORDER BY ir.created_at DESC
        ");
        $reports = $stmt->fetchAll();
        require_once 'frontend/views/admin/quan_ly_bao_hong.php';
    }

    /**
     * Trang Quản lý Người dùng (Sinh viên, Giảng viên)
     */
    public function quan_ly_nguoi_dung() {
        $stmtRoles = $this->db->query("SELECT * FROM roles");
        $roles = $stmtRoles->fetchAll();
        require_once 'frontend/views/admin/quan_ly_nguoi_dung.php';
    }
}
?>

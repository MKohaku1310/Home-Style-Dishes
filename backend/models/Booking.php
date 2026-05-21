<?php
/**
 * Model quản lý các nghiệp vụ liên quan đến Đặt phòng (Booking)
 */
class Booking {
    private $db;

    public function __construct() {
        // Khởi tạo kết nối CSDL
        $this->db = getDBConnection();
    }

    /**
     * Kiểm tra xem một phòng đã bị trùng lịch trong một ngày và ca cụ thể hay chưa
     * @param int $room_id - ID phòng học
     * @param string $date - Ngày cần kiểm tra (Y-m-d)
     * @param int $session_id - ID ca học (1-6)
     * @return bool - Trả về true nếu đã có người đặt (Đang chờ duyệt hoặc Đã duyệt)
     */
    public function checkConflict($room_id, $date, $session_id) {
        $stmt = $this->db->prepare("SELECT id FROM bookings WHERE room_id = ? AND date(booking_date) = date(?) AND session_id = ? AND status IN ('pending', 'approved')");
        $stmt->execute([$room_id, $date, $session_id]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Tạo một yêu cầu đặt phòng mới
     * @param array $data - Chứa user_id, room_id, session_id, booking_date, purpose
     * @return bool - Thành công hoặc thất bại
     */
    public function createBooking($data) {
        try {
            $this->db->beginTransaction();
            
            // Bước 1: Kiểm tra lại một lần nữa xem có bị trùng lịch hay không để tránh tranh chấp dữ liệu (Race condition)
            if ($this->checkConflict($data['room_id'], $data['booking_date'], $data['session_id'])) {
                $this->db->rollBack();
                return false;
            }

            // Bước 2: Thực hiện chèn dữ liệu đăng ký vào bảng bookings
            $stmt = $this->db->prepare("INSERT INTO bookings (user_id, room_id, session_id, booking_date, purpose) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $data['user_id'],
                $data['room_id'],
                $data['session_id'],
                $data['booking_date'],
                $data['purpose']
            ]);
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            // Nếu có lỗi hệ thống, hoàn tác lại toàn bộ
            $this->db->rollBack();
            return false;
        }
    }

    /**
     * Lấy danh sách toàn bộ lịch sử đăng ký của một người dùng cụ thể
     * Kết hợp với bảng rooms và room_sessions để lấy tên phòng và thời gian ca học
     */
    public function getUserBookings($user_id) {
        $stmt = $this->db->prepare("
            SELECT b.*, r.room_number, s.session_name, s.start_time, s.end_time 
            FROM bookings b 
            JOIN rooms r ON b.room_id = r.id 
            JOIN room_sessions s ON b.session_id = s.id 
            WHERE b.user_id = ? 
            ORDER BY b.booking_date DESC, s.start_time DESC
        ");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    }
}
?>

<?php
/**
 * Model quản lý các nghiệp vụ liên quan đến Phòng học
 */
class Room {
    private $db;

    public function __construct() {
        // Lấy kết nối CSDL khi khởi tạo Model
        $this->db = getDBConnection();
    }

    /**
     * Lấy toàn bộ danh sách phòng học, sắp xếp theo tầng và số phòng
     */
    public function getAllRooms() {
        $stmt = $this->db->query("SELECT * FROM rooms ORDER BY floor, room_number");
        return $stmt->fetchAll();
    }

    /**
     * Lấy danh sách phòng học có phân trang (dùng cho giao diện quản trị)
     * @param int $page - Trang hiện tại
     * @param int $limit - Số lượng phòng trên mỗi trang
     */
    public function getRoomsPaginated($page = 1, $limit = 10) {
        $offset = ($page - 1) * $limit;
        
        // Đếm tổng số phòng để tính toán phân trang
        $totalItems = $this->db->query("SELECT COUNT(*) FROM rooms")->fetchColumn();
        $totalPages = ceil($totalItems / $limit);

        // Truy vấn lấy dữ liệu theo giới hạn và vị trí bắt đầu (LIMIT, OFFSET)
        $stmt = $this->db->prepare("SELECT * FROM rooms ORDER BY floor, room_number LIMIT ? OFFSET ?");
        $stmt->execute([$limit, $offset]);
        $rooms = $stmt->fetchAll();

        return [
            'data' => $rooms,
            'pagination' => [
                'total_items' => (int)$totalItems,
                'total_pages' => (int)$totalPages,
                'current_page' => (int)$page,
                'limit' => (int)$limit
            ]
        ];
    }

    /**
     * Lấy thông tin chi tiết của một phòng cụ thể qua ID
     */
    public function getRoomById($id) {
        $stmt = $this->db->prepare("SELECT * FROM rooms WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Thêm phòng học mới vào hệ thống
     * @param array $data - Mảng chứa room_number, floor, capacity, room_type, status
     */
    public function createRoom($data) {
        $stmt = $this->db->prepare("INSERT INTO rooms (room_number, floor, capacity, room_type, status) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['room_number'],
            $data['floor'],
            $data['capacity'],
            $data['room_type'],
            $data['status'] ?? 'available'
        ]);
    }

    /**
     * Cập nhật thông tin phòng học đã tồn tại
     */
    public function updateRoom($id, $data) {
        $stmt = $this->db->prepare("UPDATE rooms SET room_number = ?, floor = ?, capacity = ?, room_type = ?, status = ? WHERE id = ?");
        return $stmt->execute([
            $data['room_number'],
            $data['floor'],
            $data['capacity'],
            $data['room_type'],
            $data['status'],
            $id
        ]);
    }

    /**
     * Xóa phòng học và tất cả dữ liệu liên quan (Booking, Báo hỏng, Thiết bị)
     * Sử dụng Transaction để đảm bảo tính toàn vẹn của dữ liệu
     */
    public function deleteRoom($id) {
        try {
            $this->db->beginTransaction();
            
            // 1. Xóa các bản ghi liên quan trong bảng bookings (Lịch mượn phòng)
            $stmt1 = $this->db->prepare("DELETE FROM bookings WHERE room_id = ?");
            $stmt1->execute([$id]);
            
            // 2. Xóa các bản ghi liên quan trong bảng incident_reports (Báo cáo sự cố)
            $stmt2 = $this->db->prepare("DELETE FROM incident_reports WHERE room_id = ?");
            $stmt2->execute([$id]);

            // 3. Xóa thiết bị thuộc về phòng này
            $stmt3 = $this->db->prepare("DELETE FROM equipment WHERE room_id = ?");
            $stmt3->execute([$id]);
            
            // 4. Cuối cùng mới xóa chính bản ghi phòng học
            $stmt4 = $this->db->prepare("DELETE FROM rooms WHERE id = ?");
            $stmt4->execute([$id]);
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            // Nếu có bất kỳ lỗi nào xảy ra, hoàn tác lại toàn bộ quá trình xóa
            $this->db->rollBack();
            return false;
        }
    }

    /**
     * Cập nhật nhanh trạng thái vận hành của phòng (Sẵn sàng / Bảo trì)
     */
    public function toggleStatus($id, $status) {
        $stmt = $this->db->prepare("UPDATE rooms SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }
}
?>

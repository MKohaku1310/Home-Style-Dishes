<?php
/**
 * Model quản lý các nghiệp vụ liên quan đến Trang thiết bị
 */
class Equipment {
    private $db;

    public function __construct() {
        // Lấy kết nối CSDL
        $this->db = getDBConnection();
    }

    /**
     * Lấy danh sách thiết bị thuộc về một phòng học cụ thể
     * @param int $room_id - ID phòng học
     */
    public function getEquipmentByRoom($room_id) {
        $stmt = $this->db->prepare("SELECT e.*, r.room_number FROM equipment e JOIN rooms r ON e.room_id = r.id WHERE e.room_id = ?");
        $stmt->execute([$room_id]);
        return $stmt->fetchAll();
    }

    /**
     * Lấy toàn bộ danh sách thiết bị trong hệ thống
     */
    public function getAllEquipment() {
        $stmt = $this->db->query("SELECT e.*, r.room_number FROM equipment e JOIN rooms r ON e.room_id = r.id ORDER BY r.room_number, e.name");
        return $stmt->fetchAll();
    }

    /**
     * Lấy danh sách thiết bị có phân trang và bộ lọc tìm kiếm
     * @param int $page - Trang hiện tại
     * @param int $limit - Số lượng bản ghi mỗi trang
     * @param string $status - Lọc theo tình trạng (good, broken, repairing, all)
     * @param mixed $room_id - Lọc theo phòng học cụ thể hoặc 'all'
     * @param string $search - Từ khóa tìm kiếm theo tên hoặc mã thiết bị
     */
    public function getEquipmentPaginated($page = 1, $limit = 10, $status = 'all', $room_id = 'all', $search = '') {
        $offset = ($page - 1) * $limit;
        
        // Xây dựng câu lệnh WHERE động dựa trên các tham số lọc
        $where = "WHERE 1=1";
        $params = [];

        if ($status !== 'all') {
            $where .= " AND e.condition_status = ?";
            $params[] = $status;
        }
        if ($room_id !== 'all') {
            $where .= " AND e.room_id = ?";
            $params[] = $room_id;
        }
        if ($search !== '') {
            $where .= " AND (e.name LIKE ? OR e.equipment_code LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }

        // Tính tổng số lượng bản ghi thỏa mãn điều kiện lọc để phân trang
        $totalStmt = $this->db->prepare("SELECT COUNT(*) FROM equipment e $where");
        $totalStmt->execute($params);
        $totalItems = $totalStmt->fetchColumn();
        $totalPages = ceil($totalItems / $limit);

        // Truy vấn lấy dữ liệu chi tiết
        $sql = "SELECT e.*, r.room_number 
                FROM equipment e 
                JOIN rooms r ON e.room_id = r.id 
                $where 
                ORDER BY r.room_number, e.name 
                LIMIT ? OFFSET ?";
        
        $params[] = $limit;
        $params[] = $offset;

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $equipment = $stmt->fetchAll();

        return [
            'data' => $equipment,
            'pagination' => [
                'total_items' => (int)$totalItems,
                'total_pages' => (int)$totalPages,
                'current_page' => (int)$page,
                'limit' => (int)$limit
            ]
        ];
    }
}
?>

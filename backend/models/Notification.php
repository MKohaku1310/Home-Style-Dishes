<?php
/**
 * Model quản lý các thông báo (Notifications) trong hệ thống
 */
class Notification {
    private $db;

    public function __construct() {
        // Khởi tạo kết nối CSDL
        $this->db = getDBConnection();
    }

    /**
     * Tạo thông báo mới cho một người dùng
     */
    public function create($user_id, $title, $content) {
        $stmt = $this->db->prepare("INSERT INTO notifications (user_id, title, content) VALUES (?, ?, ?)");
        return $stmt->execute([$user_id, $title, $content]);
    }

    /**
     * Đếm số lượng thông báo chưa đọc của người dùng
     */
    public function getUnreadCount($user_id) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0");
        $stmt->execute([$user_id]);
        return $stmt->fetchColumn();
    }

    /**
     * Lấy danh sách các thông báo mới nhất
     */
    public function getLatest($user_id, $limit = 5) {
        $stmt = $this->db->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT ?");
        $stmt->bindValue(1, $user_id, PDO::PARAM_INT);
        $stmt->bindValue(2, (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Đánh dấu thông báo là đã đọc
     * @param int $user_id - ID người dùng
     * @param int|null $id - ID thông báo cụ thể (nếu null thì đánh dấu tất cả)
     */
    public function markAsRead($user_id, $id = null) {
        if ($id) {
            // Đọc một thông báo cụ thể
            $stmt = $this->db->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ? AND id = ?");
            return $stmt->execute([$user_id, $id]);
        } else {
            // Đọc tất cả thông báo của người dùng đó
            $stmt = $this->db->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ?");
            return $stmt->execute([$user_id]);
        }
    }
}
?>

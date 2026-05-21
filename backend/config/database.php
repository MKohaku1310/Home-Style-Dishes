<?php
/**
 * Cấu hình kết nối cơ sở dữ liệu (SQLite)
 */

// Đường dẫn tuyệt đối đến file CSDL SQLite
define('DB_FILE', __DIR__ . '/../database.sqlite');

/**
 * Khởi tạo và trả về đối tượng kết nối PDO
 */
function getDBConnection() {
    try {
        $dsn = "sqlite:" . DB_FILE;
        $options = [
            // Thiết lập chế độ báo lỗi bằng Exception để dễ dàng debug
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            // Thiết lập kiểu trả về mặc định là mảng kết hợp (Associative Array)
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            // Tắt chế độ giả lập Prepare Statement để tăng tính bảo mật và hiệu năng
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        
        $pdo = new PDO($dsn, null, null, $options);
        
        // Bật hỗ trợ Ràng buộc khóa ngoại (Foreign Keys) cho SQLite - Mặc định SQLite thường tắt tính năng này
        $pdo->exec('PRAGMA foreign_keys = ON;');
        
        return $pdo;
    } catch (PDOException $e) {
        // Nếu có lỗi kết nối, dừng chương trình và thông báo lỗi
        die("Kết nối CSDL thất bại: " . $e->getMessage());
    }
}
?>

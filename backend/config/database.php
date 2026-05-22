<?php
/**
 * Cấu hình kết nối cơ sở dữ liệu (SQLite & MySQL)
 */

// Lựa chọn Driver: 'sqlite' hoặc 'mysql'
define('DB_DRIVER', 'sqlite'); // Thay đổi thành 'mysql' nếu muốn sử dụng MySQL/phpMyAdmin

// --- Cấu hình cho SQLite ---
define('DB_FILE', __DIR__ . '/../database.sqlite');

// --- Cấu hình cho MySQL ---
define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_NAME', 'ql_phong_hoc'); // Tên CSDL trên phpMyAdmin
define('DB_USER', 'root');         // Username mặc định của XAMPP/Laragon
define('DB_PASS', '');             // Mật khẩu mặc định của XAMPP/Laragon

/**
 * Khởi tạo và trả về đối tượng kết nối PDO
 */
function getDBConnection() {
    try {
        $options = [
            // Thiết lập chế độ báo lỗi bằng Exception để dễ dàng debug
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            // Thiết lập kiểu trả về mặc định là mảng kết hợp (Associative Array)
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            // Tắt chế độ giả lập Prepare Statement để tăng tính bảo mật và hiệu năng
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        if (DB_DRIVER === 'mysql') {
            $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } else {
            $dsn = "sqlite:" . DB_FILE;
            $pdo = new PDO($dsn, null, null, $options);
            // Bật hỗ trợ Ràng buộc khóa ngoại (Foreign Keys) cho SQLite - Mặc định SQLite thường tắt tính năng này
            $pdo->exec('PRAGMA foreign_keys = ON;');
        }
        
        return $pdo;
    } catch (PDOException $e) {
        // Nếu có lỗi kết nối, dừng chương trình và thông báo lỗi
        die("Kết nối CSDL thất bại: " . $e->getMessage());
    }
}
?>

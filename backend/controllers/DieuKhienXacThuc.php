<?php
/**
 * Controller xử lý các nghiệp vụ liên quan đến Xác thực (Đăng nhập, Đăng ký, Đăng xuất)
 */
class DieuKhienXacThuc {
    private $db;

    public function __construct() {
        // Khởi tạo kết nối CSDL
        $this->db = getDBConnection();
    }

    /**
     * Xử lý Đăng nhập người dùng
     */
    public function dang_nhap() {
        // Nếu người dùng đã đăng nhập (có session) thì tự động chuyển hướng về trang chủ/admin
        if (isset($_SESSION['user_id'])) {
            $this->chuyen_huong_theo_vai_tro();
        }

        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            if (empty($email) || empty($password)) {
                $error = 'Vui lòng nhập đầy đủ thông tin.';
            } else {
                // Kiểm tra tài khoản bằng Email hoặc Username
                $stmt = $this->db->prepare("SELECT u.*, r.role_name FROM users u JOIN roles r ON u.role_id = r.id WHERE u.email = ? OR u.username = ? LIMIT 1");
                $stmt->execute([$email, $email]); 
                $user = $stmt->fetch();

                // Xác thực mật khẩu đã được mã hóa (Bcrypt)
                if ($user && password_verify($password, $user['password'])) {
                    if ($user['status'] === 'locked') {
                        $error = 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ Admin.';
                    } else {
                        // Thiết lập các thông tin cơ bản vào Session để sử dụng toàn hệ thống
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['username'] = $user['username'];
                        $_SESSION['fullname'] = $user['fullname'];
                        $_SESSION['role_name'] = $user['role_name'];
                        
                        // Đăng nhập thành công -> Chuyển hướng
                        $this->chuyen_huong_theo_vai_tro();
                    }
                } else {
                    $error = 'Email hoặc mật khẩu không chính xác.';
                }
            }
        }

        // Load giao diện trang đăng nhập
        require_once 'frontend/views/auth/login.php';
    }

    /**
     * Xử lý Đăng ký tài khoản mới
     */
    public function dang_ky() {
        if (isset($_SESSION['user_id'])) {
            $this->chuyen_huong_theo_vai_tro();
        }

        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $fullname = $_POST['fullname'] ?? '';
            $password = $_POST['password'] ?? '';
            $password_confirm = $_POST['password_confirm'] ?? '';
            $role_name = $_POST['role'] ?? 'student';

            // Kiểm tra dữ liệu đầu vào cơ bản
            if (empty($username) || empty($email) || empty($password) || empty($fullname)) {
                $error = 'Vui lòng nhập đầy đủ thông tin bắt buộc.';
            } elseif ($password !== $password_confirm) {
                $error = 'Mật khẩu xác nhận không khớp.';
            } elseif (strlen($password) < 8) {
                $error = 'Mật khẩu phải tối thiểu 8 ký tự.';
            } else {
                // Kiểm tra xem Username hoặc Email đã tồn tại chưa
                $stmtCheck = $this->db->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
                $stmtCheck->execute([$username, $email]);
                if ($stmtCheck->rowCount() > 0) {
                    $error = 'Mã sinh viên/Giảng viên (Username) hoặc Email đã tồn tại.';
                } else {
                    // Lấy role_id tương ứng từ bảng roles
                    $stmtRole = $this->db->prepare("SELECT id FROM roles WHERE role_name = ?");
                    $stmtRole->execute([$role_name === 'lecturer' ? 'lecturer' : 'student']);
                    $roleId = $stmtRole->fetchColumn();

                    // Mã hóa mật khẩu trước khi lưu vào CSDL (Bắt buộc để bảo mật)
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                    $stmtInsert = $this->db->prepare("INSERT INTO users (username, password, fullname, email, role_id) VALUES (?, ?, ?, ?, ?)");
                    if ($stmtInsert->execute([$username, $hashed_password, $fullname, $email, $roleId])) {
                        $success = 'Đăng ký thành công. Vui lòng đăng nhập!';
                    } else {
                        $error = 'Có lỗi xảy ra, vui lòng thử lại.';
                    }
                }
            }
        }

        // Load giao diện trang đăng ký
        require_once 'frontend/views/auth/register.php';
    }

    /**
     * Hủy session và đăng xuất người dùng
     */
    public function dang_xuat() {
        session_destroy();
        header("Location: index.php?page=xac_thuc&action=dang_nhap");
        exit;
    }

    /**
     * Phương thức nội bộ để điều hướng người dùng dựa trên quyền hạn sau khi đăng nhập
     */
    private function chuyen_huong_theo_vai_tro() {
        if ($_SESSION['role_name'] === 'admin') {
            // Nếu là Admin thì đưa vào Bảng điều khiển quản trị
            header("Location: index.php?page=quan_tri&action=bang_dieu_khien");
        } else {
            // Nếu là Sinh viên/Giảng viên thì đưa vào Trang chủ
            header("Location: index.php?page=trang_chu&action=index");
        }
        exit;
    }
}
?>

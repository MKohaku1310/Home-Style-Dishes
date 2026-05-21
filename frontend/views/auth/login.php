<?php require_once 'frontend/includes/header.php'; ?>

<style>
    .auth-container {
        min-height: calc(100vh - var(--navbar-height) - 100px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 0;
    }
    .auth-card {
        background: var(--white);
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        overflow: hidden;
        display: flex;
        width: 100%;
        max-width: 1000px;
    }
    .auth-image-side {
        width: 50%;
        background: linear-gradient(145deg, #f02d24, #9a1a14);
        color: var(--white);
        padding: 4rem 3rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
        position: relative;
        box-shadow: inset -10px 0 20px rgba(0,0,0,0.1);
    }
    .auth-image-side::before {
        content: '';
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        background: url('https://info.nhonam.io.vn/images/Logo-PTIT@2x.png') no-repeat center;
        background-size: 300px;
        opacity: 0.08;
        filter: grayscale(1) brightness(2);
    }
    .auth-form-side {
        width: 50%;
        padding: 3rem;
        background: var(--white);
    }
    .password-toggle {
        cursor: pointer;
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        z-index: 10;
    }
    @media (max-width: 768px) {
        .auth-card { flex-direction: column; }
        .auth-image-side, .auth-form-side { width: 100%; padding: 2rem; }
        .auth-image-side { display: none; }
    }
</style>

<div class="auth-container">
    <div class="auth-card">
        <!-- Left Side: Branding -->
        <div class="auth-image-side">
            <h2 class="display-6 fw-bold mb-4 text-white">PTIT A3 <br>Room Booking</h2>
            <p class="mb-4 text-white opacity-75">Hệ thống quản lý và đăng ký phòng thực hành Tòa nhà A3 - Học viện Công nghệ Bưu chính Viễn thông.</p>
            <ul class="list-unstyled text-white">
                <li class="mb-2"><i class="fa-solid fa-circle-check me-2 text-warning"></i> Tra cứu phòng học thời gian thực</li>
                <li class="mb-2"><i class="fa-solid fa-circle-check me-2 text-warning"></i> Đăng ký mượn phòng trực tuyến</li>
                <li class="mb-2"><i class="fa-solid fa-circle-check me-2 text-warning"></i> Báo cáo sự cố thiết bị nhanh chóng</li>
            </ul>
        </div>

        <!-- Right Side: Form -->
        <div class="auth-form-side d-flex flex-column justify-content-center">
            <div class="text-center mb-4">
                <img src="https://info.nhonam.io.vn/images/Logo-PTIT@2x.png" alt="PTIT Logo" style="height: 100px;" class="mb-3">
                <h4 class="fw-bold text-ptit-red">ĐĂNG NHẬP HỆ THỐNG</h4>
                <p class="text-muted small">Phòng thực hành Tòa nhà A3</p>
            </div>

            <form id="loginForm" novalidate>
                <div class="form-floating mb-3">
                    <input type="text" name="email" class="form-control" id="emailInput" placeholder="name@example.com" required>
                    <label for="emailInput">Username / Email</label>
                    <div class="invalid-feedback">Vui lòng nhập tên tài khoản hợp lệ.</div>
                </div>

                <div class="form-floating mb-4 position-relative">
                    <input type="password" name="password" class="form-control" id="passwordInput" placeholder="Password" required>
                    <label for="passwordInput">Mật khẩu</label>
                    <i class="fa-solid fa-eye password-toggle" id="togglePassword"></i>
                    <div class="invalid-feedback">Mật khẩu không được để trống.</div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="rememberMe">
                        <label class="form-check-label small" for="rememberMe">Ghi nhớ</label>
                    </div>
                    <a href="#" class="small text-ptit-red fw-bold text-decoration-none">Quên mật khẩu?</a>
                </div>

                <button type="submit" class="btn btn-ptit w-100 py-3 mb-4 d-flex align-items-center justify-content-center" id="loginBtn">
                    <span class="btn-text">Đăng Nhập</span>
                    <div class="spinner-border spinner-border-sm ms-2 d-none" role="status"></div>
                </button>

                <div class="text-center">
                    <span class="text-muted small">Chưa có tài khoản?</span>
                    <a href="index.php?page=xac_thuc&action=dang_ky" class="fw-bold text-ptit-red small ms-1 text-decoration-none">Đăng ký ngay</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Ẩn/Hiện mật khẩu khi nhấn vào icon con mắt
    $('#togglePassword').on('click', function() {
        const type = $('#passwordInput').attr('type') === 'password' ? 'text' : 'password';
        $('#passwordInput').attr('type', type);
        $(this).toggleClass('fa-eye fa-eye-slash');
    });

    // Xử lý gửi form đăng nhập bằng AJAX để không làm load lại trang
    $('#loginForm').on('submit', function(e) {
        e.preventDefault(); // Ngăn chặn trình duyệt gửi form theo cách truyền thống
        
        const $form = $(this);
        const $btn = $('#loginBtn');
        const $spinner = $btn.find('.spinner-border');
        
        // Kiểm tra tính hợp lệ của dữ liệu đầu vào (Bootstrap validation)
        if (!$form[0].checkValidity()) {
            $form.addClass('was-validated');
            return;
        }

        // Hiển thị trạng thái đang tải trên nút bấm
        $btn.addClass('disabled').find('.btn-text').text('Đang xử lý...');
        $spinner.removeClass('d-none');
        
        $.ajax({
            url: 'index.php?page=xac_thuc&action=dang_nhap',
            type: 'POST',
            data: $form.serialize(), // Gom tất cả dữ liệu trong form gửi đi
            success: function(response) {
                // Kiểm tra kết quả trả về từ server
                if (response.indexOf('Dashboard') !== -1 || response.indexOf('Trang chủ') !== -1) {
                    showToast('Đăng nhập thành công!', 'success');
                    setTimeout(() => window.location.href = 'index.php?page=trang_chu', 500);
                } else if (response.indexOf('alert-danger') !== -1) {
                    showToast('Sai tên đăng nhập hoặc mật khẩu!', 'error');
                } else {
                    // Fallback nếu server trả về JSON
                    try {
                        const res = typeof response === 'string' ? JSON.parse(response) : response;
                        if (res.status === 'success') {
                            showToast('Đăng nhập thành công!', 'success');
                            window.location.href = 'index.php?page=trang_chu';
                        } else {
                            showToast(res.message || 'Lỗi đăng nhập!', 'error');
                        }
                    } catch(e) {
                        // Nếu không parse được JSON nhưng không có lỗi rõ ràng thì vẫn thử chuyển trang
                        window.location.href = 'index.php?page=trang_chu';
                    }
                }
            },
            error: function() {
                showToast('Không thể kết nối máy chủ!', 'error');
            },
            complete: function() {
                // Khôi phục trạng thái nút bấm sau khi hoàn tất (bất kể thành công hay thất bại)
                $btn.removeClass('disabled').find('.btn-text').text('Đăng Nhập');
                $spinner.addClass('d-none');
            }
        });
    });
});
</script>

<?php require_once 'frontend/includes/footer.php'; ?>

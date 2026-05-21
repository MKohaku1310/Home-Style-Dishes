<?php require_once 'frontend/includes/header.php'; ?>

<style>
    .register-container {
        min-height: calc(100vh - var(--navbar-height) - 100px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 3rem 0;
    }
    .register-card {
        background: var(--white);
        border-radius: 20px;
        box-shadow: 0 15px 45px rgba(0,0,0,0.1);
        width: 100%;
        max-width: 800px;
        overflow: hidden;
    }
    .register-header {
        background: linear-gradient(135deg, var(--ptit-red), var(--ptit-dark));
        color: white;
        padding: 2.5rem;
        text-align: center;
    }
    .register-body {
        padding: 3rem;
    }
    .pwd-strength {
        height: 5px;
        border-radius: 5px;
        background-color: #eee;
        margin-top: 5px;
        transition: var(--transition);
        width: 0%;
    }
    .pwd-strength.weak { background-color: #dc3545; width: 33%; }
    .pwd-strength.medium { background-color: #ffc107; width: 66%; }
    .pwd-strength.strong { background-color: #28a745; width: 100%; }
</style>

<div class="register-container">
    <div class="register-card">
        <div class="register-header" style="background: var(--ptit-red);">
            <img src="https://info.nhonam.io.vn/images/Logo-PTIT@2x.png" alt="PTIT Logo" style="height: 70px;" class="mb-3">
            <h3 class="fw-bold mb-2">ĐĂNG KÝ THÀNH VIÊN</h3>
            <p class="mb-0 opacity-75 small text-uppercase" style="letter-spacing: 1px;">Hệ thống Quản lý Phòng thực hành Tòa nhà A3</p>
        </div>
        
        <div class="register-body">
            <form id="registerForm" novalidate>
                <div class="row g-3">
                    <!-- Username -->
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="text" name="username" class="form-control" id="usernameInput" placeholder="B20DCCN001" required>
                            <label for="usernameInput">Mã SV / Mã GV (Username)</label>
                            <div class="invalid-feedback" id="usernameFeedback">Vui lòng nhập mã SV/GV hợp lệ.</div>
                        </div>
                    </div>
                    <!-- Fullname -->
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="text" name="fullname" class="form-control" id="fullnameInput" placeholder="Nguyễn Văn A" required>
                            <label for="fullnameInput">Họ và Tên</label>
                            <div class="invalid-feedback">Họ tên không được để trống.</div>
                        </div>
                    </div>
                    <!-- Email -->
                    <div class="col-12">
                        <div class="form-floating mb-3">
                            <input type="email" name="email" class="form-control" id="emailInput" placeholder="email@student.ptit.edu.vn" required>
                            <label for="emailInput">Email Học viện / Cá nhân</label>
                            <div class="invalid-feedback" id="emailFeedback">Địa chỉ email không hợp lệ hoặc đã tồn tại.</div>
                        </div>
                    </div>
                    <!-- Role -->
                    <div class="col-12">
                        <div class="form-floating mb-3">
                            <select name="role" class="form-select" id="roleSelect" required>
                                <option value="student">Sinh viên</option>
                                <option value="lecturer">Giảng viên / Cán bộ</option>
                            </select>
                            <label for="roleSelect">Vai trò của bạn</label>
                        </div>
                    </div>
                    <!-- Password -->
                    <div class="col-md-6">
                        <div class="form-floating position-relative">
                            <input type="password" name="password" class="form-control" id="pwdInput" placeholder="Password" required minlength="8">
                            <label for="pwdInput">Mật khẩu (>= 8 ký tự)</label>
                            <div class="pwd-strength" id="pwdStrength"></div>
                        </div>
                    </div>
                    <!-- Confirm Password -->
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="password" name="password_confirm" class="form-control" id="pwdConfirmInput" placeholder="Confirm" required>
                            <label for="pwdConfirmInput">Xác nhận mật khẩu</label>
                            <div class="invalid-feedback">Mật khẩu xác nhận không khớp.</div>
                        </div>
                    </div>

                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-ptit w-100 py-3 mb-3 d-flex align-items-center justify-content-center" id="registerBtn">
                            <span class="btn-text">Tạo Tài Khoản Ngay</span>
                            <div class="spinner-border spinner-border-sm ms-2 d-none" role="status"></div>
                        </button>
                    </div>

                    <div class="col-12 text-center">
                        <span class="text-muted small">Đã có tài khoản?</span>
                        <a href="index.php?page=xac_thuc&action=dang_nhap" class="fw-bold text-ptit-red small ms-1">Đăng nhập ngay</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Kiểm tra email đã tồn tại hay chưa ngay khi người dùng rời khỏi ô nhập (blur)
    $('#emailInput').on('blur', function() {
        const email = $(this).val();
        if (email.length < 5) return;
        
        const $input = $(this);
        $.get('backend/api/kiem_tra_nguoi_dung.php?field=email&value=' + email, function(res) {
            if (res.exists) {
                $input.addClass('is-invalid');
                $('#emailFeedback').text('Email này đã được sử dụng trên hệ thống.');
            } else {
                $input.removeClass('is-invalid');
            }
        });
    });

    // Hiển thị thanh độ mạnh yếu của mật khẩu dựa trên độ dài
    $('#pwdInput').on('input', function() {
        const val = $(this).val();
        const $bar = $('#pwdStrength');
        $bar.removeClass('weak medium strong');
        
        if (val.length === 0) return;
        if (val.length < 6) $bar.addClass('weak');
        else if (val.length < 10) $bar.addClass('medium');
        else $bar.addClass('strong');
    });

    // Kiểm tra xem mật khẩu nhập lại có khớp với mật khẩu ban đầu không
    $('#pwdConfirmInput, #pwdInput').on('input', function() {
        if ($('#pwdConfirmInput').val() !== $('#pwdInput').val()) {
            $('#pwdConfirmInput').addClass('is-invalid');
        } else {
            $('#pwdConfirmInput').removeClass('is-invalid');
        }
    });

    // Xử lý gửi form đăng ký bằng AJAX
    $('#registerForm').on('submit', function(e) {
        e.preventDefault();
        const $form = $(this);
        const $btn = $('#registerBtn');
        const $spinner = $btn.find('.spinner-border');

        // Kiểm tra tính hợp lệ của form trước khi gửi
        if (!$form[0].checkValidity() || $('#pwdConfirmInput').hasClass('is-invalid')) {
            $form.addClass('was-validated');
            return;
        }

        $btn.addClass('disabled').find('.btn-text').text('Đang tạo tài khoản...');
        $spinner.removeClass('d-none');

        $.ajax({
            url: 'index.php?page=xac_thuc&action=dang_ky',
            type: 'POST',
            data: $form.serialize(),
            success: function(response) {
                // Xử lý kết quả trả về từ server (có thể là HTML hoặc JSON)
                if (response.indexOf('alert-success') !== -1 || response.indexOf('thành công') !== -1) {
                    showToast('Đăng ký thành công! Đang chuyển hướng...', 'success');
                    setTimeout(() => window.location.href = 'index.php?page=xac_thuc&action=dang_nhap', 1500);
                } else if (response.indexOf('alert-danger') !== -1) {
                    showToast('Đăng ký thất bại. Vui lòng kiểm tra lại thông tin!', 'error');
                } else {
                    try {
                        const res = typeof response === 'string' ? JSON.parse(response) : response;
                        if (res.status === 'success') {
                            showToast('Đăng ký thành công!', 'success');
                            window.location.href = 'index.php?page=xac_thuc&action=dang_nhap';
                        } else {
                            showToast(res.message || 'Lỗi đăng ký!', 'error');
                        }
                    } catch(e) {
                        window.location.href = 'index.php?page=xac_thuc&action=dang_nhap';
                    }
                }
            },
            error: function() {
                showToast('Lỗi kết nối máy chủ!', 'error');
            },
            complete: function() {
                $btn.removeClass('disabled').find('.btn-text').text('Tạo Tài Khoản Ngay');
                $spinner.addClass('d-none');
            }
        });
    });
});
</script>

<?php require_once 'frontend/includes/footer.php'; ?>

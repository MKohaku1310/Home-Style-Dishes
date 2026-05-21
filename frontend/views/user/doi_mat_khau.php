<?php require_once 'frontend/includes/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="ptit-card p-4 p-md-5">
                <div class="text-center mb-5">
                    <div class="bg-primary-soft text-primary rounded-circle d-inline-flex p-3 mb-3">
                        <i class="fa-solid fa-shield-halved fs-3"></i>
                    </div>
                    <h2 class="fw-bold">Đổi mật khẩu</h2>
                    <p class="text-muted">Bảo mật tài khoản của bạn bằng mật khẩu mạnh</p>
                </div>

                <form id="changePasswordForm">
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-uppercase">Mật khẩu hiện tại</label>
                        <input type="password" name="current_password" id="current_password" class="form-control form-control-lg border-0 shadow-sm bg-light" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-uppercase">Mật khẩu mới</label>
                        <input type="password" name="new_password" id="new_password" class="form-control form-control-lg border-0 shadow-sm bg-light" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-uppercase">Xác nhận mật khẩu mới</label>
                        <input type="password" name="confirm_password" id="confirm_password" class="form-control form-control-lg border-0 shadow-sm bg-light" required>
                    </div>

                    <div class="mt-5">
                        <button type="submit" class="btn btn-ptit btn-lg w-100 shadow-sm py-3 fw-bold">
                            <i class="fa-solid fa-key me-2"></i>Cập nhật mật khẩu
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Xử lý sự kiện gửi form đổi mật khẩu
        $('#changePasswordForm').on('submit', function(e) {
            e.preventDefault();
            
            // Kiểm tra xem mật khẩu mới và mật khẩu xác nhận có khớp nhau không
            if ($('#new_password').val() !== $('#confirm_password').val()) {
                Swal.fire('Lỗi!', 'Mật khẩu xác nhận không khớp.', 'error');
                return;
            }

            const btn = $(this).find('button[type="submit"]');
            // Hiển thị trạng thái đang tải trên nút bấm
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span> Đang xử lý...');

            // Gửi dữ liệu mật khẩu mới lên server qua API
            $.post('backend/api/xu_ly_ho_so.php?action=change_password', $(this).serialize(), function(res) {
                if (res.status === 'success') {
                    // Thông báo thành công và chuyển về trang hồ sơ
                    Swal.fire({
                        icon: 'success',
                        title: 'Thành công!',
                        text: res.message,
                        confirmButtonColor: '#8B0000'
                    }).then(() => window.location.href = 'index.php?page=user&action=profile');
                } else {
                    // Thông báo lỗi nếu mật khẩu cũ sai hoặc có vấn đề khác
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi!',
                        text: res.message
                    });
                    // Khôi phục lại trạng thái nút bấm
                    btn.prop('disabled', false).html('<i class="fa-solid fa-key me-2"></i>Cập nhật mật khẩu');
                }
            });
        });
    });
</script>

<?php require_once 'frontend/includes/footer.php'; ?>

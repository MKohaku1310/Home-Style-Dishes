<?php require_once 'frontend/includes/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="ptit-card p-4 p-md-5">
                <div class="d-flex align-items-center gap-4 mb-5">
                    <div class="user-avatar-circle" style="width: 80px; height: 80px; font-size: 2rem;">
                        <?= strtoupper(substr($user['fullname'] ?? 'U', 0, 1)) ?>
                    </div>
                    <div>
                        <h2 class="fw-bold mb-1"><?= htmlspecialchars($user['fullname']) ?></h2>
                        <span class="badge bg-ptit-red-soft text-ptit-red px-3 py-2"><?= htmlspecialchars($user['role_name'] === 'admin' ? 'Quản trị viên' : 'Người dùng hệ thống') ?></span>
                    </div>
                </div>

                <form id="profileForm">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-uppercase">Tên đăng nhập (MSSV/MSGV)</label>
                            <input type="text" class="form-control form-control-lg bg-light border-0" value="<?= htmlspecialchars($user['username']) ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-uppercase">Họ và tên</label>
                            <input type="text" name="fullname" id="fullname" class="form-control form-control-lg border-0 shadow-sm bg-light" value="<?= htmlspecialchars($user['fullname']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-uppercase">Email</label>
                            <input type="email" name="email" id="email" class="form-control form-control-lg border-0 shadow-sm bg-light" value="<?= htmlspecialchars($user['email'] ?? '') ?>" placeholder="chưa cập nhật">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-uppercase">Số điện thoại</label>
                            <input type="text" name="phone" id="phone" class="form-control form-control-lg border-0 shadow-sm bg-light" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" placeholder="chưa cập nhật">
                        </div>
                        
                        <div class="col-12 mt-5">
                            <button type="submit" class="btn btn-ptit btn-lg px-5 shadow-sm fw-bold">
                                <i class="fa-solid fa-floppy-disk me-2"></i>Lưu thay đổi
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Xử lý sự kiện gửi form cập nhật thông tin cá nhân
        $('#profileForm').on('submit', function(e) {
            e.preventDefault();
            const btn = $(this).find('button[type="submit"]');
            // Hiển thị trạng thái đang lưu
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span> Đang lưu...');

            // Gửi dữ liệu mới (Họ tên, Email, SĐT) lên server
            $.post('backend/api/xu_ly_ho_so.php?action=update_profile', $(this).serialize(), function(res) {
                if (res.status === 'success') {
                    // Thông báo thành công và tải lại trang để cập nhật giao diện
                    Swal.fire({
                        icon: 'success',
                        title: 'Thành công!',
                        text: res.message,
                        confirmButtonColor: '#8B0000'
                    }).then(() => location.reload());
                } else {
                    // Thông báo lỗi nếu có
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi!',
                        text: res.message
                    });
                    // Khôi phục nút bấm
                    btn.prop('disabled', false).html('<i class="fa-solid fa-floppy-disk me-2"></i>Lưu thay đổi');
                }
            });
        });
    });
</script>

<?php require_once 'frontend/includes/footer.php'; ?>

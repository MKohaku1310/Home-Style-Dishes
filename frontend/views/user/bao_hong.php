<?php require_once 'frontend/includes/header.php'; ?>

<div class="container-fluid py-4" style="max-width: 800px; margin: 0 auto;">
    <div class="text-center mb-5">
        <div class="bg-ptit-red-soft rounded-circle d-inline-flex p-3 mb-3">
            <i class="fa-solid fa-screwdriver-wrench text-ptit-red fs-2"></i>
        </div>
        <h3 class="fw-bold">Báo hỏng thiết bị</h3>
        <p class="text-muted">Gửi thông tin về hư hỏng hoặc sự cố kỹ thuật tại phòng thực hành</p>
    </div>

    <div class="ptit-card p-4 p-md-5">
        <form id="incidentForm">
            <div class="mb-4">
                <label class="form-label fw-bold small">Phòng học / Phòng máy</label>
                <select name="room_id" id="room_id" class="form-select form-select-lg" required>
                    <option value="">-- Chọn phòng xảy ra sự cố --</option>
                    <!-- AJAX Load -->
                </select>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold small">Mô tả chi tiết sự cố</label>
                <textarea name="description" class="form-control" rows="6" required 
                    placeholder="Vui lòng mô tả cụ thể: Thiết bị hỏng là gì (máy tính, điều hòa, đèn...), tình trạng hư hỏng như thế nào..."></textarea>
            </div>

            <div class="alert alert-warning border-0 small d-flex align-items-center mb-5">
                <i class="fa-solid fa-circle-info me-3 fs-4"></i>
                <div>Thông tin của bạn sẽ được gửi tới bộ phận kỹ thuật để kiểm tra và khắc phục sớm nhất.</div>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-ptit py-3 fw-bold" id="btn-submit">
                    <i class="fa-solid fa-paper-plane me-2"></i> Gửi báo cáo sự cố
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    /**
     * Tải danh sách phòng từ server để người dùng chọn phòng gặp sự cố
     */
    function tai_danh_sach_phong() {
        $.get('backend/api/tim_phong.php', function(res) {
            if (res.status === 'success') {
                let html = '<option value="">-- Chọn phòng xảy ra sự cố --</option>';
                res.data.forEach(room => {
                    html += `<option value="${room.id}">${room.room_number} - ${room.room_type}</option>`;
                });
                $('#room_id').html(html);
            }
        });
    }

    $(document).ready(function() {
        // Khởi tạo danh sách phòng ngay khi trang load xong
        tai_danh_sach_phong();

        // Xử lý sự kiện gửi form báo cáo sự cố
        $('#incidentForm').on('submit', function(e) {
            e.preventDefault();
            const $btn = $('#btn-submit');
            // Hiển thị trạng thái đang xử lý trên nút gửi
            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span> Đang gửi...');

            $.ajax({
                url: 'backend/api/xu_ly_bao_hong.php?action=create',
                type: 'POST',
                data: $(this).serialize(),
                success: function(res) {
                    try {
                        // Xử lý dữ liệu trả về từ API (thành công hoặc thất bại)
                        const data = typeof res === 'string' ? JSON.parse(res) : res;
                        if (data.status === 'success') {
                            showToast('Đã gửi báo cáo thành công!', 'success');
                            $('#incidentForm')[0].reset(); // Xóa trắng form sau khi gửi thành công
                        } else {
                            showToast(data.message || 'Có lỗi xảy ra!', 'error');
                        }
                    } catch(e) {
                        // Fallback nếu có lỗi phân tách dữ liệu JSON nhưng vẫn gửi được
                        showToast('Đã gửi báo cáo sự cố!', 'success');
                        $('#incidentForm')[0].reset();
                    }
                    // Khôi phục trạng thái nút bấm
                    $btn.prop('disabled', false).html('<i class="fa-solid fa-paper-plane me-2"></i> Gửi báo cáo sự cố');
                },
                error: function() {
                    showToast('Lỗi máy chủ!', 'error');
                    $btn.prop('disabled', false).html('<i class="fa-solid fa-paper-plane me-2"></i> Gửi báo cáo sự cố');
                }
            });
        });
    });
</script>

<?php require_once 'frontend/includes/footer.php'; ?>

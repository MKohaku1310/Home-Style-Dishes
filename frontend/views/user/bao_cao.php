<?php require_once 'frontend/includes/header.php'; ?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="ptit-card p-4 border-top border-5 border-warning">
                <div class="d-flex align-items-center mb-4">
                    <div class="card-icon-box me-3" style="background: #fff8e1; color: #f57f17;">
                        <i class="fa-solid fa-screwdriver-wrench"></i>
                    </div>
                    <div>
                        <h2 class="fw-bold mb-0">Báo hỏng thiết bị</h2>
                        <p class="text-muted small mb-0">Giúp chúng tôi cải thiện môi trường học tập bằng cách thông báo sự cố.</p>
                    </div>
                </div>

                <form id="reportForm">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Phòng học <span class="text-danger">*</span></label>
                            <select name="room_id" id="room_id" class="form-select" required>
                                <option value="">Đang tải danh sách phòng...</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Thiết bị gặp sự cố <span class="text-danger">*</span></label>
                            <input type="text" name="equipment_name" class="form-control" placeholder="Ví dụ: Máy chiếu, Điều hòa, Bàn ghế..." required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold small">Mức độ ưu tiên</label>
                            <div class="d-flex gap-3 mt-1">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="urgency" id="urg1" value="low">
                                    <label class="form-check-label small" for="urg1">Thấp (Chưa gấp)</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="urgency" id="urg2" value="medium" checked>
                                    <label class="form-check-label small" for="urg2">Trung bình</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="urgency" id="urg3" value="high">
                                    <label class="form-check-label small" for="urg3">Cao (Cần sửa ngay)</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold small">Mô tả chi tiết sự cố <span class="text-danger">*</span></label>
                            <textarea name="description" class="form-control" rows="4" placeholder="Vui lòng mô tả cụ thể tình trạng hỏng hóc..." required></textarea>
                        </div>
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-ptit w-100 py-3 shadow-sm">
                                <i class="fa-solid fa-paper-plane me-2"></i>Gửi báo cáo sự cố
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="mt-4 ptit-card p-3 bg-light">
                <div class="d-flex align-items-center text-muted small">
                    <i class="fa-solid fa-circle-info me-2"></i>
                    Báo cáo của bạn sẽ được gửi trực tiếp đến bộ phận kỹ thuật để xử lý.
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        /**
         * Tải danh sách tất cả các phòng học để hiển thị trong ô chọn (Select Box)
         */
        $.get('backend/api/bao_cao_thiet_bi.php?action=list_rooms', function(res) {
            if (res.status === 'success') {
                let html = '<option value="">-- Chọn phòng --</option>';
                res.data.forEach(room => {
                    html += `<option value="${room.id}">${room.room_number}</option>`;
                });
                $('#room_id').html(html);
            }
        });

        /**
         * Xử lý gửi form báo hỏng thiết bị
         */
        $('#reportForm').on('submit', function(e) {
            e.preventDefault();
            const formData = $(this).serialize();
            
            // Vô hiệu hóa nút bấm để tránh người dùng nhấn nhiều lần
            $(this).find('button').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Đang gửi...');

            $.post('backend/api/bao_cao_thiet_bi.php?action=submit', formData, function(res) {
                if (res.status === 'success') {
                    showToast(res.message); // Thông báo thành công
                    // Chuyển hướng về trang chủ sau 2 giây
                    setTimeout(() => {
                        window.location.href = 'index.php?page=home';
                    }, 2000);
                } else {
                    showToast(res.message, 'error'); // Thông báo lỗi nếu có
                    // Khôi phục lại nút bấm nếu gửi thất bại
                    $('#reportForm button').prop('disabled', false).html('<i class="fa-solid fa-paper-plane me-2"></i>Gửi báo cáo sự cố');
                }
            });
        });
    });
</script>

<?php require_once 'frontend/includes/footer.php'; ?>

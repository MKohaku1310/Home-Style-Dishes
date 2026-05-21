<?php require_once 'frontend/includes/header.php'; ?>

<style>
    .booking-wizard {
        max-width: 900px;
        margin: 0 auto;
    }
    .step-indicator {
        display: flex;
        justify-content: space-between;
        margin-bottom: 3rem;
        position: relative;
    }
    .step-indicator::before {
        content: '';
        position: absolute;
        top: 20px; left: 0; width: 100%; height: 2px;
        background: #e9ecef;
        z-index: 1;
    }
    .step-item {
        position: relative;
        z-index: 2;
        text-align: center;
        width: 33.33%;
    }
    .step-circle {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: var(--white);
        border: 2px solid #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
        font-weight: bold;
        color: #adb5bd;
        transition: var(--transition);
    }
    .step-item.active .step-circle {
        background: var(--ptit-red);
        border-color: var(--ptit-red);
        color: var(--white);
        box-shadow: 0 0 0 5px rgba(139, 0, 0, 0.1);
    }
    .step-item.completed .step-circle {
        background: #28a745;
        border-color: #28a745;
        color: var(--white);
    }
    .step-title {
        font-size: 0.8rem;
        font-weight: 600;
        color: #adb5bd;
    }
    .step-item.active .step-title { color: var(--ptit-red); }
    
    .room-card-selection {
        cursor: pointer;
        transition: var(--transition);
        border: 2px solid transparent;
    }
    .room-card-selection:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-md);
    }
    .room-card-selection.selected {
        border-color: var(--ptit-red);
        background-color: var(--ptit-red-soft);
    }
</style>

<div class="booking-wizard py-4">
    <div class="text-center mb-5">
        <h3 class="fw-bold">Đăng ký mượn phòng học</h3>
        <p class="text-muted">Hoàn thành các bước dưới đây để gửi yêu cầu mượn phòng Tòa A3</p>
    </div>

    <!-- Step Progress -->
    <div class="step-indicator">
        <div class="step-item active" id="indicator-1">
            <div class="step-circle">1</div>
            <div class="step-title">Thời gian</div>
        </div>
        <div class="step-item" id="indicator-2">
            <div class="step-circle">2</div>
            <div class="step-title">Chọn phòng</div>
        </div>
        <div class="step-item" id="indicator-3">
            <div class="step-circle">3</div>
            <div class="step-title">Xác nhận</div>
        </div>
    </div>

    <div class="ptit-card p-4 p-md-5">
        <form id="multiStepBooking">
            <!-- Step 1: Time Selection -->
            <div class="step-content" id="step-1">
                <h5 class="fw-bold mb-4">Bước 1: Chọn thời gian sử dụng</h5>
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label fw-bold small">Ngày sử dụng</label>
                        <input type="date" name="booking_date" id="booking_date" class="form-control form-control-lg" required min="<?= date('Y-m-d') ?>" value="<?= date('Y-m-d') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold small">Ca học</label>
                        <select name="session_id" id="session_id" class="form-select form-select-lg" required>
                            <option value="">-- Chọn ca học --</option>
                            <option value="1">Ca 1 (07:00 - 09:25)</option>
                            <option value="2">Ca 2 (09:35 - 12:00)</option>
                            <option value="3">Ca 3 (12:30 - 14:55)</option>
                            <option value="4">Ca 4 (15:05 - 17:30)</option>
                            <option value="5">Ca 5 (18:00 - 20:25)</option>
                        </select>
                    </div>
                </div>
                <div class="mt-5 d-flex justify-content-end">
                    <button type="button" class="btn btn-ptit px-5 py-3" onclick="buoc_tiep_theo(2)">Tiếp theo <i class="fa-solid fa-arrow-right ms-2"></i></button>
                </div>
            </div>

            <!-- Step 2: Room Selection -->
            <div class="step-content d-none" id="step-2">
                <h5 class="fw-bold mb-2">Bước 2: Chọn phòng trống</h5>
                <p class="text-muted small mb-4">Danh sách phòng khả dụng vào <span id="display-date" class="fw-bold"></span> - <span id="display-session" class="fw-bold"></span></p>
                
                <div id="room-filters" class="row g-2 mb-4 d-none">
                    <div class="col-md-4">
                        <select id="filter-floor" class="form-select form-select-sm" onchange="ap_dung_bo_loc()">
                            <option value="">Tất cả tầng</option>
                            <option value="1">Tầng 1</option>
                            <option value="2">Tầng 2</option>
                            <option value="3">Tầng 3</option>
                            <option value="4">Tầng 4</option>
                            <option value="5">Tầng 5</option>
                            <option value="6">Tầng 6</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select id="filter-capacity" class="form-select form-select-sm" onchange="ap_dung_bo_loc()">
                            <option value="0">Sức chứa (Tất cả)</option>
                            <option value="40">Trên 40 chỗ</option>
                            <option value="60">Trên 60 chỗ</option>
                            <option value="80">Trên 80 chỗ</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="text" id="filter-search" class="form-control form-control-sm" placeholder="Tìm tên phòng..." onkeyup="ap_dung_bo_loc()">
                    </div>
                </div>

                <div id="room-list-loading" class="text-center py-5 d-none">
                    <div class="ptit-spinner mx-auto"></div>
                    <p class="mt-3 text-muted">Đang tìm phòng trống...</p>
                </div>

                <div class="row g-3" id="room-selection-container">
                    <!-- AJAX Powered -->
                </div>

                <nav id="booking-room-pagination" class="mt-4 d-none">
                    <ul class="pagination justify-content-center">
                        <!-- AJAX Load -->
                    </ul>
                </nav>

                <input type="hidden" name="room_id" id="selected_room_id" required>

                <div class="mt-5 d-flex justify-content-between">
                    <button type="button" class="btn btn-light px-4" onclick="buoc_truoc_do(1)"><i class="fa-solid fa-arrow-left me-2"></i> Quay lại</button>
                    <button type="button" class="btn btn-ptit px-5 py-3" id="btn-to-step3" onclick="buoc_tiep_theo(3)" disabled>Xác nhận chọn phòng <i class="fa-solid fa-arrow-right ms-2"></i></button>
                </div>
            </div>

            <!-- Step 3: Purpose & Final Confirm -->
            <div class="step-content d-none" id="step-3">
                <h5 class="fw-bold mb-4">Bước 3: Hoàn tất đăng ký</h5>
                <div class="bg-light p-4 rounded mb-4">
                    <div class="row">
                        <div class="col-sm-4"><span class="text-muted small">Phòng học:</span><div class="fw-bold" id="confirm-room">-</div></div>
                        <div class="col-sm-4"><span class="text-muted small">Thời gian:</span><div class="fw-bold" id="confirm-time">-</div></div>
                        <div class="col-sm-4"><span class="text-muted small">Ca học:</span><div class="fw-bold" id="confirm-session">-</div></div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold small">Mục đích sử dụng chi tiết</label>
                    <textarea name="purpose" class="form-control" rows="5" required placeholder="Ví dụ: Mượn phòng thảo luận nhóm môn Lập trình ứng dụng web, số lượng 5 người..."></textarea>
                </div>

                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" id="terms" required>
                    <label class="form-check-label small" for="terms">
                        Tôi cam kết tuân thủ các quy định về sử dụng phòng thực hành và bảo quản thiết bị tại Tòa A3.
                    </label>
                </div>

                <div class="mt-5 d-flex justify-content-between">
                    <button type="button" class="btn btn-light px-4" onclick="buoc_truoc_do(2)"><i class="fa-solid fa-arrow-left me-2"></i> Quay lại</button>
                    <button type="submit" class="btn btn-ptit px-5 py-3" id="btn-submit">Gửi yêu cầu <i class="fa-solid fa-paper-plane ms-2"></i></button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    let buoc_hien_tai = 1;

    /**
     * Chuyển sang bước tiếp theo trong quá trình đăng ký
     * @param {number} step - Số thứ tự của bước muốn chuyển tới
     */
    function buoc_tiep_theo(step) {
        // Kiểm tra dữ liệu ở Bước 1 trước khi cho phép sang Bước 2
        if (step === 2) {
            if (!$('#booking_date').val() || !$('#session_id').val()) {
                showToast('Vui lòng chọn ngày và ca học!', 'error');
                return;
            }
            tai_danh_sach_phong_trong(); // Tải danh sách phòng trống dựa trên thời gian đã chọn
        }
        
        // Kiểm tra dữ liệu ở Bước 2 trước khi sang Bước 3
        if (step === 3) {
            if (!$('#selected_room_id').val()) {
                showToast('Vui lòng chọn phòng học!', 'error');
                return;
            }
            chuan_bi_xac_nhan(); // Hiển thị tóm tắt thông tin để người dùng kiểm tra lại
        }

        // Cập nhật giao diện ẩn/hiện các bước và thanh tiến trình
        $(`#step-${buoc_hien_tai}`).addClass('d-none');
        $(`#step-${step}`).removeClass('d-none');
        $(`#indicator-${buoc_hien_tai}`).addClass('completed').removeClass('active');
        $(`#indicator-${step}`).addClass('active');
        buoc_hien_tai = step;
        window.scrollTo({ top: 0, behavior: 'smooth' }); // Cuộn lên đầu trang cho dễ nhìn
    }

    /**
     * Quay lại bước trước đó
     * @param {number} step - Số thứ tự của bước muốn quay lại
     */
    function buoc_truoc_do(step) {
        $(`#step-${buoc_hien_tai}`).addClass('d-none');
        $(`#step-${step}`).removeClass('d-none');
        $(`#indicator-${buoc_hien_tai}`).removeClass('active');
        $(`#indicator-${step}`).addClass('active').removeClass('completed');
        buoc_hien_tai = step;
    }

    let du_lieu_phong = [];
    let trang_hien_tai = 1;

    /**
     * Gọi API để lấy danh sách phòng học đang trống
     * @param {number} page - Trang hiện tại (mặc định là 1)
     */
    function tai_danh_sach_phong_trong(page = 1) {
        trang_hien_tai = page;
        const date = $('#booking_date').val();
        const sessionId = $('#session_id').val();
        const floor = $('#filter-floor').val();
        const capacity = $('#filter-capacity').val();
        const search = $('#filter-search').val();
        
        // Hiển thị thông tin thời gian người dùng đã chọn lên giao diện
        $('#display-date').text(date);
        $('#display-session').text($('#session_id option:selected').text());
        
        const $container = $('#room-selection-container');
        const $pagination = $('#booking-room-pagination');
        
        $container.empty();
        $pagination.addClass('d-none');
        $('#room-filters').addClass('d-none');
        $('#room-list-loading').removeClass('d-none'); // Hiện loading spinner
        
        const url = `backend/api/tim_phong.php?date=${date}&session_id=${sessionId}&page=${page}&floor=${floor}&capacity=${capacity}&q=${search}&limit=9`;
        
        $.get(url, function(res) {
            $('#room-list-loading').addClass('d-none'); // Ẩn loading
            $('#room-filters').removeClass('d-none');
            
            if (res.status === 'success') {
                du_lieu_phong = res.data;
                hien_thi_danh_sach_phong(du_lieu_phong); // Vẽ các card phòng học ra màn hình
                renderBookingPagination(res.pagination); // Vẽ bộ nút phân trang
            }
        });
    }

    /**
     * Render các card phòng học từ dữ liệu API
     * @param {array} rooms - Mảng chứa thông tin các phòng
     */
    function hien_thi_danh_sach_phong(rooms) {
        const $container = $('#room-selection-container');
        if (rooms.length === 0) {
            $container.html('<div class="col-12 text-center py-5"><p class="text-ptit-red fw-bold">Rất tiếc, không có phòng nào trống!</p></div>');
            return;
        }
        
        let html = '';
        rooms.forEach(room => {
            html += `
                <div class="col-md-4">
                    <div class="ptit-card room-card-selection p-3 text-center" onclick="chon_phong(${room.id}, '${room.room_number}')">
                        <h4 class="fw-bold mb-1">${room.room_number}</h4>
                        <div class="text-ptit-red small fw-bold">${room.room_type}</div>
                        <hr class="my-2 opacity-10">
                        <div class="extra-small text-muted">Tầng ${room.floor} | ${room.capacity} chỗ</div>
                    </div>
                </div>
            `;
        });
        $container.html(html);
    }

    /**
     * Tạo bộ nút phân trang cho danh sách phòng
     * @param {object} paging - Dữ liệu phân trang từ server
     */
    function renderBookingPagination(paging) {
        const $pagination = $('#booking-room-pagination');
        const $ul = $pagination.find('ul');
        $ul.empty();

        if (paging.total_pages <= 1) {
            $pagination.addClass('d-none');
            return;
        }

        $pagination.removeClass('d-none');

        // Nút Trước
        $ul.append(`<li class="page-item ${paging.current_page === 1 ? 'disabled' : ''}"><a class="page-link" href="javascript:void(0)" onclick="tai_danh_sach_phong_trong(${paging.current_page - 1})">Trước</a></li>`);
        // Các số trang
        for (let i = 1; i <= paging.total_pages; i++) {
            $ul.append(`<li class="page-item ${paging.current_page === i ? 'active' : ''}"><a class="page-link" href="javascript:void(0)" onclick="tai_danh_sach_phong_trong(${i})">${i}</a></li>`);
        }
        // Nút Sau
        $ul.append(`<li class="page-item ${paging.current_page === paging.total_pages ? 'disabled' : ''}"><a class="page-link" href="javascript:void(0)" onclick="tai_danh_sach_phong_trong(${paging.current_page + 1})">Sau</a></li>`);
    }

    /**
     * Tải lại danh sách phòng khi thay đổi các bộ lọc (Tầng, Sức chứa, Tìm kiếm)
     */
    function ap_dung_bo_loc() {
        tai_danh_sach_phong_trong(1);
    }

    /**
     * Xử lý khi người dùng nhấn chọn một card phòng học cụ thể
     * @param {number} id - ID của phòng
     * @param {string} name - Tên (số) phòng
     */
    function chon_phong(id, name) {
        $('.room-card-selection').removeClass('selected'); // Bỏ chọn các phòng khác
        event.currentTarget.classList.add('selected'); // Đánh dấu phòng đang chọn
        $('#selected_room_id').val(id); // Lưu ID vào input ẩn để gửi form
        $('#selected_room_name').val(name); 
        $('#btn-to-step3').prop('disabled', false); // Kích hoạt nút sang bước tiếp theo
        $('#confirm-room').data('name', name);
    }

    /**
     * Hiển thị tóm tắt thông tin ở bước cuối cùng
     */
    function chuan_bi_xac_nhan() {
        $('#confirm-room').text($('#confirm-room').data('name'));
        $('#confirm-time').text($('#booking_date').val());
        $('#confirm-session').text($('#session_id option:selected').text());
    }

    $(document).ready(function() {
        // Xử lý gửi form đăng ký mượn phòng (Bước 3)
        $('#multiStepBooking').on('submit', function(e) {
            e.preventDefault();
            const $btn = $('#btn-submit');
            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span> Đang gửi...');

            $.ajax({
                url: 'backend/api/xu_ly_dat_phong.php?action=create',
                type: 'POST',
                data: $(this).serialize(),
                success: function(res) {
                    try {
                        const data = typeof res === 'string' ? JSON.parse(res) : res;
                        if (data.status === 'success') {
                            showToast('Gửi yêu cầu thành công! Vui lòng chờ phê duyệt.', 'success');
                            // Chuyển hướng sang trang Lịch sử sau khi gửi thành công
                            setTimeout(() => window.location.href = 'index.php?page=nguoi_dung&action=lich_su', 2000);
                        } else {
                            showToast(data.message || 'Có lỗi xảy ra!', 'error');
                            $btn.prop('disabled', false).html('Gửi yêu cầu <i class="fa-solid fa-paper-plane ms-2"></i>');
                        }
                    } catch(e) {
                        showToast('Đã gửi yêu cầu mượn phòng!', 'success');
                        window.location.href = 'index.php?page=nguoi_dung&action=lich_su';
                    }
                },
                error: function() {
                    showToast('Lỗi máy chủ!', 'error');
                    $btn.prop('disabled', false).html('Gửi yêu cầu <i class="fa-solid fa-paper-plane ms-2"></i>');
                }
            });
        });
    });
</script>

<?php require_once 'frontend/includes/footer.php'; ?>

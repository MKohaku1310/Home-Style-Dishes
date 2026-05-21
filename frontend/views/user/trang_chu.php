<?php require_once 'frontend/includes/header.php'; ?>

<style>
    .hero-section {
        background: linear-gradient(135deg, var(--ptit-red), var(--ptit-dark));
        border-radius: var(--radius-lg);
        padding: 3rem;
        color: white !important;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }
    .hero-section h1, .hero-section p, .hero-section .lead {
        color: white !important;
    }
    .hero-section::after {
        content: '';
        position: absolute;
        top: 0; right: 0; width: 300px; height: 100%;
        background: url('https://upload.wikimedia.org/wikipedia/commons/1/13/Logo_PTIT.png') no-repeat center;
        background-size: contain;
        opacity: 0.1;
        transform: rotate(-15deg) translateX(50px);
    }
    .clock-box {
        background: rgba(255, 255, 255, 0.1);
        padding: 1rem 1.5rem;
        border-radius: var(--radius-md);
        backdrop-filter: blur(5px);
        display: inline-block;
    }
    .stat-badge {
        position: absolute;
        top: -10px;
        right: -10px;
        background: var(--ptit-accent);
        color: var(--ptit-dark);
        width: 25px;
        height: 25px;
        border-radius: 50%;
        font-size: 0.75rem;
        font-weight: 700;
        display: none;
        align-items: center;
        justify-content: center;
        border: 2px solid var(--white);
    }
</style>

<div class="container py-2">
    <!-- Hero Header -->
    <div class="hero-section shadow-lg">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-5 fw-bold mb-2">Chào <?php echo htmlspecialchars($_SESSION['fullname'] ?? 'bạn'); ?>,</h1>
                <p class="lead opacity-75 mb-4">Chào mừng bạn đến với Hệ thống Quản lý Phòng học Tòa A3.</p>
                <div class="d-flex flex-wrap gap-3">
                    <div class="clock-box shadow-sm">
                        <i class="fa-regular fa-clock me-2"></i>
                        <span id="realtime-clock" class="fw-bold">00:00:00</span>
                    </div>
                    <div class="clock-box shadow-sm">
                        <i class="fa-regular fa-calendar-days me-2"></i>
                        <span id="today-date" class="fw-bold">Đang tải...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Action Widgets -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="ptit-card p-4 h-100 position-relative">
                <div class="card-icon-box mb-3">
                    <i class="fa-solid fa-calendar-check"></i>
                </div>
                <h5 class="fw-bold">Đặt phòng học</h5>
                <p class="text-muted small">Đăng ký mượn phòng học tòa A3 cho mục đích học tập và thảo luận.</p>
                <a href="index.php?page=nguoi_dung&action=dat_phong" class="btn btn-ptit w-100 mt-2">Bắt đầu đăng ký</a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="ptit-card p-4 h-100 position-relative">
                <div id="badge-history" class="stat-badge">0</div>
                <div class="card-icon-box mb-3" style="background: #e3f2fd; color: #1976d2;">
                    <i class="fa-solid fa-list-check"></i>
                </div>
                <h5 class="fw-bold">Lịch sử của bạn</h5>
                <p class="text-muted small">Kiểm tra trạng thái các yêu cầu đang chờ duyệt hoặc đã hoàn thành.</p>
                <a href="index.php?page=nguoi_dung&action=lich_su" class="btn btn-ptit-outline w-100 mt-2">Xem chi tiết</a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="ptit-card p-4 h-100 position-relative">
                <div id="badge-reports" class="stat-badge">0</div>
                <div class="card-icon-box mb-3" style="background: #fff8e1; color: #f57f17;">
                    <i class="fa-solid fa-screwdriver-wrench"></i>
                </div>
                <h5 class="fw-bold">Báo hỏng thiết bị</h5>
                <p class="text-muted small">Thông báo các sự cố kỹ thuật về máy chiếu, máy tính tại các phòng.</p>
                <a href="index.php?page=nguoi_dung&action=bao_hong" class="btn btn-ptit-outline w-100 mt-2">Gửi báo cáo</a>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Quick Search Room -->
        <div class="col-lg-8">
            <div class="ptit-card p-4 border-top border-5 border-danger">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0"><i class="fa-solid fa-magnifying-glass me-2"></i>Tìm kiếm phòng nhanh</h5>
                    <span class="badge bg-light text-muted fw-normal">Tra cứu tức thì</span>
                </div>
                
                <div class="row g-3 mb-4">
                    <div class="col-md-5">
                        <label class="small fw-bold text-muted mb-1">Ngày mượn</label>
                        <input type="date" id="searchDate" class="form-control" value="<?= date('Y-m-d') ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="small fw-bold text-muted mb-1">Ca học</label>
                        <select id="searchSession" class="form-select">
                            <option value="1">Ca 1 (07:00 - 09:25)</option>
                            <option value="2">Ca 2 (09:35 - 12:00)</option>
                            <option value="3">Ca 3 (12:30 - 14:55)</option>
                            <option value="4">Ca 4 (15:05 - 17:30)</option>
                            <option value="5">Ca 5 (18:00 - 20:25)</option>
                            <option value="6">Ca 6 (20:30 - 22:00)</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="small fw-bold text-muted mb-1">&nbsp;</label>
                        <button id="btnQuickSearch" class="btn btn-ptit w-100">Tìm kiếm</button>
                    </div>
                </div>

                <div id="quickSearchResults" class="row">
                    <!-- AJAX Load Results -->
                    <div class="empty-state">
                        <i class="fa-solid fa-search"></i>
                        <p>Nhấn nút tìm kiếm để xem danh sách phòng trống theo ca học.</p>
                    </div>
                </div>

                <!-- Pagination Container -->
                <nav id="roomPagination" class="mt-4 d-none">
                    <ul class="pagination justify-content-center">
                        <!-- AJAX Load Pagination -->
                    </ul>
                </nav>
            </div>
        </div>

        <!-- Today's Schedule -->
        <div class="col-lg-4">
            <div class="ptit-card p-0 overflow-hidden">
                <div class="p-4 bg-light border-bottom">
                    <h6 class="fw-bold mb-0"><i class="fa-solid fa-calendar-day me-2 text-ptit-red"></i>Lịch đăng ký hôm nay</h6>
                </div>
                <div id="today-usage-list" class="list-group list-group-flush">
                    <!-- AJAX Load Usage -->
                    <div class="p-4 text-center text-muted small">Đang tải lịch trình...</div>
                </div>
                <div class="p-3 text-center bg-light">
                    <a href="index.php?page=nguoi_dung&action=lich_su" class="small text-ptit-red text-decoration-none fw-bold">Xem tất cả lịch sử ứng dụng</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    /**
     * Cập nhật đồng hồ thời gian thực và ngày tháng hiện tại
     */
    function updateClock() {
        const now = new Date();
        const timeStr = now.toLocaleTimeString('vi-VN', { hour12: false });
        $('#realtime-clock').text(timeStr);

        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        $('#today-date').text(now.toLocaleDateString('vi-VN', options));
    }

    /**
     * Tải các con số thống kê nhanh (số yêu cầu chờ duyệt, báo hỏng...)
     */
    function loadHomeStats() {
        $.get('backend/api/thong_ke_trang_chu.php', function(res) {
            if (res.status === 'success') {
                // Hiển thị badge (số lượng) nếu có dữ liệu
                if (res.pending_count > 0) $('#badge-history').text(res.pending_count).css('display', 'flex');
                if (res.broken_count > 0) $('#badge-reports').text(res.broken_count).css('display', 'flex');
            }
        });
    }

    /**
     * Tải danh sách các phòng mà người dùng đã đăng ký sử dụng trong ngày hôm nay
     */
    function loadTodayUsage() {
        $.get('backend/api/lich_su_nguoi_dung.php?today=1', function(res) {
            const $list = $('#today-usage-list');
            if (res.status === 'success' && res.data.length > 0) {
                let html = '';
                res.data.forEach(item => {
                    // Xác định màu sắc dựa trên trạng thái của yêu cầu
                    const statusClass = item.status === 'approved' ? 'text-success' : (item.status === 'rejected' ? 'text-danger' : 'text-warning');
                    html += `
                        <div class="list-group-item p-3 border-0 border-bottom">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold text-ptit-red">${item.room_number}</span>
                                <small class="fw-bold ${statusClass}">${item.status_text}</small>
                            </div>
                            <div class="small text-muted">
                                <i class="fa-regular fa-clock me-1"></i> ${item.session_name} (${item.start_time} - ${item.end_time})
                            </div>
                        </div>
                    `;
                });
                $list.html(html);
            } else {
                $list.html('<div class="p-5 text-center text-muted small"><i class="fa-solid fa-calendar-check d-block fs-2 mb-2 opacity-25"></i>Bạn không có lịch mượn phòng nào hôm nay.</div>');
            }
        });
    }

    $(document).ready(function() {
        // Khởi tạo đồng hồ và cập nhật mỗi giây
        setInterval(updateClock, 1000);
        updateClock();
        
        // Tải các thông tin động khi trang vừa load xong
        loadHomeStats();
        loadTodayUsage();

        let currentPage = 1;

        // Sự kiện khi nhấn nút Tìm kiếm nhanh
        $('#btnQuickSearch').on('click', function() {
            currentPage = 1;
            fetchRooms(currentPage);
        });

        /**
         * Tìm kiếm danh sách phòng trống dựa trên ngày và ca học qua API
         * @param {number} page - Số trang hiện tại
         */
        function fetchRooms(page) {
            const date = $('#searchDate').val();
            const sessionId = $('#searchSession').val();
            const $results = $('#quickSearchResults');
            const $pagination = $('#roomPagination');
            
            // Hiển thị loading spinner trong khi chờ API trả về
            $results.html('<div class="col-12 text-center py-5"><div class="ptit-spinner mx-auto"></div><p class="mt-3 text-muted">Đang tìm phòng trống...</p></div>');
            $pagination.addClass('d-none');

            $.get(`backend/api/tim_phong.php?date=${date}&session_id=${sessionId}&page=${page}&limit=6`, function(res) {
                if (res.status === 'success') {
                    // Nếu không có phòng nào trống
                    if (res.data.length === 0) {
                        $results.html('<div class="empty-state py-5"><i class="fa-solid fa-face-frown fs-1 mb-3 opacity-25"></i><p>Rất tiếc! Không còn phòng nào trống trong ca học này.</p></div>');
                        return;
                    }
                    
                    // Render danh sách phòng dưới dạng Card
                    let html = '';
                    res.data.forEach(room => {
                        html += `
                            <div class="col-md-6 mb-3">
                                <div class="card border border-light shadow-sm h-100 p-2 hover-lift">
                                    <div class="card-body d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="fw-bold mb-1 text-dark">${room.room_number}</h6>
                                            <div class="extra-small text-muted">
                                                <i class="fa-solid fa-layer-group me-1"></i>Tầng ${room.floor} | 
                                                <i class="fa-solid fa-users me-1"></i>${room.capacity} chỗ
                                            </div>
                                        </div>
                                        <a href="index.php?page=nguoi_dung&action=dat_phong&room_id=${room.id}&date=${date}&session_id=${sessionId}" class="btn btn-sm btn-ptit-outline">Đặt ngay</a>
                                    </div>
                                </div>
                             </div>
                        `;
                    });
                    $results.html(html);

                    // Hiển thị phân trang nếu có nhiều hơn 1 trang
                    renderPagination(res.pagination);
                }
            });
        }

        /**
         * Xây dựng giao diện phân trang (Pagination)
         * @param {object} paging - Đối tượng chứa thông tin phân trang từ API
         */
        function renderPagination(paging) {
            const $pagination = $('#roomPagination');
            const $ul = $pagination.find('ul');
            $ul.empty();

            if (paging.total_pages <= 1) {
                $pagination.addClass('d-none');
                return;
            }

            $pagination.removeClass('d-none');

            // Nút "Trước"
            $ul.append(`
                <li class="page-item ${paging.current_page === 1 ? 'disabled' : ''}">
                    <a class="page-link" href="javascript:void(0)" data-page="${paging.current_page - 1}">Trước</a>
                </li>
            `);

            // Các số trang cụ thể
            for (let i = 1; i <= paging.total_pages; i++) {
                $ul.append(`
                    <li class="page-item ${paging.current_page === i ? 'active' : ''}">
                        <a class="page-link" href="javascript:void(0)" data-page="${i}">${i}</a>
                    </li>
                `);
            }

            // Nút "Sau"
            $ul.append(`
                <li class="page-item ${paging.current_page === paging.total_pages ? 'disabled' : ''}">
                    <a class="page-link" href="javascript:void(0)" data-page="${paging.current_page + 1}">Sau</a>
                </li>
            `);

            // Xử lý sự kiện khi nhấn vào các nút phân trang
            $ul.find('.page-link').on('click', function() {
                const page = $(this).data('page');
                if (page && page !== paging.current_page) {
                    fetchRooms(page);
                    // Cuộn màn hình lên đầu danh sách kết quả để người dùng dễ nhìn
                    $('html, body').animate({
                        scrollTop: $("#btnQuickSearch").offset().top - 100
                    }, 200);
                }
            });
        }
    });
</script>

<?php require_once 'frontend/includes/footer.php'; ?>

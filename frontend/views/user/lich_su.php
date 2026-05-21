<?php require_once 'frontend/includes/header.php'; ?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0">Lịch sử đăng ký của bạn</h3>
        <a href="index.php?page=nguoi_dung&action=dat_phong" class="btn btn-ptit">
            <i class="fa-solid fa-plus me-2"></i> Đăng ký mới
        </a>
    </div>

    <div class="ptit-card">
        <div class="p-3 border-bottom d-flex gap-2 flex-wrap">
            <button class="btn btn-sm btn-outline-ptit filter-btn active" data-status="all">Tất cả</button>
            <button class="btn btn-sm btn-outline-ptit filter-btn" data-status="pending">Chờ duyệt</button>
            <button class="btn btn-sm btn-outline-ptit filter-btn" data-status="approved">Đã duyệt</button>
            <button class="btn btn-sm btn-outline-ptit filter-btn" data-status="rejected">Đã từ chối</button>
        </div>
        
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="history-table">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Phòng học</th>
                        <th>Thời gian</th>
                        <th>Ca học</th>
                        <th>Trạng thái</th>
                        <th class="text-end pe-4">Thao tác</th>
                    </tr>
                </thead>
                <tbody id="history-body">
                    <!-- AJAX Load -->
                </tbody>
            </table>
        </div>
        
        <div id="history-empty" class="text-center py-5 d-none">
            <i class="fa-solid fa-calendar-xmark text-muted mb-3" style="font-size: 3rem; opacity: 0.3;"></i>
            <p class="text-muted">Bạn chưa có lịch sử đăng ký nào.</p>
        </div>
        
        <div id="history-loading" class="text-center py-5">
            <div class="ptit-spinner mx-auto"></div>
            <p class="mt-3 text-muted">Đang tải lịch sử...</p>
        </div>

        <nav id="history-pagination" class="py-3 d-none">
            <ul class="pagination justify-content-center mb-0">
                <!-- AJAX Load -->
            </ul>
        </nav>
    </div>
</div>

<!-- Modal Detail -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-ptit-red text-white">
                <h5 class="modal-title fw-bold">Chi tiết yêu cầu</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" id="modalDetailBody">
                <!-- AJAX Load -->
            </div>
        </div>
    </div>
</div>

<script>
    let currentStatus = 'all'; // Trạng thái lọc hiện tại (mặc định là tất cả)
    let currentPage = 1;        // Trang hiện tại

    /**
     * Trả về đoạn mã HTML hiển thị Badge trạng thái với màu sắc tương ứng
     * @param {string} status - Trạng thái (pending, approved, rejected)
     */
    function getStatusBadge(status) {
        switch(status) {
            case 'pending': return '<span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-2">Chờ duyệt</span>';
            case 'approved': return '<span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2">Đã duyệt</span>';
            case 'rejected': return '<span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2">Từ chối</span>';
            default: return '';
        }
    }

    /**
     * Tải danh sách lịch sử đăng ký từ server
     * @param {string} status - Trạng thái cần lọc
     * @param {number} page - Trang cần tải
     */
    function tai_lich_su(status = 'all', page = 1) {
        currentPage = page;
        $('#history-loading').removeClass('d-none'); // Hiện loading
        $('#history-body').empty(); // Xóa dữ liệu cũ trong bảng
        $('#history-empty').addClass('d-none');
        $('#history-pagination').addClass('d-none');
        
        $.get(`backend/api/lich_su_nguoi_dung.php?action=list&status=${status}&page=${page}`, function(res) {
            $('#history-loading').addClass('d-none');
            if (res.status === 'success' && res.data.length > 0) {
                let html = '';
                res.data.forEach(item => {
                    // Xây dựng các hàng dữ liệu cho bảng
                    html += `
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold">${item.room_number}</div>
                                <small class="text-muted">${item.room_type || 'Phòng học'}</small>
                            </td>
                            <td>${item.booking_date}</td>
                            <td>${item.session_name}</td>
                            <td>${getStatusBadge(item.status)}</td>
                            <td class="text-end pe-4">
                                <button class="btn btn-sm btn-light me-1" onclick="xem_chi_tiet(${item.id})" title="Xem chi tiết">
                                    <i class="fa-solid fa-eye text-primary"></i>
                                </button>
                                ${item.status === 'pending' ? `
                                    <button class="btn btn-sm btn-light" onclick="huy_dat_phong(${item.id})" title="Hủy yêu cầu">
                                        <i class="fa-solid fa-trash-can text-danger"></i>
                                    </button>
                                ` : ''}
                            </td>
                        </tr>
                    `;
                });
                $('#history-body').html(html);
                renderPagination(res.pagination); // Vẽ bộ nút chuyển trang
            } else {
                $('#history-empty').removeClass('d-none'); // Hiện thông báo nếu không có dữ liệu
            }
        });
    }

    /**
     * Vẽ bộ nút phân trang
     */
    function renderPagination(paging) {
        const $nav = $('#history-pagination');
        const $ul = $nav.find('ul');
        $ul.empty();

        if (paging.total_pages <= 1) return;

        $nav.removeClass('d-none');

        // Nút Trước
        $ul.append(`
            <li class="page-item ${paging.current_page === 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="tai_lich_su('${currentStatus}', ${paging.current_page - 1}); return false;">Trước</a>
            </li>
        `);

        // Các nút số trang
        for (let i = 1; i <= paging.total_pages; i++) {
            $ul.append(`
                <li class="page-item ${paging.current_page === i ? 'active' : ''}">
                    <a class="page-link" href="#" onclick="tai_lich_su('${currentStatus}', ${i}); return false;">${i}</a>
                </li>
            `);
        }

        // Nút Sau
        $ul.append(`
            <li class="page-item ${paging.current_page === paging.total_pages ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="tai_lich_su('${currentStatus}', ${paging.current_page + 1}); return false;">Sau</a>
            </li>
        `);
    }

    /**
     * Xử lý hủy một yêu cầu đang ở trạng thái 'pending' (Chờ duyệt)
     * @param {number} id - ID của yêu cầu mượn phòng
     */
    function huy_dat_phong(id) {
        Swal.fire({
            title: 'Xác nhận hủy?',
            text: "Bạn có chắc chắn muốn hủy yêu cầu mượn phòng này không?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#8b0000',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Có, hủy ngay!',
            cancelButtonText: 'Quay lại'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'backend/api/xu_ly_dat_phong.php?action=cancel',
                    type: 'POST',
                    data: { id: id },
                    success: function(res) {
                        try {
                            const data = typeof res === 'string' ? JSON.parse(res) : res;
                            if (data.status === 'success') {
                                showToast('Đã hủy yêu cầu đăng ký!', 'success');
                                tai_lich_su(currentStatus); // Tải lại danh sách sau khi hủy
                            } else {
                                showToast(data.message, 'error');
                            }
                        } catch(e) {
                            showToast('Đã thực hiện yêu cầu hủy', 'info');
                            tai_lich_su(currentStatus);
                        }
                    }
                });
            }
        });
    }

    /**
     * Mở Modal hiển thị chi tiết thông tin mượn phòng (Ngày, giờ, mục đích, phản hồi admin)
     * @param {number} id - ID của yêu cầu
     */
    function xem_chi_tiet(id) {
        const modal = new bootstrap.Modal(document.getElementById('detailModal'));
        $('#modalDetailBody').html('<div class="text-center py-4"><div class="ptit-spinner mx-auto"></div></div>');
        modal.show();

        // Lấy chi tiết từ API
        $.get(`backend/api/lich_su_nguoi_dung.php?action=detail&id=${id}`, function(res) {
            if (res.status === 'success') {
                const item = res.data;
                const html = `
                    <div class="mb-3 d-flex justify-content-between">
                        <span class="text-muted small">Phòng học:</span>
                        <span class="fw-bold text-ptit-red">${item.room_number}</span>
                    </div>
                    <div class="mb-3 d-flex justify-content-between">
                        <span class="text-muted small">Ngày mượn:</span>
                        <span class="fw-bold">${item.booking_date}</span>
                    </div>
                    <div class="mb-3 d-flex justify-content-between">
                        <span class="text-muted small">Ca học:</span>
                        <span class="fw-bold">${item.session_name} (${item.start_time.substring(0, 5)} - ${item.end_time.substring(0, 5)})</span>
                    </div>
                    <div class="mb-3">
                        <div class="text-muted small mb-2">Mục đích:</div>
                        <div class="p-3 bg-light rounded small">${item.purpose || 'Không có mô tả'}</div>
                    </div>
                    ${item.admin_note ? `
                        <div class="mb-0">
                            <div class="text-muted small mb-2">Phản hồi từ Admin:</div>
                            <div class="p-3 border-start border-4 border-danger bg-danger-subtle rounded small">${item.admin_note}</div>
                        </div>
                    ` : ''}
                `;
                $('#modalDetailBody').html(html);
            }
        });
    }

    $(document).ready(function() {
        tai_lich_su(); // Tải dữ liệu mặc định khi vừa mở trang
        
        // Xử lý sự kiện khi nhấn vào các nút lọc trạng thái (Tất cả, Chờ duyệt, ...)
        $('.filter-btn').on('click', function() {
            $('.filter-btn').removeClass('active');
            $(this).addClass('active');
            currentStatus = $(this).data('status');
            tai_lich_su(currentStatus);
        });
    });
</script>

<?php require_once 'frontend/includes/footer.php'; ?>

<?php require_once 'frontend/includes/header.php'; ?>

<div class="container-fluid py-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h3 class="fw-bold mb-1">Quản lý duyệt phòng</h3>
            <p class="text-muted small mb-0">Phê duyệt hoặc từ chối các yêu cầu mượn phòng tại tòa A3</p>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <div class="btn-group shadow-sm">
                <button class="btn btn-outline-success" id="btn-bulk-approve"><i class="fa-solid fa-check me-2"></i>Duyệt chọn</button>
                <button class="btn btn-outline-danger" id="btn-bulk-reject"><i class="fa-solid fa-xmark me-2"></i>Từ chối chọn</button>
                <button class="btn btn-outline-primary" id="btn-export"><i class="fa-solid fa-file-export me-2"></i>Xuất Excel</button>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="ptit-card px-3 py-2 mb-4 bg-light">
        <ul class="nav nav-pills nav-justified" id="manageTabs">
            <li class="nav-item"><button class="nav-link active" data-status="all">Tất cả</button></li>
            <li class="nav-item"><button class="nav-link text-warning" data-status="pending">Đang chờ <span id="count-pending" class="badge bg-warning text-dark ms-1 d-none">0</span></button></li>
            <li class="nav-item"><button class="nav-link text-success" data-status="approved">Đã duyệt</button></li>
            <li class="nav-item"><button class="nav-link text-danger" data-status="rejected">Bị từ chối</button></li>
        </ul>
    </div>

    <!-- Table Section -->
    <div class="ptit-card p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="manageTable">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4" style="width: 40px;"><input type="checkbox" class="form-check-input" id="check-all"></th>
                        <th>Người đăng ký</th>
                        <th>Phòng & Ca</th>
                        <th>Thời gian</th>
                        <th>Mục đích</th>
                        <th>Trạng thái</th>
                        <th class="text-end pe-4">Thao tác</th>
                    </tr>
                </thead>
                <tbody id="manageBody">
                    <!-- AJAX Load -->
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="ptit-spinner mx-auto"></div>
                            <p class="mt-2 text-muted">Đang tải dữ liệu...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <nav id="manage-pagination" class="py-3 d-none">
            <ul class="pagination justify-content-center mb-0">
                <!-- AJAX Load -->
            </ul>
        </nav>
    </div>
</div>


<!-- Rejection Reason Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold">Lý do từ chối</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" id="reject-id">
                <textarea id="reject-note" class="form-control" rows="4" placeholder="Nhập lý do từ chối yêu cầu này..."></textarea>
            </div>
            <div class="modal-footer border-top-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-ptit" id="confirm-reject">Xác nhận từ chối</button>
            </div>
        </div>
    </div>
</div>

<script>
    let trang_thai_hien_tai = 'all'; // Trạng thái lọc (Tất cả, Đang chờ, Đã duyệt, ...)
    let trang_hien_tai = 1;         // Trang hiện tại đang xem
    
    /**
     * Tải danh sách các yêu cầu mượn phòng từ server
     * @param {string} status - Trạng thái lọc
     * @param {number} page - Trang cần tải
     */
    function tai_danh_sach_duyet_phong(status = 'all', page = 1) {
        trang_thai_hien_tai = status;
        trang_hien_tai = page;
        const $body = $('#manageBody');
        const $nav = $('#manage-pagination');
        
        // Hiển thị trạng thái đang tải
        $body.html('<tr><td colspan="7" class="text-center py-5"><div class="ptit-spinner mx-auto"></div></td></tr>');
        $nav.addClass('d-none');

        $.get(`backend/api/quan_tri_dat_phong.php?status=${status}&page=${page}`, function(res) {
            if (res.status === 'success') {
                if (res.data.length === 0) {
                    $body.html('<tr><td colspan="7" class="text-center py-5 text-muted">Không có yêu cầu nào.</td></tr>');
                    return;
                }

                let html = '';
                res.data.forEach(item => {
                    // Xác định màu sắc Badge dựa trên trạng thái
                    let statusBadge = '';
                    switch(item.status) {
                        case 'pending': statusBadge = '<span class="badge bg-warning-soft text-warning">Chờ duyệt</span>'; break;
                        case 'approved': statusBadge = '<span class="badge bg-success-soft text-success">Đã duyệt</span>'; break;
                        case 'rejected': statusBadge = '<span class="badge bg-danger-soft text-danger">Bị từ chối</span>'; break;
                        case 'cancelled': statusBadge = '<span class="badge bg-light text-muted">Đã hủy</span>'; break;
                    }

                    // Xây dựng hàng dữ liệu cho bảng
                    html += `
                        <tr class="${item.status === 'pending' ? 'bg-light-red-05' : ''}">
                            <td class="ps-4">
                                ${item.status === 'pending' ? `<input type="checkbox" class="form-check-input item-check" value="${item.id}">` : ''}
                            </td>
                            <td>
                                <div class="fw-bold text-dark">${item.fullname}</div>
                                <div class="extra-small text-muted">${item.username}</div>
                            </td>
                            <td>
                                <div class="badge bg-ptit-red-soft text-ptit-red mb-1">${item.room_number}</div>
                                <div class="extra-small text-muted">${item.session_name}</div>
                            </td>
                            <td>
                                <div class="fw-medium small">${item.booking_date}</div>
                            </td>
                            <td><div class="text-truncate small" style="max-width: 150px;" title="${item.purpose}">${item.purpose}</div></td>
                            <td>${statusBadge}</td>
                            <td class="text-end pe-4">
                                ${item.status === 'pending' ? `
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-success" onclick="duyet_yeu_cau(${item.id})" title="Duyệt"><i class="fa-solid fa-check"></i></button>
                                        <button class="btn btn-sm btn-danger" onclick="hien_thi_modal_tu_choi(${item.id})" title="Từ chối"><i class="fa-solid fa-xmark"></i></button>
                                    </div>
                                ` : `
                                    <button class="btn btn-sm btn-outline-secondary" onclick="hoan_tac_trang_thai(${item.id})" title="Đặt lại về Chờ duyệt">
                                        <i class="fa-solid fa-rotate-left"></i> Hoàn tác
                                    </button>
                                `}
                            </td>
                        </tr>
                    `;
                });
                $body.html(html);
                renderPagination(res.pagination); // Vẽ bộ nút phân trang
                
                // Cập nhật số lượng yêu cầu đang chờ trên thanh Tab
                const totalPending = res.total_pending;
                if (totalPending > 0) {
                    $('#count-pending').text(totalPending).removeClass('d-none');
                } else {
                    $('#count-pending').addClass('d-none');
                }
            }
        });
    }

    /**
     * Tạo bộ nút phân trang
     */
    function renderPagination(paging) {
        const $nav = $('#manage-pagination');
        const $ul = $nav.find('ul');
        $ul.empty();

        if (paging.total_pages <= 1) return;

        $nav.removeClass('d-none');

        // Nút Trước
        $ul.append(`
            <li class="page-item ${paging.current_page === 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="tai_danh_sach_duyet_phong('${trang_thai_hien_tai}', ${paging.current_page - 1}); return false;">Trước</a>
            </li>
        `);

        // Các nút số trang
        for (let i = 1; i <= paging.total_pages; i++) {
            $ul.append(`
                <li class="page-item ${paging.current_page === i ? 'active' : ''}">
                    <a class="page-link" href="#" onclick="tai_danh_sach_duyet_phong('${trang_thai_hien_tai}', ${i}); return false;">${i}</a>
                </li>
            `);
        }

        // Nút Sau
        $ul.append(`
            <li class="page-item ${paging.current_page === paging.total_pages ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="tai_danh_sach_duyet_phong('${trang_thai_hien_tai}', ${paging.current_page + 1}); return false;">Sau</a>
            </li>
        `);
    }

    /**
     * Thực hiện phê duyệt yêu cầu (Chấp nhận cho mượn phòng)
     * @param {number|array} id - ID hoặc mảng ID cần phê duyệt
     */
    function duyet_yeu_cau(id) {
        const ids = Array.isArray(id) ? id : [id];
        $.post('backend/api/xu_ly_dat_phong.php?action=bulk_approve', { ids: ids }, function(res) {
            try {
                const data = typeof res === 'string' ? JSON.parse(res) : res;
                if (data.status === 'success') {
                    showToast(data.message, 'success');
                    tai_danh_sach_duyet_phong(trang_thai_hien_tai);
                } else {
                    showToast(data.message, 'error');
                }
            } catch(e) { 
                showToast('Đã thực hiện phê duyệt', 'success');
                tai_danh_sach_duyet_phong(trang_thai_hien_tai);
            }
        });
    }

    /**
     * Đưa một yêu cầu đã duyệt hoặc từ chối quay trở về trạng thái 'Chờ duyệt'
     * @param {number} id - ID yêu cầu
     */
    function hoan_tac_trang_thai(id) {
        Swal.fire({
            title: 'Xác nhận hoàn tác?',
            text: "Yêu cầu này sẽ quay trở về trạng thái 'Chờ duyệt'.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Đồng ý',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('backend/api/xu_ly_dat_phong.php?action=reset_status', { id: id }, function(res) {
                    if (res.status === 'success') {
                        showToast(res.message, 'success');
                        tai_danh_sach_duyet_phong(trang_thai_hien_tai);
                    } else {
                        showToast(res.message, 'error');
                    }
                });
            }
        });
    }

    /**
     * Hiển thị Modal để nhập lý do từ chối yêu cầu
     * @param {number|array} ids - ID hoặc mảng ID bị từ chối
     */
    function hien_thi_modal_tu_choi(ids) {
        const idArray = Array.isArray(ids) ? ids : [ids];
        $('#reject-id').val(idArray.join(',')); // Lưu danh sách ID vào input ẩn trong modal
        $('#reject-note').val(''); // Xóa trắng nội dung cũ
        const modal = new bootstrap.Modal(document.getElementById('rejectModal'));
        modal.show();
    }

    $(document).ready(function() {
        // Tải dữ liệu mặc định (tất cả) khi trang mở ra
        tai_danh_sach_duyet_phong('all');

        // Xử lý sự kiện khi nhấn vào các tab trạng thái (Tất cả, Đang chờ, ...)
        $('#manageTabs button').on('click', function() {
            $('#manageTabs button').removeClass('active');
            $(this).addClass('active');
            tai_danh_sach_duyet_phong($(this).data('status'));
        });

        // Xử lý chọn tất cả checkbox trong bảng
        $('#check-all').on('change', function() {
            $('.item-check').prop('checked', this.checked);
        });

        // Xử lý nút xác nhận từ chối bên trong Modal
        $('#confirm-reject').on('click', function() {
            const idsStr = $('#reject-id').val();
            const ids = idsStr.split(',');
            const note = $('#reject-note').val();
            if (!note) { showToast('Vui lòng nhập lý do!', 'error'); return; }

            $.post('backend/api/xu_ly_dat_phong.php?action=bulk_reject', { ids: ids, admin_note: note }, function(res) {
                showToast('Đã từ chối các yêu cầu đã chọn', 'info');
                bootstrap.Modal.getInstance(document.getElementById('rejectModal')).hide(); // Đóng modal
                tai_danh_sach_duyet_phong(trang_thai_hien_tai, trang_hien_tai); // Tải lại bảng
            });
        });

        // Xử lý nút từ chối hàng loạt (các mục đã chọn qua checkbox)
        $('#btn-bulk-reject').on('click', function() {
            const ids = $('.item-check:checked').map(function() { return $(this).val(); }).get();
            if (ids.length === 0) { showToast('Chọn ít nhất 1 yêu cầu!', 'error'); return; }
            hien_thi_modal_tu_choi(ids);
        });

        // Xử lý nút duyệt hàng loạt (các mục đã chọn qua checkbox)
        $('#btn-bulk-approve').on('click', function() {
            const ids = $('.item-check:checked').map(function() { return $(this).val(); }).get();
            if (ids.length === 0) { showToast('Chọn ít nhất 1 yêu cầu!', 'error'); return; }
            duyet_yeu_cau(ids);
        });

        // Xuất file báo cáo dựa trên trạng thái đang lọc hiện tại
        $('#btn-export').on('click', function() {
            window.location.href = `backend/api/xuat_file_dat_phong.php?status=${trang_thai_hien_tai}`;
        });
    });
</script>

<style>
    .bg-light-red-05 { background-color: rgba(139, 0, 0, 0.02); }
</style>

<?php require_once 'frontend/includes/footer.php'; ?>

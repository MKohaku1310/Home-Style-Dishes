<?php require_once 'frontend/includes/header.php'; ?>

<div class="container-fluid py-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h3 class="fw-bold mb-1">Quản lý báo hỏng</h3>
            <p class="text-muted small mb-0">Theo dõi và xử lý các sự cố thiết bị tại tòa A3</p>
        </div>
        <div class="col-md-6 text-md-end">
            <button class="btn btn-outline-primary shadow-sm" id="btn-export-reports">
                <i class="fa-solid fa-file-export me-2"></i>Xuất Excel
            </button>
        </div>
    </div>

    <div class="ptit-card p-0 overflow-hidden shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Người báo cáo</th>
                        <th>Phòng học</th>
                        <th style="width: 300px;">Mô tả sự cố</th>
                        <th>Ngày báo</th>
                        <th>Trạng thái</th>
                        <th class="text-end pe-4">Thao tác</th>
                    </tr>
                </thead>
                <tbody id="reportsBody">
                    <!-- AJAX Load -->
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="ptit-spinner mx-auto"></div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <nav id="reports-pagination" class="py-3 d-none">
            <ul class="pagination justify-content-center mb-0">
                <!-- AJAX Load -->
            </ul>
        </nav>
    </div>
</div>

<script>
    let trang_hien_tai = 1;

    /**
     * Tải danh sách các báo cáo sự cố từ server
     * @param {number} page - Trang cần tải
     */
    function tai_danh_sach_bao_cao(page = 1) {
        trang_hien_tai = page;
        const $body = $('#reportsBody');
        const $nav = $('#reports-pagination');
        
        // Hiển thị trạng thái đang tải
        $body.html('<tr><td colspan="6" class="text-center py-5"><div class="ptit-spinner mx-auto"></div></td></tr>');
        $nav.addClass('d-none');

        $.get(`backend/api/xu_ly_bao_cao.php?page=${page}`, function(res) {
            if (res.status === 'success') {
                if (res.data.length === 0) {
                    $body.html('<tr><td colspan="6" class="text-center py-5 text-muted"><i class="fa-solid fa-clipboard-check fs-2 text-muted mb-3 d-block"></i>Hiện chưa có báo cáo sự cố nào.</td></tr>');
                    return;
                }

                let html = '';
                res.data.forEach(r => {
                    // Xác định màu sắc Badge trạng thái
                    const statusBadge = r.status === 'pending' 
                        ? '<span class="badge bg-warning-soft text-warning"><i class="fa-solid fa-clock me-1"></i>Đang chờ</span>'
                        : '<span class="badge bg-success-soft text-success"><i class="fa-solid fa-check-circle me-1"></i>Đã xử lý</span>';
                    
                    // Nút thao tác tùy theo trạng thái
                    const actionBtn = r.status === 'pending'
                        ? `<button class="btn btn-sm btn-success rounded-pill px-3" onclick="xu_ly_bao_cao(${r.id})">Đánh dấu đã xử lý</button>`
                        : '<span class="text-success small"><i class="fa-solid fa-check me-1"></i>Hoàn tất</span>';

                    html += `
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold">${r.fullname}</div>
                                <div class="extra-small text-muted">ID: #${r.id}</div>
                            </td>
                            <td><span class="badge bg-ptit-red-soft text-ptit-red">${r.room_number}</span></td>
                            <td><div class="text-wrap small" style="max-width: 300px;">${r.description}</div></td>
                            <td><div class="small">${r.created_at}</div></td>
                            <td>${statusBadge}</td>
                            <td class="text-end pe-4">${actionBtn}</td>
                        </tr>
                    `;
                });
                $body.html(html);
                renderPagination(res.pagination); // Vẽ bộ nút chuyển trang
            }
        });
    }

    /**
     * Tạo bộ nút phân trang cho danh sách báo cáo
     */
    function renderPagination(paging) {
        const $nav = $('#reports-pagination');
        const $ul = $nav.find('ul');
        $ul.empty();

        if (paging.total_pages <= 1) return;
        $nav.removeClass('d-none');

        $ul.append(`<li class="page-item ${paging.current_page === 1 ? 'disabled' : ''}"><a class="page-link" href="#" onclick="tai_danh_sach_bao_cao(${paging.current_page - 1}); return false;">Trước</a></li>`);
        for (let i = 1; i <= paging.total_pages; i++) {
            $ul.append(`<li class="page-item ${paging.current_page === i ? 'active' : ''}"><a class="page-link" href="#" onclick="tai_danh_sach_bao_cao(${i}); return false;">${i}</a></li>`);
        }
        $ul.append(`<li class="page-item ${paging.current_page === paging.total_pages ? 'disabled' : ''}"><a class="page-link" href="#" onclick="tai_danh_sach_bao_cao(${paging.current_page + 1}); return false;">Sau</a></li>`);
    }

    /**
     * Cập nhật trạng thái sự cố thiết bị thành 'resolved' (Đã xử lý)
     * @param {number} id - ID của bản ghi báo hỏng
     */
    function xu_ly_bao_cao(id) {
        Swal.fire({
            title: 'Xác nhận xử lý?',
            text: "Đánh dấu sự cố này đã được khắc phục hoàn toàn.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Đã xử lý xong',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                // Gửi yêu cầu cập nhật lên server
                $.post('backend/api/xu_ly_bao_cao.php', { id: id, action: 'resolve' }, function(res) {
                    if (res.status === 'success') {
                        showToast(res.message, 'success');
                        tai_danh_sach_bao_cao(trang_hien_tai); // Tải lại danh sách sau khi cập nhật
                    }
                });
            }
        });
    }

    $(document).ready(function() {
        tai_danh_sach_bao_cao(1); // Tải dữ liệu trang đầu tiên
        
        // Xử lý nút xuất dữ liệu ra file Excel (.csv)
        $('#btn-export-reports').on('click', function() {
            window.location.href = 'backend/api/xuat_file_bao_cao.php';
        });
    });
</script>


<?php require_once 'frontend/includes/footer.php'; ?>

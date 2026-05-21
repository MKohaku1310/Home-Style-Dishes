<?php require_once 'frontend/includes/header.php'; ?>

<div class="container-fluid py-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6 text-center text-md-start">
            <h3 class="fw-bold mb-1">Quản lý phòng học</h3>
            <p class="text-muted small mb-0">Thiết lập và vận hành các phòng tại tòa A3</p>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <a href="index.php?page=quan_tri&action=lich_muon_phong" class="btn btn-outline-ptit shadow-sm py-2 px-4 me-2">
                <i class="fa-solid fa-calendar-days me-2"></i>Xem lịch mượn phòng
            </a>
            <button class="btn btn-ptit shadow-sm py-2 px-4" data-bs-toggle="modal" data-bs-target="#roomModal" onclick="resetForm()">
                <i class="fa-solid fa-plus me-2"></i>Thêm phòng mới
            </button>
        </div>
    </div>

    <!-- Stats Summary -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="ptit-card px-3 py-2 bg-light text-center small">
                <span class="text-muted">Tổng phòng:</span> <span class="fw-bold fs-6 ms-1"><?= count($rooms ?? []) ?></span>
            </div>
        </div>
    </div>

    <div class="ptit-card p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Tên phòng</th>
                        <th>Vị trí</th>
                        <th>Loại phòng</th>
                        <th>Sức chứa</th>
                        <th>Trạng thái</th>
                        <th class="text-end pe-4">Thao tác</th>
                    </tr>
                </thead>
                <tbody id="roomTableBody">
                    <!-- AJAX Load -->
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="ptit-spinner mx-auto"></div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <nav id="room-pagination" class="py-3 d-none">
            <ul class="pagination justify-content-center mb-0">
                <!-- AJAX Load -->
            </ul>
        </nav>
    </div>
</div>


<!-- Room Modal -->
<div class="modal fade" id="roomModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="roomModalLabel">Thêm phòng mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="roomForm">
                    <input type="hidden" name="id" id="roomId">
                    <input type="hidden" name="action" id="roomAction" value="create">
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Tên / Số phòng</label>
                        <input type="text" class="form-control" name="room_number" id="roomNumber" placeholder="VD: P301, LAB-A" required>
                    </div>
                    
                    <div class="row g-3">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-muted">Tầng</label>
                            <input type="number" class="form-control" name="floor" id="roomFloor" value="1" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-muted">Sức chứa (Người)</label>
                            <input type="number" class="form-control" name="capacity" id="roomCapacity" value="40" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Loại phòng học</label>
                        <select class="form-select" name="room_type" id="roomType">
                            <option value="standard">Phòng học lý thuyết</option>
                            <option value="lab_pc">Phòng thực hành máy tính</option>
                            <option value="lab_server">Phòng Server Lab</option>
                        </select>
                    </div>

                    <div class="mb-0">
                        <label class="form-label small fw-bold text-muted">Trạng thái vận hành</label>
                        <select class="form-select" name="status" id="roomStatus">
                            <option value="available">Sẵn sàng (Available)</option>
                            <option value="maintenance">Đang bảo trì (Maintenance)</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-ptit px-4" id="btn-save-room">Lưu thông tin</button>
            </div>
        </div>
    </div>
</div>

<script>
    let trang_hien_tai = 1;

    /**
     * Làm mới form nhập liệu về trạng thái ban đầu (thêm mới)
     */
    function resetForm() {
        $('#roomForm')[0].reset();
        $('#roomId').val('');
        $('#roomAction').val('create');
        $('#roomModalLabel').text('Thêm phòng mới');
    }

    /**
     * Đưa thông tin phòng cần sửa vào Modal
     * @param {object} room - Đối tượng chứa thông tin phòng học
     */
    function sua_phong(room) {
        $('#roomId').val(room.id);
        $('#roomNumber').val(room.room_number);
        $('#roomFloor').val(room.floor);
        $('#roomCapacity').val(room.capacity);
        $('#roomType').val(room.room_type);
        $('#roomStatus').val(room.status);
        $('#roomAction').val('update');
        $('#roomModalLabel').text('Cập nhật phòng ' + room.room_number);
        new bootstrap.Modal(document.getElementById('roomModal')).show();
    }

    /**
     * Tải danh sách phòng học từ server
     * @param {number} page - Trang cần tải
     */
    function tai_danh_sach_phong(page = 1) {
        trang_hien_tai = page;
        const $body = $('#roomTableBody');
        const $nav = $('#room-pagination');
        
        // Hiện spinner khi đang tải
        $body.html('<tr><td colspan="6" class="text-center py-5"><div class="ptit-spinner mx-auto"></div></td></tr>');
        $nav.addClass('d-none');

        $.get(`backend/api/quan_ly_phong.php?page=${page}`, function(res) {
            if (res.status === 'success') {
                if (res.data.length === 0) {
                    $body.html('<tr><td colspan="6" class="text-center py-5 text-muted">Không có phòng nào.</td></tr>');
                    return;
                }

                let html = '';
                res.data.forEach(room => {
                    // Xử lý hiển thị loại phòng bằng Badge
                    let typeBadge = 'bg-light text-dark';
                    let typeName = room.room_type;
                    if(room.room_type === 'lab_pc') { typeBadge = 'bg-primary-soft text-primary'; typeName = 'Phòng Máy'; }
                    if(room.room_type === 'lab_server') { typeBadge = 'bg-ptit-red-soft text-ptit-red'; typeName = 'Server'; }

                    html += `
                        <tr id="room-row-${room.id}">
                            <td class="ps-4">
                                <div class="fw-bold text-dark fs-5">${room.room_number}</div>
                            </td>
                            <td><span class="small text-muted">Tầng ${room.floor}</span></td>
                            <td><span class="badge ${typeBadge}">${typeName}</span></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="fa-solid fa-users-rectangle me-2 opacity-50"></i>
                                    <span class="small fw-bold">${room.capacity}</span>
                                </div>
                            </td>
                            <td>
                                <div class="form-check form-switch">
                                    <input class="form-check-input status-toggle" type="checkbox" data-id="${room.id}" ${room.status === 'available' ? 'checked' : ''}>
                                    <span class="badge ${room.status === 'available' ? 'bg-success-soft text-success' : 'bg-warning-soft text-warning'} ms-2 status-label">
                                        ${room.status === 'available' ? 'Sẵn sàng' : 'Bảo trì'}
                                    </span>
                                </div>
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-light" onclick='sua_phong(${JSON.stringify(room)})'><i class="fa-solid fa-pen"></i></button>
                                    <button class="btn btn-sm btn-light text-danger" onclick="xoa_phong(${room.id})"><i class="fa-solid fa-trash-can"></i></button>
                                </div>
                            </td>
                        </tr>
                    `;
                });
                $body.html(html);
                renderPagination(res.pagination); // Vẽ bộ nút phân trang
                gắn_su_kien_toggle(); // Gán lại sự kiện cho các nút bật/tắt trạng thái
            }
        });
    }

    /**
     * Tạo bộ nút phân trang
     */
    function renderPagination(paging) {
        const $nav = $('#room-pagination');
        const $ul = $nav.find('ul');
        $ul.empty();

        if (paging.total_pages <= 1) return;
        $nav.removeClass('d-none');

        $ul.append(`<li class="page-item ${paging.current_page === 1 ? 'disabled' : ''}"><a class="page-link" href="#" onclick="tai_danh_sach_phong(${paging.current_page - 1}); return false;">Trước</a></li>`);
        for (let i = 1; i <= paging.total_pages; i++) {
            $ul.append(`<li class="page-item ${paging.current_page === i ? 'active' : ''}"><a class="page-link" href="#" onclick="tai_danh_sach_phong(${i}); return false;">${i}</a></li>`);
        }
        $ul.append(`<li class="page-item ${paging.current_page === paging.total_pages ? 'disabled' : ''}"><a class="page-link" href="#" onclick="tai_danh_sach_phong(${paging.current_page + 1}); return false;">Sau</a></li>`);
    }

    /**
     * Xử lý bật/tắt nhanh trạng thái hoạt động của phòng (Sẵn sàng / Bảo trì)
     */
    function gắn_su_kien_toggle() {
        $('.status-toggle').off('change').on('change', function() {
            const id = $(this).data('id');
            const checked = $(this).is(':checked');
            const status = checked ? 'available' : 'maintenance';
            const $label = $(this).siblings('.status-label');

            $.post('backend/api/doi_trang_thai_phong.php', { room_id: id, status: status }, function(res) {
                if(res.status === 'success') {
                    showToast('Cập nhật trạng thái thành công');
                    // Cập nhật giao diện Badge ngay lập tức mà không cần load lại trang
                    if(checked) {
                        $label.removeClass('bg-warning-soft text-warning').addClass('bg-success-soft text-success').text('Sẵn sàng');
                    } else {
                        $label.removeClass('bg-success-soft text-success').addClass('bg-warning-soft text-warning').text('Bảo trì');
                    }
                }
            }, 'json');
        });
    }

    $(document).ready(function() {
        tai_danh_sach_phong(1); // Tải danh sách phòng trang 1

        // Xử lý gửi form (Thêm mới hoặc Cập nhật)
        $('#btn-save-room').on('click', function() {
            const formData = $('#roomForm').serialize();
            const $btn = $(this);
            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');

            $.post('backend/api/quan_ly_phong.php', formData, function(res) {
                if(res.status === 'success') {
                    showToast('Đã lưu thay đổi thành công!', 'success');
                    bootstrap.Modal.getInstance(document.getElementById('roomModal')).hide(); // Đóng modal
                    tai_danh_sach_phong(trang_hien_tai); // Tải lại danh sách
                    $btn.prop('disabled', false).text('Lưu thông tin');
                } else {
                    showToast(res.message, 'error');
                    $btn.prop('disabled', false).text('Lưu thông tin');
                }
            }, 'json');
        });
    });

    /**
     * Xử lý xóa phòng học
     * @param {number} id - ID của phòng học cần xóa
     */
    function xoa_phong(id) {
        if(!confirm('Xác nhận xóa phòng này? Tất cả dữ liệu liên quan sẽ bị mất.')) return;
        $.post('backend/api/quan_ly_phong.php', { action: 'delete', id: id }, function(res) {
            if(res.status === 'success') {
                showToast('Đã xóa phòng học', 'success');
                tai_danh_sach_phong(trang_hien_tai);
            }
        }, 'json');
    }
</script>


<?php require_once 'frontend/includes/footer.php'; ?>

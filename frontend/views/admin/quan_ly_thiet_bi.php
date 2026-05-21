<?php require_once 'frontend/includes/header.php'; ?>

<div class="container-fluid py-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6 text-center text-md-start">
            <h3 class="fw-bold mb-1">Quản lý thiết bị</h3>
            <p class="text-muted small mb-0">Theo dõi trạng thái và phân bổ trang thiết bị phòng Lab</p>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <button class="btn btn-ptit shadow-sm py-2 px-4" data-bs-toggle="modal" data-bs-target="#equipModal" onclick="resetEquipForm()">
                <i class="fa-solid fa-plus me-2"></i>Thêm thiết bị
            </button>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="ptit-card p-3 mb-4 bg-light">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                    <input type="text" class="form-control border-start-0 ps-0" id="equipSearch" placeholder="Tìm theo tên hoặc mã thiết bị...">
                </div>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="filterStatus">
                    <option value="all">Tất cả trạng thái</option>
                    <option value="good">Hoạt động tốt</option>
                    <option value="broken">Báo hỏng</option>
                    <option value="repairing">Đang sửa chữa</option>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="filterRoom">
                    <option value="all">Tất cả phòng</option>
                    <?php if(isset($rooms)): foreach ($rooms as $r): ?>
                        <option value="<?= $r['id'] ?>"><?= $r['room_number'] ?></option>
                    <?php endforeach; endif; ?>
                </select>
            </div>
        </div>
    </div>

    <div class="ptit-card p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Thiết bị</th>
                        <th>Mã TB</th>
                        <th>Phòng</th>
                        <th>Loại</th>
                        <th>Trạng thái</th>
                        <th class="text-end pe-4">Thao tác</th>
                    </tr>
                </thead>
                <tbody id="equipTableBody">
                    <!-- AJAX Load -->
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="ptit-spinner mx-auto"></div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <nav id="equip-pagination" class="py-3 d-none">
            <ul class="pagination justify-content-center mb-0">
                <!-- AJAX Load -->
            </ul>
        </nav>
    </div>
</div>

<!-- Equip Modal -->
<div class="modal fade" id="equipModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="equipModalLabel">Thêm thiết bị mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="equipForm">
                    <input type="hidden" name="id" id="equipId">
                    <input type="hidden" name="action" id="equipAction" value="create">
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Tên thiết bị</label>
                        <input type="text" class="form-control" name="equipment_name" id="equipName" placeholder="VD: PC Dell Optiplex, Monitor Samsung..." required>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-muted">Mã định danh (Code)</label>
                            <input type="text" class="form-control" name="equipment_code" id="equipCode" placeholder="VD: A3-PC-01" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-muted">Phòng gán</label>
                            <select class="form-select" name="room_id" id="equipRoom">
                                <?php if(isset($rooms)): foreach ($rooms as $r): ?>
                                    <option value="<?= $r['id'] ?>"><?= $r['room_number'] ?></option>
                                <?php endforeach; endif; ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Phân loại</label>
                        <select class="form-select" name="category" id="equipCategory">
                            <option value="pc">Máy tính để bàn</option>
                            <option value="monitor">Màn hình</option>
                            <option value="network">Thiết bị mạng</option>
                            <option value="other">Khác</option>
                        </select>
                    </div>

                    <div class="mb-0">
                        <label class="form-label small fw-bold text-muted">Tình trạng</label>
                        <select class="form-select" name="status" id="equipStatus">
                            <option value="good">Hoạt động tốt</option>
                            <option value="broken">Hỏng hóc</option>
                            <option value="repairing">Đang sửa chữa</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-ptit px-4" id="btn-save-equip">Lưu thiết bị</button>
            </div>
        </div>
    </div>
</div>

<script>
    let trang_hien_tai = 1;

    /**
     * Làm mới form nhập liệu thiết bị về trạng thái thêm mới
     */
    function resetEquipForm() {
        $('#equipForm')[0].reset();
        $('#equipId').val('');
        $('#equipAction').val('create');
        $('#equipModalLabel').text('Thêm thiết bị mới');
    }

    /**
     * Đưa thông tin thiết bị vào Modal để chỉnh sửa
     * @param {object} equip - Đối tượng chứa thông tin thiết bị
     */
    function sua_thiet_bi(equip) {
        $('#equipId').val(equip.id);
        $('#equipName').val(equip.name);
        $('#equipCode').val(equip.equipment_code);
        $('#equipRoom').val(equip.room_id);
        $('#equipCategory').val(equip.category);
        $('#equipStatus').val(equip.status);
        $('#equipAction').val('update');
        $('#equipModalLabel').text('Cập nhật ' + equip.name);
        new bootstrap.Modal(document.getElementById('equipModal')).show();
    }

    /**
     * Tải danh sách thiết bị từ server với các bộ lọc tìm kiếm
     * @param {number} page - Trang cần tải
     */
    function tai_danh_sach_thiet_bi(page = 1) {
        trang_hien_tai = page;
        const $body = $('#equipTableBody');
        const $nav = $('#equip-pagination');
        const searchVal = $('#equipSearch').val();
        const statusVal = $('#filterStatus').val();
        const roomVal = $('#filterRoom').val();

        // Hiển thị hiệu ứng đang tải
        $body.html('<tr><td colspan="6" class="text-center py-5"><div class="ptit-spinner mx-auto"></div></td></tr>');
        $nav.addClass('d-none');

        // Gửi yêu cầu GET kèm theo từ khóa tìm kiếm và các bộ lọc
        $.get(`backend/api/quan_ly_thiet_bi.php?action=list&page=${page}&q=${searchVal}&status=${statusVal}&room_id=${roomVal}`, function(res) {
            if (res.status === 'success') {
                if (res.data.length === 0) {
                    $body.html('<tr><td colspan="6" class="text-center py-5 text-muted">Không tìm thấy thiết bị nào.</td></tr>');
                    return;
                }

                let html = '';
                res.data.forEach(e => {
                    // Xác định màu sắc và văn bản cho Badge tình trạng
                    let status = e.condition_status ?? 'good';
                    let statusClass = 'bg-success-soft text-success';
                    let statusText = 'Tốt';
                    if(status === 'broken') { statusClass = 'bg-danger-soft text-danger'; statusText = 'Hỏng'; }
                    if(status === 'repairing') { statusClass = 'bg-warning-soft text-warning'; statusText = 'Sửa...'; }

                    html += `
                        <tr id="equip-row-${e.id}">
                            <td class="ps-4">
                                <div class="fw-bold text-dark">${e.name ?? 'Thiết bị không tên'}</div>
                                <div class="extra-small text-muted">Ngày nhập: ${e.purchase_date ?? '---'}</div>
                            </td>
                            <td><code class="text-ptit-red fw-bold">${e.equipment_code ?? '---'}</code></td>
                            <td><span class="badge bg-light text-dark border">${e.room_number ?? 'Chưa gán'}</span></td>
                            <td><small class="text-muted">${e.category ?? 'Khác'}</small></td>
                            <td><span class="badge ${statusClass}">${statusText}</span></td>
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-light" onclick='sua_thiet_bi(${JSON.stringify({
                                        id: e.id,
                                        name: e.name,
                                        equipment_code: e.equipment_code,
                                        room_id: e.room_id,
                                        category: e.category,
                                        status: e.condition_status
                                    })})'><i class="fa-solid fa-pen"></i></button>
                                    <button class="btn btn-sm btn-light text-danger" onclick="xoa_thiet_bi(${e.id})"><i class="fa-solid fa-trash-can"></i></button>
                                </div>
                            </td>
                        </tr>
                    `;
                });
                $body.html(html);
                renderPagination(res.pagination); // Vẽ bộ nút phân trang
            }
        });
    }

    /**
     * Tạo bộ nút phân trang cho danh sách thiết bị
     */
    function renderPagination(paging) {
        const $nav = $('#equip-pagination');
        const $ul = $nav.find('ul');
        $ul.empty();

        if (paging.total_pages <= 1) return;
        $nav.removeClass('d-none');

        $ul.append(`<li class="page-item ${paging.current_page === 1 ? 'disabled' : ''}"><a class="page-link" href="#" onclick="tai_danh_sach_thiet_bi(${paging.current_page - 1}); return false;">Trước</a></li>`);
        for (let i = 1; i <= paging.total_pages; i++) {
            $ul.append(`<li class="page-item ${paging.current_page === i ? 'active' : ''}"><a class="page-link" href="#" onclick="tai_danh_sach_thiet_bi(${i}); return false;">${i}</a></li>`);
        }
        $ul.append(`<li class="page-item ${paging.current_page === paging.total_pages ? 'disabled' : ''}"><a class="page-link" href="#" onclick="tai_danh_sach_thiet_bi(${paging.current_page + 1}); return false;">Sau</a></li>`);
    }

    $(document).ready(function() {
        tai_danh_sach_thiet_bi(1); // Tải trang đầu tiên

        // Xử lý gửi form lưu thiết bị (Thêm mới hoặc Cập nhật)
        $('#btn-save-equip').on('click', function() {
            const formData = $('#equipForm').serialize();
            $.post('backend/api/quan_ly_thiet_bi.php', formData, function(res) {
                if(res.status === 'success') {
                    showToast('Đã lưu thiết bị thành công', 'success');
                    bootstrap.Modal.getInstance(document.getElementById('equipModal')).hide(); // Đóng modal
                    tai_danh_sach_thiet_bi(trang_hien_tai); // Tải lại danh sách
                } else {
                    showToast(res.message, 'error');
                }
            }, 'json');
        });

        // Xử lý tìm kiếm thời gian thực (Debounce)
        let searchTimer;
        $('#equipSearch').on('keyup', function() {
            clearTimeout(searchTimer);
            // Chỉ thực hiện tìm kiếm sau khi người dùng ngừng gõ 300ms để giảm tải cho server
            searchTimer = setTimeout(() => tai_danh_sach_thiet_bi(1), 300);
        });

        // Tải lại danh sách khi thay đổi bộ lọc Trạng thái hoặc Phòng
        $('#filterStatus, #filterRoom').on('change', function() {
            tai_danh_sach_thiet_bi(1);
        });
    });

    /**
     * Xử lý xóa thiết bị khỏi hệ thống
     * @param {number} id - ID thiết bị
     */
    function xoa_thiet_bi(id) {
        if(!confirm('Xóa thiết bị này khỏi hệ thống?')) return;
        $.post('backend/api/quan_ly_thiet_bi.php', { action: 'delete', id: id }, function(res) {
            if(res.status === 'success') {
                showToast('Đã xóa thiết bị');
                tai_danh_sach_thiet_bi(trang_hien_tai);
            }
        }, 'json');
    }
</script>


<?php require_once 'frontend/includes/footer.php'; ?>

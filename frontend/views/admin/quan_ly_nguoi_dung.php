<?php require_once 'frontend/includes/header.php'; ?>

<div class="container-fluid py-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6 text-center text-md-start">
            <h3 class="fw-bold mb-1">Quản lý người dùng</h3>
            <p class="text-muted small mb-0">Quản lý tài khoản Sinh viên, Giảng viên và Admin</p>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <button class="btn btn-ptit shadow-sm py-2 px-4" data-bs-toggle="modal" data-bs-target="#userModal" onclick="resetUserForm()">
                <i class="fa-solid fa-user-plus me-2"></i>Thêm người dùng
            </button>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="ptit-card p-3 mb-4 bg-light">
        <div class="row g-3">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                    <input type="text" class="form-control border-start-0 ps-0" id="userSearch" placeholder="Tìm theo tên, mã sinh viên hoặc email...">
                </div>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="filterRole">
                    <option value="all">Tất cả quyền hạn</option>
                    <?php foreach ($roles as $role): ?>
                        <option value="<?= $role['id'] ?>"><?= $role['role_name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>

    <div class="ptit-card p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Người dùng</th>
                        <th>Mã định danh</th>
                        <th>Email</th>
                        <th>Quyền hạn</th>
                        <th>Trạng thái</th>
                        <th class="text-end pe-4">Thao tác</th>
                    </tr>
                </thead>
                <tbody id="userTableBody">
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="ptit-spinner mx-auto"></div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <nav id="user-pagination" class="py-3 d-none">
            <ul class="pagination justify-content-center mb-0">
                <!-- AJAX Load -->
            </ul>
        </nav>
    </div>
</div>

<!-- User Modal -->
<div class="modal fade" id="userModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="userModalLabel">Thêm người dùng mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="userForm">
                    <input type="hidden" name="id" id="userId">
                    <input type="hidden" name="action" id="userAction" value="create">
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Họ và tên</label>
                        <input type="text" class="form-control" name="fullname" id="userFullname" placeholder="VD: Nguyễn Văn A" required>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-muted">Mã SV / Giảng viên</label>
                            <input type="text" class="form-control" name="username" id="userUsername" placeholder="VD: B21DCCN001" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-muted">Email</label>
                            <input type="email" class="form-control" name="email" id="userEmail" placeholder="example@ptit.edu.vn" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Mật khẩu</label>
                        <input type="password" class="form-control" name="password" id="userPassword" placeholder="Nhập mật khẩu (để trống nếu không đổi)">
                        <small class="text-muted extra-small" id="passHelp">Mật khẩu mặc định là mã định danh nếu để trống khi tạo mới.</small>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-muted">Quyền hạn</label>
                            <select class="form-select" name="role_id" id="userRole">
                                <?php foreach ($roles as $role): ?>
                                    <option value="<?= $role['id'] ?>"><?= $role['role_name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-muted">Trạng thái</label>
                            <select class="form-select" name="status" id="userStatus">
                                <option value="active">Hoạt động</option>
                                <option value="locked">Bị khóa</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-ptit px-4" id="btn-save-user">Lưu thông tin</button>
            </div>
        </div>
    </div>
</div>

<script>
    let trang_hien_tai = 1;

    function resetUserForm() {
        $('#userForm')[0].reset();
        $('#userId').val('');
        $('#userAction').val('create');
        $('#userModalLabel').text('Thêm người dùng mới');
        $('#userUsername').prop('readonly', false);
        $('#passHelp').text('Mật khẩu mặc định là mã định danh nếu để trống khi tạo mới.');
    }

    function sua_nguoi_dung(user) {
        $('#userId').val(user.id);
        $('#userFullname').val(user.fullname);
        $('#userUsername').val(user.username).prop('readonly', true);
        $('#userEmail').val(user.email);
        $('#userRole').val(user.role_id);
        $('#userStatus').val(user.status);
        $('#userAction').val('update');
        $('#userModalLabel').text('Cập nhật: ' + user.fullname);
        $('#passHelp').text('Để trống nếu không muốn thay đổi mật khẩu.');
        new bootstrap.Modal(document.getElementById('userModal')).show();
    }

    function tai_danh_sach_nguoi_dung(page = 1) {
        trang_hien_tai = page;
        const $body = $('#userTableBody');
        const $nav = $('#user-pagination');
        const search = $('#userSearch').val();
        const role = $('#filterRole').val();

        $body.html('<tr><td colspan="6" class="text-center py-5"><div class="ptit-spinner mx-auto"></div></td></tr>');
        $nav.addClass('d-none');

        $.get(`backend/api/quan_ly_nguoi_dung.php?action=list&page=${page}&q=${search}&role_id=${role}`, function(res) {
            if (res.status === 'success') {
                if (res.data.length === 0) {
                    $body.html('<tr><td colspan="6" class="text-center py-5 text-muted">Không tìm thấy người dùng nào.</td></tr>');
                    return;
                }

                let html = '';
                res.data.forEach(u => {
                    let roleBadge = 'bg-light text-dark';
                    if(u.role_name === 'admin') roleBadge = 'bg-danger-soft text-ptit-red';
                    if(u.role_name === 'lecturer') roleBadge = 'bg-primary-soft text-primary';
                    if(u.role_name === 'student') roleBadge = 'bg-success-soft text-success';

                    html += `
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark">${u.fullname}</div>
                                <div class="extra-small text-muted">ID: #${u.id}</div>
                            </td>
                            <td><code class="fw-bold">${u.username}</code></td>
                            <td><small>${u.email}</small></td>
                            <td><span class="badge ${roleBadge}">${u.role_name}</span></td>
                            <td>
                                <span class="badge ${u.status === 'active' ? 'bg-success' : 'bg-secondary'}">
                                    ${u.status === 'active' ? 'Hoạt động' : 'Đã khóa'}
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-light" onclick='sua_nguoi_dung(${JSON.stringify(u)})'><i class="fa-solid fa-pen-to-square"></i></button>
                                    <button class="btn btn-sm btn-light text-danger" onclick="xoa_nguoi_dung(${u.id})"><i class="fa-solid fa-trash-can"></i></button>
                                </div>
                            </td>
                        </tr>
                    `;
                });
                $body.html(html);
                renderPagination(res.pagination);
            }
        });
    }

    function renderPagination(paging) {
        const $nav = $('#user-pagination');
        const $ul = $nav.find('ul');
        $ul.empty();
        if (paging.total_pages <= 1) return;
        $nav.removeClass('d-none');
        $ul.append(`<li class="page-item ${paging.current_page === 1 ? 'disabled' : ''}"><a class="page-link" href="#" onclick="tai_danh_sach_nguoi_dung(${paging.current_page - 1}); return false;">Trước</a></li>`);
        for (let i = 1; i <= paging.total_pages; i++) {
            $ul.append(`<li class="page-item ${paging.current_page === i ? 'active' : ''}"><a class="page-link" href="#" onclick="tai_danh_sach_nguoi_dung(${i}); return false;">${i}</a></li>`);
        }
        $ul.append(`<li class="page-item ${paging.current_page === paging.total_pages ? 'disabled' : ''}"><a class="page-link" href="#" onclick="tai_danh_sach_nguoi_dung(${paging.current_page + 1}); return false;">Sau</a></li>`);
    }

    $(document).ready(function() {
        tai_danh_sach_nguoi_dung(1);

        $('#btn-save-user').on('click', function() {
            const formData = $('#userForm').serialize();
            const $btn = $(this);
            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');

            $.post('backend/api/quan_ly_nguoi_dung.php', formData, function(res) {
                if(res.status === 'success') {
                    showToast(res.message, 'success');
                    bootstrap.Modal.getInstance(document.getElementById('userModal')).hide();
                    tai_danh_sach_nguoi_dung(trang_hien_tai);
                } else {
                    showToast(res.message, 'error');
                }
                $btn.prop('disabled', false).text('Lưu thông tin');
            }, 'json');
        });

        let searchTimer;
        $('#userSearch').on('keyup', function() {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => tai_danh_sach_nguoi_dung(1), 300);
        });

        $('#filterRole').on('change', () => tai_danh_sach_nguoi_dung(1));
    });

    function xoa_nguoi_dung(id) {
        if(!confirm('Xác nhận xóa người dùng này? Thao tác không thể hoàn tác.')) return;
        $.post('backend/api/quan_ly_nguoi_dung.php', { action: 'delete', id: id }, function(res) {
            if(res.status === 'success') {
                showToast(res.message, 'success');
                tai_danh_sach_nguoi_dung(trang_hien_tai);
            } else {
                showToast(res.message, 'error');
            }
        }, 'json');
    }
</script>

<?php require_once 'frontend/includes/footer.php'; ?>

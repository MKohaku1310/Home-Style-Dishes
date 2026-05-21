<?php require_once 'frontend/includes/header.php'; ?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Thông báo của bạn</h2>
            <p class="text-muted">Xem và quản lý các cập nhật từ hệ thống</p>
        </div>
        <button class="btn btn-ptit-outline" onclick="markAllRead()">
            <i class="fa-solid fa-check-double me-2"></i>Đánh dấu tất cả đã đọc
        </button>
    </div>

    <div class="ptit-card">
        <div id="all-notifications-list">
            <!-- AJAX Load -->
            <div class="p-5 text-center">
                <div class="ptit-spinner mx-auto"></div>
                <p class="mt-3 text-muted">Đang tải thông báo...</p>
            </div>
        </div>
    </div>
</div>

<script>
    /**
     * Tải danh sách tất cả thông báo của người dùng từ server
     */
    function loadFullNotifList() {
        $.get('backend/api/thong_bao.php?action=list&limit=50', function(res) {
            const $list = $('#all-notifications-list');
            if (res.status === 'success' && res.data.length > 0) {
                let html = '';
                res.data.forEach(n => {
                    // Nếu chưa đọc thì thêm viền đỏ và nền xám nhạt để phân biệt
                    const unreadClass = n.is_read ? '' : 'border-start border-5 border-danger bg-light';
                    html += `
                        <div class="p-4 border-bottom d-flex justify-content-between align-items-start ${unreadClass}">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <h6 class="fw-bold mb-0">${n.title}</h6>
                                    ${!n.is_read ? '<span class="badge bg-danger rounded-pill" style="font-size: 0.6rem;">Mới</span>' : ''}
                                </div>
                                <div class="text-muted mb-2">${n.content}</div>
                                <div class="small text-muted"><i class="fa-regular fa-clock me-1"></i>${n.created_at}</div>
                            </div>
                            <div class="ms-3">
                                ${!n.is_read ? `
                                    <button class="btn btn-sm btn-light border" onclick="markReadAndReload(${n.id})" title="Đánh dấu đã đọc">
                                        <i class="fa-solid fa-check"></i>
                                    </button>
                                ` : ''}
                            </div>
                        </div>
                    `;
                });
                $list.html(html);
            } else {
                // Hiển thị trạng thái trống
                $list.html(`
                    <div class="p-5 text-center text-muted">
                        <i class="fa-solid fa-bell-slash fs-1 mb-3 opacity-25"></i>
                        <p>Bạn không có thông báo nào.</p>
                    </div>
                `);
            }
        });
    }

    /**
     * Đánh dấu một thông báo là đã đọc và tải lại danh sách
     * @param {number} id - ID của thông báo
     */
    function markReadAndReload(id) {
        $.post('backend/api/thong_bao.php?action=mark_read', { id: id }, function(res) {
            if (res.status === 'success') {
                loadFullNotifList(); // Load lại trang thông báo này
                fetchNotifications(); // Cập nhật lại số lượng thông báo trên thanh Header (hàm này khai báo ở header.php)
            }
        });
    }

    $(document).ready(function() {
        loadFullNotifList(); // Chạy ngay khi trang load xong
    });
</script>

<?php require_once 'frontend/includes/footer.php'; ?>

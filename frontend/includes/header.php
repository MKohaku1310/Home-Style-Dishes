<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PTIT Room Booking - Hệ thống Quản lý Phòng học A3</title>
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="frontend/assets/css/style.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body>
    <!-- Loading Overlay -->
    <div id="loading-overlay">
        <div class="ptit-spinner"></div>
    </div>

    <div class="wrapper">
        <?php 
        // Kiểm tra nếu người dùng đã đăng nhập (có session user_id)
        if(isset($_SESSION['user_id'])): 
            // Lấy thông tin trang hiện tại từ URL để đánh dấu menu active
            $page = $_GET['page'] ?? 'trang_chu';
            $action = $_GET['action'] ?? 'index';
            // Kiểm tra quyền Admin dựa trên session role_name
            $isAdmin = ($_SESSION['role_name'] ?? '') === 'admin';
        ?>
        <!-- Sidebar -->
        <nav id="sidebar" style="border-right: 2px solid var(--ptit-red);">
            <div class="sidebar-header" style="background: var(--ptit-red); border-bottom: none; padding: 1.5rem 1rem;">
                <a href="index.php?page=trang_chu" class="text-decoration-none d-block">
                    <img src="https://info.nhonam.io.vn/images/Logo-PTIT@2x.png" alt="PTIT Logo" class="navbar-logo mb-2" style="height: 65px; max-width: 100%; object-fit: contain;">
                    <div class="fw-bold text-white mb-0" style="font-size: 0.75rem; letter-spacing: 0.5px; line-height: 1.2;">Hệ thống Quản lý<br>Phòng thực hành A3</div>
                </a>
            </div>
            <ul class="sidebar-menu">
                <li class="<?= ($page == 'trang_chu') ? 'active' : '' ?>">
                    <a href="index.php?page=trang_chu"><i class="fa-solid fa-house"></i> <span>Trang chủ</span></a>
                </li>
                <li class="<?= ($page == 'nguoi_dung' && $action == 'dat_phong') ? 'active' : '' ?>">
                    <a href="index.php?page=nguoi_dung&action=dat_phong"><i class="fa-solid fa-calendar-plus"></i> <span>Đăng ký mượn</span></a>
                </li>
                <li class="<?= ($page == 'nguoi_dung' && $action == 'lich_su') ? 'active' : '' ?>">
                    <a href="index.php?page=nguoi_dung&action=lich_su"><i class="fa-solid fa-clock-rotate-left"></i> <span>Lịch sử đặt</span></a>
                </li>
                <li class="<?= ($page == 'nguoi_dung' && $action == 'bao_hong') ? 'active' : '' ?>">
                    <a href="index.php?page=nguoi_dung&action=bao_hong"><i class="fa-solid fa-screwdriver-wrench"></i> <span>Báo hỏng thiết bị</span></a>
                </li>
                
                <?php if($isAdmin): ?>
                <div class="sidebar-section-header px-4 py-3 small text-uppercase text-muted fw-bold" style="font-size: 0.65rem; letter-spacing: 1px;">Quản trị hệ thống</div>
                <li class="<?= ($page == 'quan_tri' && $action == 'bang_dieu_khien') ? 'active' : '' ?>">
                    <a href="index.php?page=quan_tri&action=bang_dieu_khien"><i class="fa-solid fa-chart-line"></i> <span>Dashboard</span></a>
                </li>
                <li class="<?= ($page == 'quan_tri' && $action == 'lich_muon_phong') ? 'active' : '' ?>">
                    <a href="index.php?page=quan_tri&action=lich_muon_phong"><i class="fa-solid fa-calendar-days"></i> <span>Lịch mượn phòng</span></a>
                </li>
                <li class="<?= ($page == 'quan_tri' && $action == 'duyet_dat_phong') ? 'active' : '' ?>">
                    <a href="index.php?page=quan_tri&action=duyet_dat_phong"><i class="fa-solid fa-check-to-slot"></i> <span>Duyệt đặt phòng</span></a>
                </li>
                <li class="<?= ($page == 'quan_tri' && $action == 'quan_ly_phong') ? 'active' : '' ?>">
                    <a href="index.php?page=quan_tri&action=quan_ly_phong"><i class="fa-solid fa-door-open"></i> <span>Quản lý phòng</span></a>
                </li>
                <li class="<?= ($page == 'quan_tri' && $action == 'quan_ly_thiet_bi') ? 'active' : '' ?>">
                    <a href="index.php?page=quan_tri&action=quan_ly_thiet_bi"><i class="fa-solid fa-microchip"></i> <span>Quản lý thiết bị</span></a>
                </li>
                <li class="<?= ($page == 'quan_tri' && $action == 'quan_ly_bao_hong') ? 'active' : '' ?>">
                    <a href="index.php?page=quan_tri&action=quan_ly_bao_hong"><i class="fa-solid fa-triangle-exclamation"></i> <span>Quản lý báo hỏng</span></a>
                </li>
                <?php endif; ?>
                
                <li class="mt-4">
                    <a href="index.php?page=xac_thuc&action=dang_xuat" class="text-danger"><i class="fa-solid fa-power-off"></i> <span>Đăng xuất</span></a>
                </li>
            </ul>
        </nav>
        <?php endif; ?>

        <!-- Main Content Area -->
        <main class="main-content <?= !isset($_SESSION['user_id']) ? 'ms-0' : '' ?>" id="main-panel">
            <?php if(isset($_SESSION['user_id'])): ?>
            <header class="top-navbar mb-4 justify-content-between">
                <div class="d-flex align-items-center">
                    <button id="toggleSidebar" class="btn btn-light rounded-circle me-3"><i class="fa-solid fa-bars-staggered"></i></button>
                    <div class="d-none d-md-block">
                        <h5 class="mb-0 fw-bold">A3 PTIT <span class="text-muted fw-normal fs-6 ms-2">| Hệ thống quản lý phòng học</span></h5>
                    </div>
                </div>
                
                <div class="d-flex align-items-center gap-4">
                    <!-- Notification Bell -->
                    <div class="dropdown">
                        <div class="notif-bell" id="notifDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-bell"></i>
                            <span class="notif-badge" id="unread-count" style="display: none;">0</span>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-0 mt-3" style="width: 320px; border-radius: 12px; overflow: hidden;">
                            <li class="p-3 border-bottom d-flex justify-content-between align-items-center bg-light">
                                <span class="fw-bold">Thông báo</span>
                                <button class="btn btn-sm text-ptit-red p-0 small" onclick="markAllRead()">Đánh dấu đã đọc</button>
                            </li>
                            <div id="notif-list" style="max-height: 350px; overflow-y: auto;">
                                <!-- AJAX Load -->
                                <div class="p-4 text-center text-muted small">Đang tải thông báo...</div>
                            </div>
                            <li class="text-center p-2 border-top">
                                <a href="index.php?page=nguoi_dung&action=thong_bao" class="small text-ptit-red fw-bold text-decoration-none">Xem tất cả thông báo</a>
                            </li>
                        </ul>
                    </div>

                    <!-- User Profile -->
                    <div class="dropdown">
                        <div class="d-flex align-items-center gap-2 cursor-pointer" data-bs-toggle="dropdown">
                            <div class="user-avatar-circle">
                                <?= strtoupper(substr($_SESSION['fullname'] ?? 'U', 0, 1)) ?>
                            </div>
                            <div class="d-none d-lg-block">
                                <div class="fw-bold lh-1" style="font-size: 0.9rem;"><?= htmlspecialchars($_SESSION['fullname'] ?? 'User') ?></div>
                                <small class="text-muted"><?= htmlspecialchars($_SESSION['role_name'] ?? 'Guest') ?></small>
                            </div>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" style="border-radius: 10px;">
                            <li><a class="dropdown-item py-2" href="index.php?page=nguoi_dung&action=ho_so"><i class="fa-solid fa-id-card me-2"></i> Hồ sơ cá nhân</a></li>
                            <li><a class="dropdown-item py-2" href="index.php?page=nguoi_dung&action=doi_mat_khau"><i class="fa-solid fa-shield-halved me-2"></i> Đổi mật khẩu</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item py-2 text-danger" href="index.php?page=xac_thuc&action=dang_xuat"><i class="fa-solid fa-power-off me-2"></i> Đăng xuất</a></li>
                        </ul>
                    </div>
                </div>
            </header>
            <?php endif; ?>

            <div class="toast-container-ptit" id="toast-wrapper"></div>

            <!-- Scripts for Header (Notif Polling) -->
            <script>
                /**
                 * Hiển thị thông báo Toast nhanh ở góc màn hình
                 * @param {string} message - Nội dung thông báo
                 * @param {string} type - Loại thông báo ('success' hoặc 'error')
                 */
                function showToast(message, type = 'success') {
                    const icon = type === 'success' ? 'fa-circle-check text-success' : 'fa-circle-xmark text-danger';
                    const toastHtml = `
                        <div class="toast-ptit">
                            <i class="fa-solid ${icon} fs-4"></i>
                            <div class="fw-medium">${message}</div>
                        </div>
                    `;
                    const $toast = $(toastHtml).appendTo('#toast-wrapper');
                    // Tự động biến mất sau 4 giây
                    setTimeout(() => {
                        $toast.fadeOut(400, function() { $(this).remove(); });
                    }, 4000);
                }

                /**
                 * Đánh dấu một thông báo là đã đọc
                 * @param {number} id - ID của thông báo
                 */
                function markRead(id) {
                    $.post('backend/api/thong_bao.php?action=mark_read', { id: id }, function(res) {
                        if (res.status === 'success') {
                            fetchNotifications(); // Cập nhật lại số lượng thông báo chưa đọc
                            loadNotifList();    // Cập nhật lại danh sách trong dropdown
                        }
                    });
                }

                /**
                 * Đánh dấu tất cả thông báo là đã đọc
                 */
                function markAllRead() {
                    $.post('backend/api/thong_bao.php?action=mark_read', {}, function(res) {
                        if (res.status === 'success') {
                            showToast('Đã đánh dấu tất cả là đã đọc');
                            fetchNotifications();
                            loadNotifList();
                            // Nếu đang ở trang danh sách thông báo đầy đủ thì reload lại trang đó
                            if (typeof loadFullNotifList === 'function') {
                                loadFullNotifList();
                            }
                        }
                    });
                }

                /**
                 * Lấy số lượng thông báo chưa đọc từ server
                 */
                function fetchNotifications() {
                    // Không gọi API nếu tab trình duyệt đang ẩn để tiết kiệm tài nguyên
                    if (document.hidden) return; 

                    $.get('backend/api/thong_bao.php?action=get_count', function(res) {
                        if (res.status === 'success') {
                            const count = res.count;
                            const $badge = $('#unread-count');
                            if (count > 0) {
                                $badge.text(count).show();
                                // Nếu người dùng đang mở menu thông báo thì load lại danh sách luôn
                                if ($('#notifDropdown').attr('aria-expanded') === 'true') {
                                    loadNotifList();
                                }
                            } else {
                                $badge.hide();
                            }
                        }
                    });
                }

                /**
                 * Tải danh sách thông báo để hiển thị trong dropdown
                 */
                function loadNotifList() {
                    $.get('backend/api/thong_bao.php?action=list', function(res) {
                        const $list = $('#notif-list');
                        if (res.status === 'success' && res.data.length > 0) {
                            let html = '';
                            res.data.forEach(n => {
                                html += `
                                    <div class="p-3 border-bottom notif-item ${n.is_read ? '' : 'bg-light'} cursor-pointer" onclick="markRead(${n.id})">
                                        <div class="fw-bold small mb-1">${n.title}</div>
                                        <div class="text-muted small">${n.content}</div>
                                        <div class="text-end extra-small text-muted mt-1" style="font-size: 0.7rem;">${n.created_at}</div>
                                    </div>
                                `;
                            });
                            $list.html(html);
                        } else {
                            $list.html('<div class="p-4 text-center text-muted small">Không có thông báo mới</div>');
                        }
                    });
                }

                $(document).ready(function() {
                    // Nếu đã đăng nhập thì mới bắt đầu chạy các hàm theo dõi thông báo
                    <?php if(isset($_SESSION['user_id'])): ?>
                    fetchNotifications();
                    // Thiết lập cơ chế "Long Polling" đơn giản: gọi lại sau mỗi 30 giây
                    setInterval(fetchNotifications, 30000); 
                    
                    // Sự kiện click vào chuông thông báo
                    $('#notifDropdown').on('click', function() {
                        loadNotifList();
                    });

                    // Xử lý thu phóng Sidebar (thanh menu bên trái)
                    $('#toggleSidebar').on('click', function() {
                        $('#sidebar').toggleClass('collapsed');
                        $('#main-panel').toggleClass('expanded');
                        // Nếu là màn hình nhỏ (mobile) thì dùng cơ chế active khác
                        if ($(window).width() <= 992) {
                            $('#sidebar').toggleClass('active');
                        }
                    });
                    <?php endif; ?>
                });
            </script>

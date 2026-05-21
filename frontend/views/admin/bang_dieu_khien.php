<?php require_once 'frontend/includes/header.php'; ?>

<div class="container-fluid py-2">
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="fw-bold mb-1">Tổng quan hệ thống</h3>
                    <p class="text-muted small mb-0">Theo dõi hoạt động tại Tòa nhà A3</p>
                </div>
                <div class="text-end">
                    <div id="admin-clock" class="fw-bold text-ptit-red fs-5">00:00:00</div>
                    <div class="small text-muted"><?= date('d/m/Y') ?></div>
                </div>
            </div>
        </div>

        <!-- 4 Stats Cards -->
        <div class="col-sm-6 col-xl-3">
            <div class="ptit-card p-4 h-100 border-start border-5 border-primary">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="card-icon-box m-0" style="background: rgba(13, 110, 253, 0.1); color: #0d6efd;">
                        <i class="fa-solid fa-door-open"></i>
                    </div>
                    <span class="badge bg-light text-muted small">Phòng học</span>
                </div>
                <h6 class="text-muted small fw-bold text-uppercase">Tổng số phòng</h6>
                <h2 class="fw-bold mb-0"><?= $totalRooms ?></h2>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="ptit-card p-4 h-100 border-start border-5 border-warning">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="card-icon-box m-0" style="background: rgba(255, 193, 7, 0.1); color: #ffc107;">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </div>
                    <span class="badge bg-warning text-dark">Theo dõi</span>
                </div>
                <h6 class="text-muted small fw-bold text-uppercase">Yêu cầu chờ duyệt</h6>
                <h2 class="fw-bold mb-0"><?= $pendingBookings ?></h2>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="ptit-card p-4 h-100 border-start border-5 border-success">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="card-icon-box m-0" style="background: rgba(25, 135, 84, 0.1); color: #198754;">
                        <i class="fa-solid fa-door-open"></i>
                    </div>
                    <span class="badge bg-light text-muted small"><?= $currentSessionName ?></span>
                </div>
                <h6 class="text-muted small fw-bold text-uppercase">Phòng đang sử dụng</h6>
                <h2 class="fw-bold mb-0"><?= $occupiedRooms ?> / <?= $totalRooms ?></h2>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="ptit-card p-4 h-100 border-start border-5 border-danger">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="card-icon-box m-0" style="background: rgba(220, 53, 69, 0.1); color: #dc3545;">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <span class="badge bg-danger">Khẩn cấp</span>
                </div>
                <h6 class="text-muted small fw-bold text-uppercase">Báo hỏng mới</h6>
                <h2 class="fw-bold mb-0"><?= $brokenEquipment ?></h2>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <!-- Chart Section -->
        <div class="col-lg-8">
            <div class="ptit-card p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0"><i class="fa-solid fa-chart-line me-2 text-ptit-red"></i>Xu hướng đăng ký 7 ngày qua</h5>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">Xem theo tuần</button>
                        <ul class="dropdown-menu shadow border-0">
                            <li><a class="dropdown-item small" href="#">Tuần này</a></li>
                            <li><a class="dropdown-item small" href="#">Tuần trước</a></li>
                        </ul>
                    </div>
                </div>
                <div style="height: 320px;">
                    <canvas id="adminChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-lg-4">
            <div class="ptit-card p-4">
                <h5 class="fw-bold mb-4">Lối tắt quản trị</h5>
                <div class="list-group list-group-flush">
                    <a href="index.php?page=quan_tri&action=duyet_dat_phong" class="list-group-item list-group-item-action d-flex align-items-center py-3 px-0 border-top-0">
                        <div class="bg-warning-soft text-warning rounded-circle p-2 me-3"><i class="fa-solid fa-check-to-slot"></i></div>
                        <div>
                            <div class="fw-bold small">Duyệt đặt phòng</div>
                            <div class="extra-small text-muted">Xử lý các yêu cầu mượn phòng</div>
                        </div>
                        <i class="fa-solid fa-chevron-right ms-auto extra-small text-muted"></i>
                    </a>
                    <a href="index.php?page=quan_tri&action=quan_ly_nguoi_dung" class="list-group-item list-group-item-action d-flex align-items-center py-3 px-0">
                        <div class="bg-info-soft text-info rounded-circle p-2 me-3"><i class="fa-solid fa-users-gear"></i></div>
                        <div>
                            <div class="fw-bold small">Quản lý người dùng</div>
                            <div class="extra-small text-muted">Quản lý tài khoản SV & Giảng viên</div>
                        </div>
                        <i class="fa-solid fa-chevron-right ms-auto extra-small text-muted"></i>
                    </a>
                    <a href="index.php?page=quan_tri&action=lich_muon_phong" class="list-group-item list-group-item-action d-flex align-items-center py-3 px-0">
                        <div class="bg-primary-soft text-primary rounded-circle p-2 me-3"><i class="fa-solid fa-calendar-days"></i></div>
                        <div>
                            <div class="fw-bold small">Lịch mượn phòng</div>
                            <div class="extra-small text-muted">Theo dõi lịch sử dụng theo ca</div>
                        </div>
                        <i class="fa-solid fa-chevron-right ms-auto extra-small text-muted"></i>
                    </a>
                    <a href="index.php?page=quan_tri&action=quan_ly_thiet_bi" class="list-group-item list-group-item-action d-flex align-items-center py-3 px-0">
                        <div class="bg-success-soft text-success rounded-circle p-2 me-3"><i class="fa-solid fa-tools"></i></div>
                        <div>
                            <div class="fw-bold small">Quản lý thiết bị</div>
                            <div class="extra-small text-muted">Theo dõi và sửa chữa phần cứng</div>
                        </div>
                        <i class="fa-solid fa-chevron-right ms-auto extra-small text-muted"></i>
                    </a>
                    <a href="backend/api/xuat_file_dat_phong.php" class="list-group-item list-group-item-action d-flex align-items-center py-3 px-0 border-bottom-0">
                        <div class="bg-danger-soft text-danger rounded-circle p-2 me-3"><i class="fa-solid fa-file-csv"></i></div>
                        <div>
                            <div class="fw-bold small">Xuất báo cáo</div>
                            <div class="extra-small text-muted">Tải dữ liệu đăng ký (.csv)</div>
                        </div>
                        <i class="fa-solid fa-chevron-right ms-auto extra-small text-muted"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function() {
        /**
         * Cập nhật đồng hồ thời gian thực trên giao diện Admin
         */
        function updateClock() {
            const now = new Date();
            const timeStr = now.toLocaleTimeString('vi-VN', { hour12: false });
            $('#admin-clock').text(timeStr);
        }
        setInterval(updateClock, 1000); // Chạy mỗi giây
        updateClock();

        /**
         * Lấy dữ liệu thống kê từ API và vẽ biểu đồ Chart.js
         */
        $.get('backend/api/thong_ke.php', function(res) {
            if (res.status === 'success') {
                const ctx = document.getElementById('adminChart').getContext('2d');
                
                // Tạo hiệu ứng Gradient cho vùng bên dưới đường biểu đồ
                const gradient = ctx.createLinearGradient(0, 0, 0, 300);
                gradient.addColorStop(0, 'rgba(139, 0, 0, 0.4)');
                gradient.addColorStop(1, 'rgba(139, 0, 0, 0)');

                // Khởi tạo biểu đồ đường (Line Chart)
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: res.data.labels, // Các nhãn (thường là ngày)
                        datasets: [{
                            label: 'Lượt mượn phòng',
                            data: res.data.data, // Dữ liệu số lượng tương ứng
                            borderColor: '#8B0000',
                            backgroundColor: gradient,
                            fill: true,
                            tension: 0.4, // Độ cong của đường
                            pointBackgroundColor: '#fff',
                            pointBorderColor: '#8B0000',
                            pointBorderWidth: 2,
                            pointRadius: 5
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { 
                                beginAtZero: true, 
                                ticks: {
                                    stepSize: 1, // Khoảng cách giữa các vạch chia
                                    precision: 0 // Không hiển thị số thập phân
                                },
                                grid: { display: true, color: '#f0f0f0' } 
                            },
                            x: { grid: { display: false } }
                        }
                    }
                });
            }
        });
    });
</script>

<?php require_once 'frontend/includes/footer.php'; ?>

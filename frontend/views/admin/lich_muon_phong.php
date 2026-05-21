<?php require_once 'frontend/includes/header.php'; ?>

<style>
    .weekly-schedule-container {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.05);
        overflow: hidden;
    }
    .weekly-table {
        table-layout: fixed;
        width: 100%;
        border-collapse: collapse;
    }
    .weekly-table th {
        background: #f8fafc;
        padding: 1.5rem 1rem;
        border-bottom: 2px solid #e2e8f0;
        border-right: 1px solid #e2e8f0;
        text-align: center;
    }
    .weekly-table td {
        height: 120px;
        border-bottom: 1px solid #f1f5f9;
        border-right: 1px solid #f1f5f9;
        vertical-align: top;
        padding: 8px;
    }
    .session-col {
        width: 100px;
        background: #fdfdfd;
        font-weight: 700;
        text-align: center;
        vertical-align: middle !important;
        border-right: 2px solid #e2e8f0 !important;
    }
    .slot-card {
        height: 100%;
        border-radius: 10px;
        padding: 10px;
        font-size: 0.75rem;
        transition: all 0.3s ease;
    }
    .slot-approved { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
    .slot-pending { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
    .slot-free { background: #f8fafc; border: 1px dashed #e2e8f0; display: flex; align-items: center; justify-content: center; color: #94a3b8; }
    
    .room-sidebar {
        background: #fff;
        border-radius: 20px;
        padding: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.05);
    }
    .suggested-room {
        padding: 12px;
        border-radius: 12px;
        background: #f8fafc;
        margin-bottom: 10px;
        border: 1px solid #e2e8f0;
        transition: all 0.2s;
    }
    .suggested-room:hover {
        border-color: var(--ptit-red);
        background: #fff1f1;
    }

    /* Two-tier Room Selection */
    .room-nav-container {
        background: #fff;
        border-radius: 15px;
        padding: 5px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        margin-bottom: 25px;
    }
    .floor-switcher {
        display: flex;
        border-bottom: 1px solid #f1f5f9;
        padding: 5px;
        margin-bottom: 10px;
    }
    .floor-btn {
        padding: 8px 20px;
        font-weight: 700;
        color: #64748b;
        cursor: pointer;
        border-radius: 10px;
        transition: all 0.2s;
        font-size: 0.85rem;
    }
    .floor-btn:hover { color: var(--ptit-red); background: #fdf2f2; }
    .floor-btn.active {
        background: #fff1f1;
        color: var(--ptit-red);
    }

    .room-slider {
        display: flex;
        overflow-x: auto;
        gap: 10px;
        padding: 10px;
        scrollbar-width: none;
    }
    .room-slider::-webkit-scrollbar { display: none; }

    .room-chip {
        flex: 0 0 auto;
        padding: 8px 18px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        font-weight: 600;
        color: #475569;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.9rem;
    }
    .room-chip:hover { border-color: var(--ptit-red); color: var(--ptit-red); }
    .room-chip.active {
        background: var(--ptit-red);
        color: #fff;
        border-color: var(--ptit-red);
        box-shadow: 0 4px 12px rgba(139, 0, 0, 0.2);
    }
</style>

<div class="container-fluid py-4">
    <div class="row g-4">
        <!-- Main Schedule Column -->
        <div class="col-xl-9">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-3">
                <div>
                    <h2 class="fw-extrabold text-ptit-red mb-1">Lịch Tuần Phòng Học</h2>
                    <p class="text-muted mb-0">Theo dõi chi tiết lịch sử dụng trong tuần (Thứ 2 - Thứ 7)</p>
                </div>
                <div class="d-flex gap-2">
                    <form action="index.php" method="GET" id="filter-form" class="d-flex gap-2">
                        <input type="hidden" name="page" value="quan_tri">
                        <input type="hidden" name="action" value="lich_muon_phong">
                        <input type="hidden" name="room_id" id="selected-room-id" value="<?= $roomId ?>">
                        <input type="week" name="week" class="form-select border-0 shadow-sm rounded-pill px-4" value="<?= $week ?>" onchange="this.form.submit()">
                    </form>
                </div>
            </div>

            <!-- Two-tier Room Selection -->
            <div class="room-nav-container">
                <div class="floor-switcher">
                    <?php foreach (array_keys($roomsByFloor) as $f): ?>
                        <div class="floor-btn <?= $f == $currentFloor ? 'active' : '' ?>" data-floor="<?= $f ?>" onclick="switchFloor(<?= $f ?>)">
                            Tầng <?= $f ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="room-slider">
                    <?php foreach ($roomsByFloor as $f => $fRooms): ?>
                        <div class="floor-group <?= $f == $currentFloor ? '' : 'd-none' ?>" id="floor-group-<?= $f ?>">
                            <div class="d-flex gap-2">
                                <?php foreach ($fRooms as $r): ?>
                                    <div class="room-chip <?= $r['id'] == $roomId ? 'active' : '' ?>" onclick="selectSliderRoom(<?= $r['id'] ?>)">
                                        <i class="fa-solid fa-door-<?= $r['id'] == $roomId ? 'open' : 'closed' ?>"></i>
                                        <?= $r['room_number'] ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="weekly-schedule-container">
                <div class="table-responsive">
                    <table class="weekly-table">
                        <thead>
                            <tr>
                                <th class="session-col">Ca \ Thứ</th>
                                <?php foreach ($weekDays as $day): ?>
                                    <th>
                                        <div class="fw-bold text-dark"><?= $day['name'] ?></div>
                                        <div class="extra-small text-muted"><?= date('d/m', strtotime($day['date'])) ?></div>
                                    </th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($sessions as $session): ?>
                                <tr>
                                    <td class="session-col">
                                        <div class="text-ptit-red mb-1"><?= $session['session_name'] ?></div>
                                        <div class="extra-small text-muted fw-normal"><?= substr($session['start_time'], 0, 5) ?></div>
                                    </td>
                                    <?php foreach ($weekDays as $day): ?>
                                        <?php 
                                            $booking = $roomSchedule[$day['date']][$session['id']] ?? null; 
                                            $status = $booking ? $booking['status'] : 'free';
                                        ?>
                                        <td>
                                            <?php if ($status === 'approved'): ?>
                                                <div class="slot-card slot-approved" data-bs-toggle="tooltip" title="Người mượn: <?= htmlspecialchars($booking['fullname']) ?>">
                                                    <div class="fw-bold mb-1">ĐÃ MƯỢN</div>
                                                    <div class="opacity-75"><?= htmlspecialchars($booking['fullname']) ?></div>
                                                </div>
                                            <?php elseif ($status === 'pending'): ?>
                                                <div class="slot-card slot-pending" data-bs-toggle="tooltip" title="Yêu cầu đang chờ duyệt">
                                                    <div class="fw-bold mb-1">CHỜ DUYỆT</div>
                                                    <div class="opacity-75"><?= htmlspecialchars($booking['fullname']) ?></div>
                                                </div>
                                            <?php else: ?>
                                                <div class="slot-card slot-free">
                                                    <span>Sẵn sàng</span>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right Sidebar (Suggestions) -->
        <div class="col-xl-3">
            <div class="room-sidebar h-100">
                <h5 class="fw-bold mb-4"><i class="fa-solid fa-lightbulb text-warning me-2"></i>Phòng trống gợi ý</h5>
                <p class="small text-muted mb-4">Các phòng đang khả dụng tại <strong><?= $sessionName ?></strong> (<?= date('H:i') ?>)</p>
                
                <div class="suggested-list">
                    <?php if (empty($freeRooms)): ?>
                        <div class="text-center py-4">
                            <i class="fa-solid fa-calendar-xmark fs-2 text-light mb-2"></i>
                            <p class="small text-muted">Không có phòng trống nào</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($freeRooms as $fr): ?>
                            <a href="index.php?page=quan_tri&action=lich_muon_phong&room_id=<?= $fr['id'] ?>" class="suggested-room d-flex align-items-center text-dark text-decoration-none">
                                <div class="bg-success-soft text-success rounded p-2 me-3"><i class="fa-solid fa-door-open"></i></div>
                                <div class="fw-bold"><?= $fr['room_number'] ?></div>
                                <i class="fa-solid fa-arrow-right ms-auto extra-small text-muted"></i>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <hr class="my-4">
                
                <h6 class="fw-bold mb-3 small text-uppercase text-muted">Chú thích</h6>
                <div class="d-flex flex-column gap-2 small">
                    <div class="d-flex align-items-center"><span class="rounded me-2" style="width:12px; height:12px; background:#fee2e2; border:1px solid #fecaca;"></span> Đã mượn</div>
                    <div class="d-flex align-items-center"><span class="rounded me-2" style="width:12px; height:12px; background:#fef3c7; border:1px solid #fde68a;"></span> Đang chờ duyệt</div>
                    <div class="d-flex align-items-center"><span class="rounded me-2" style="width:12px; height:12px; background:#f8fafc; border:1px dashed #e2e8f0;"></span> Đang trống</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    /**
     * Chuyển đổi hiển thị danh sách phòng theo tầng
     * @param {number} floor - Số tầng được chọn
     */
    function switchFloor(floor) {
        // Cập nhật trạng thái active cho nút bấm tầng
        $('.floor-btn').removeClass('active');
        $(`.floor-btn[data-floor="${floor}"]`).addClass('active');
        
        // Ẩn tất cả các nhóm phòng của tầng khác và hiện nhóm phòng của tầng được chọn
        $('.floor-group').addClass('d-none');
        $(`#floor-group-${floor}`).removeClass('d-none');
    }

    /**
     * Xử lý khi nhấn chọn một phòng cụ thể trong slider
     * @param {number} id - ID của phòng
     */
    function selectSliderRoom(id) {
        // Cập nhật ID phòng vào form ẩn và thực hiện gửi form để tải lại lịch của phòng đó
        $('#selected-room-id').val(id);
        $('#filter-form').submit();
    }

    $(document).ready(function() {
        // Khởi tạo các tooltip của Bootstrap (hiển thị tên người mượn khi di chuột vào ô lịch)
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
        const tooltipList = [...tooltipTriggerList].map(el => new bootstrap.Tooltip(el))

        // Tự động cuộn thanh chọn phòng đến vị trí phòng đang được xem
        const activeChip = document.querySelector('.room-chip.active');
        if (activeChip) {
            activeChip.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
        }
    });
</script>

<?php require_once 'frontend/includes/footer.php'; ?>

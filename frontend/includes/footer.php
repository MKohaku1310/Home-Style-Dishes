    <footer class="bg-white py-5 mt-auto border-top">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <img src="https://info.nhonam.io.vn/images/Logo-PTIT@2x.png" alt="PTIT" height="55" class="mb-3">
                    <p class="text-muted small pe-lg-4">
                        Hệ thống Quản lý Phòng học Tòa A3 - Học viện Công nghệ Bưu chính Viễn thông. Giải pháp tối ưu cho việc điều phối và quản lý tài nguyên học đường.
                    </p>
                    <div class="d-flex gap-3 mt-3">
                        <a href="#" class="text-ptit-red fs-5"><i class="fa-brands fa-facebook"></i></a>
                        <a href="#" class="text-ptit-red fs-5"><i class="fa-brands fa-youtube"></i></a>
                        <a href="#" class="text-ptit-red fs-5"><i class="fa-solid fa-globe"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h6 class="fw-bold mb-3">Liên kết nhanh</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><a href="index.php?page=home" class="text-muted text-decoration-none hover-red">Trang chủ</a></li>
                        <li class="mb-2"><a href="index.php?page=user&action=booking" class="text-muted text-decoration-none hover-red">Đăng ký mượn</a></li>
                        <li class="mb-2"><a href="index.php?page=user&action=history" class="text-muted text-decoration-none hover-red">Lịch sử của bạn</a></li>
                        <li class="mb-2"><a href="index.php?page=user&action=report_incident" class="text-muted text-decoration-none hover-red">Báo hỏng thiết bị</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h6 class="fw-bold mb-3">Liên hệ hỗ trợ</h6>
                    <ul class="list-unstyled small text-muted">
                        <li class="mb-2"><i class="fa-solid fa-phone me-2 text-ptit-red"></i> 024.33528122</li>
                        <li class="mb-2"><i class="fa-solid fa-envelope me-2 text-ptit-red"></i> phongdaotao@ptit.edu.vn</li>
                        <li class="mb-2"><i class="fa-solid fa-clock me-2 text-ptit-red"></i> Thứ 2 - Thứ 6: 08:00 - 17:00</li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h6 class="fw-bold mb-3">Vị trí</h6>
                    <ul class="list-unstyled small text-muted">
                        <li class="mb-2"><i class="fa-solid fa-location-dot me-2 text-ptit-red"></i> Tòa nhà A3, Học viện Công nghệ Bưu chính Viễn thông.</li>
                        <li class="mb-2"><i class="fa-solid fa-city me-2 text-ptit-red"></i> 96A Trần Phú, Quận Hà Đông, Hà Nội.</li>
                    </ul>
                </div>
            </div>
            <hr class="my-4 opacity-10">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                <p class="mb-0 text-muted small">
                    © <span id="current-year"></span> PTIT Room Booking. Bảo lưu mọi quyền.
                </p>
                <div class="small">
                    <a href="#" class="text-muted text-decoration-none me-3">Chính sách bảo mật</a>
                    <a href="#" class="text-muted text-decoration-none">Điều khoản sử dụng</a>
                </div>
            </div>
        </div>
    </footer>
        </main> <!-- /main-content -->
    </div> <!-- /wrapper -->

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Hiển thị năm hiện tại ở chân trang
            $('#current-year').text(new Date().getFullYear());

            // Kích hoạt Bootstrap Tooltips (hiển thị chú thích khi di chuột vào icon)
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
            const tooltipList = [...tooltipTriggerList].map(el => new bootstrap.Tooltip(el))
            
            // Tự động ẩn các thông báo (Alerts) sau 5 giây để không làm phiền người dùng
            setTimeout(function() {
                $('.alert-dismissible').fadeOut(500, function() {
                    $(this).remove();
                });
            }, 5000);
        });
    </script>
</body>
</html>
</html>

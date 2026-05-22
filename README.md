# 🏫 Hệ thống Quản lý Đặt phòng PTIT (Room Booking System)

[![PHP Version](https://img.shields.io/badge/PHP-8.0+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![Database](https://img.shields.io/badge/Database-SQLite3-003B57?style=for-the-badge&logo=sqlite&logoColor=white)](https://www.sqlite.org/)
[![Platform](https://img.shields.io/badge/Platform-Windows-0078D6?style=for-the-badge&logo=windows&logoColor=white)](#)

Dự án **Hệ thống Quản lý Đặt phòng** là giải pháp phần mềm tối ưu phục vụ việc quản lý, đăng ký và mượn phòng học, phòng thực hành tại Học viện Công nghệ Bưu chính Viễn thông (PTIT). 

Hệ thống được phát triển theo mô hình **MVC (Model-View-Controller)** thuần túy bằng ngôn ngữ **PHP** kết hợp cơ sở dữ liệu **SQLite** gọn nhẹ, tối ưu hóa để vận hành mượt mà mà không cần cấu hình phức tạp.

---

## ⚡ HƯỚNG DẪN CHẠY TRÊN MÁY TÍNH TRƯỜNG (Ổ CỨNG ĐÓNG BĂNG)

Vì máy tính phòng máy của trường sử dụng công nghệ **đóng băng ổ cứng** (Deep Freeze), mọi phần mềm cài đặt hoặc thay đổi cấu hình hệ thống sẽ bị xóa sạch sau khi máy khởi động lại. 

Mã nguồn này đã được tối ưu hóa đặc biệt theo dạng **Portable (chạy ngay không cần cài đặt)** giúp bạn cài đặt hệ thống chỉ với **1 click chuột**.

### 🚀 Cách 1: Tải trực tiếp từ GitHub (Nhanh & Thuận tiện nhất)
Khi bạn ngồi máy trường và tải file mã nguồn dạng `.zip` từ GitHub về máy:

1. **Giải nén**: Click chuột phải vào file ZIP vừa tải về và chọn `Extract Here` (Giải nén ra Desktop hoặc ổ đĩa `D:\`).
2. **Khởi chạy**: Nhấp đúp chuột vào file **`chay_nhanh.bat`** ở thư mục gốc của dự án.
3. **Cài đặt PHP tự động (nếu máy trống)**:
   * **Nếu máy trường ĐÃ CÓ XAMPP/Laragon**: Script sẽ tự động phát hiện đường dẫn PHP của máy và khởi chạy Web Server ngay lập tức.
   * **Nếu máy trường CHƯA CÓ PHP**: Cửa sổ CMD sẽ hiển thị câu hỏi:
     `[+] Bạn có muốn TỰ ĐỘNG TẢI PHP Portable (25MB) từ trang chủ PHP để chạy ngay không? (Y/N):`
     -> Bạn chỉ cần gõ **`Y`** rồi ấn **`Enter`**.
4. **Trải nghiệm**: Script sẽ tự động tải, giải nén PHP, tự động kích hoạt SQLite và mở trình duyệt truy cập địa chỉ: `http://localhost:8000`.

---

### 💾 Cách 2: Chuẩn bị sẵn trong USB (Chạy Offline không cần Internet)
Nếu bạn muốn chuẩn bị trước ở nhà để cắm USB vào máy trường là chạy được ngay không cần kết nối mạng:

1. **Tải PHP Portable**: Truy cập trang [windows.php.net/download](https://windows.php.net/download/) và tải bản **PHP Portable (dạng Zip)** (~25MB, chọn bản *VS16 x64 Non Thread Safe* hoặc *Thread Safe* đều được).
2. **Cài đặt vào thư mục**: Tạo thư mục có tên **`php`** ở ngay thư mục gốc của dự án này và giải nén toàn bộ file Zip vừa tải vào đó (Đảm bảo file thực thi nằm đúng tại: `[thư mục dự án]/php/php.exe`).
3. **Lưu vào USB**: Copy toàn bộ thư mục dự án này vào USB của bạn.
4. **Khởi chạy**: Cắm USB vào máy trường và chạy trực tiếp file **`chay_nhanh.bat`**. Server sẽ nhận diện PHP cục bộ và khởi chạy ngay trong vòng 1 giây.

---

## 💾 Cơ sở dữ liệu

Mặc định, hệ thống sử dụng cơ sở dữ liệu **SQLite** giúp chạy ngay lập tức mà không cần cấu hình. Tuy nhiên, nếu bạn muốn sử dụng **MySQL (phpMyAdmin)**, bạn có thể thực hiện theo hướng dẫn dưới đây.

---

### 🌐 Hướng dẫn sử dụng MySQL / phpMyAdmin

#### Bước 1: Import cơ sở dữ liệu trên phpMyAdmin
1. Khởi động **XAMPP** hoặc **Laragon** và mở **phpMyAdmin** (thường ở địa chỉ `http://localhost/phpmyadmin`).
2. Tạo một cơ sở dữ liệu mới có tên: **`ql_phong_hoc`** (chọn bảng mã `utf8mb4_unicode_ci`).
3. Chọn cơ sở dữ liệu `ql_phong_hoc` vừa tạo.
4. Chọn tab **Import** (Nhập) ở thanh menu phía trên.
5. Nhấp chọn **Choose File** (Chọn tệp) và trỏ đến file **`database.sql`** nằm ngay thư mục gốc của dự án này.
6. Kéo xuống dưới cùng và nhấn **Import** (Nhập) hoặc **Go** (Thực hiện).

#### Bước 2: Cấu hình kết nối MySQL trong mã nguồn
1. Mở tệp [database.php](file:///d:/Projects/VNOJ/Home-Style-Dishes/backend/config/database.php) nằm tại thư mục `backend/config/database.php`.
2. Thay đổi giá trị cấu hình driver từ `sqlite` thành `mysql`:
   ```php
   define('DB_DRIVER', 'mysql');
   ```
3. Cập nhật lại thông tin kết nối MySQL nếu cần thiết:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_PORT', '3306');
   define('DB_NAME', 'ql_phong_hoc');
   define('DB_USER', 'root');
   define('DB_PASS', ''); // Mật khẩu MySQL của bạn (mặc định XAMPP/Laragon là để trống)
   ```

---

## 🔑 Tài khoản đăng nhập thử nghiệm

Dữ liệu thử nghiệm đã được chuẩn bị sẵn trong hệ thống để bạn thuận tiện kiểm tra các vai trò (Roles):

| Vai trò | Tên đăng nhập | Mật khẩu | Mô tả quyền |
| :--- | :--- | :--- | :--- |
| **Quản trị viên (Admin)** | `admin` | `123456` | Toàn quyền quản lý phòng, thiết bị, duyệt yêu cầu đặt phòng, quản lý người dùng, xem thống kê báo cáo. |
| **Sinh viên / Giảng viên** | *Đăng ký trực tiếp* | *Tự chọn* | Đăng ký mượn phòng, kiểm tra ca trống, báo hỏng thiết bị, theo dõi lịch sử và nhận thông báo duyệt yêu cầu. |

> Bạn cũng có thể sử dụng tài khoản Sinh viên mẫu có sẵn: `testuser123` / mật khẩu: `123456`.

---

## ✨ Các chức năng chính của hệ thống

* **Trang chủ & Thống kê**: Hiển thị tổng quan số lượng phòng học trống, thiết bị hỏng hóc cần sửa chữa và biểu đồ phân tích tần suất mượn phòng.
* **Đặt phòng học**: Kiểm tra phòng trống theo thời gian thực (ngày học, ca học), thực hiện gửi yêu cầu mượn phòng và tránh trùng lịch tối đa.
* **Quản lý thiết bị & Báo hỏng**: Sinh viên/Giảng viên gửi báo cáo hỏng hóc (máy chiếu, điều hòa,...) trực tiếp của phòng học, Admin quản lý danh sách thiết bị và phê duyệt xử lý sự cố.
* **Quản trị hệ thống (Admin)**:
  * Duyệt/từ chối nhanh hàng loạt yêu cầu đặt phòng kèm lý do.
  * Thêm, sửa, xóa thông tin phòng học, trạng thái phòng học.
  * Quản lý thông tin tài khoản người dùng (khóa/mở khóa tài khoản).
  * Xuất báo cáo danh sách đặt phòng và danh sách báo hỏng ra file CSV/Excel tiện lợi.

---

## 📁 Cấu trúc thư mục dự án

```text
BT3/
│
├── chay_nhanh.bat            # Script tự động khởi chạy Web Server trên Windows
├── index.php                 # File định tuyến (Router) và điều phối chính (MVC Entry)
│
├── backend/                  # Chứa toàn bộ xử lý logic nghiệp vụ
│   ├── api/                  # Các Endpoint API xử lý dữ liệu AJAX (Đặt phòng, báo cáo, thống kê...)
│   ├── config/               # Cấu hình kết nối CSDL SQLite (database.php)
│   ├── controllers/          # Bộ điều khiển trung gian nhận và xử lý yêu cầu
│   ├── models/               # Model quản lý nghiệp vụ và tương tác với các bảng CSDL
│   └── database.sqlite       # Tệp cơ sở dữ liệu SQLite duy nhất của hệ thống
│
└── frontend/                 # Chứa giao diện hiển thị cho người dùng
    ├── assets/               # Thư mục chứa các file tĩnh (Style CSS, JavaScript Client, Image)
    └── views/                # Giao diện PHP templates (Trang chủ, Đăng nhập/Đăng ký, các View con)
```

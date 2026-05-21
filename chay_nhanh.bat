@echo off
chcp 65001 > nul
title Khởi chạy nhanh - Hệ thống đặt phòng PTIT
echo ============================================================
echo      HỆ THỐNG QUẢN LÝ ĐẶT PHÒNG PTIT - KHỞI CHẠY NHANH
echo ============================================================
echo.

:: 1. Kiểm tra PHP Portable trong thư mục dự án (thư mục "php")
if exist "%~dp0php\php.exe" (
    set PHP_PATH=%~dp0php\php.exe
    goto :found_php
)

:: 2. Kiểm tra PHP trong PATH hệ thống
where php >nul 2>nul
if %errorlevel% equ 0 (
    for /f "tokens=*" %%i in ('where php') do (
        set PHP_PATH=%%i
        goto :found_php
    )
)

:: 3. Kiểm tra PHP trong thư mục XAMPP ổ C (C:\xampp\php\php.exe)
if exist "C:\xampp\php\php.exe" (
    set PHP_PATH=C:\xampp\php\php.exe
    goto :found_php
)

:: 4. Kiểm tra PHP trong thư mục XAMPP ổ D (D:\xampp\php\php.exe)
if exist "D:\xampp\php\php.exe" (
    set PHP_PATH=D:\xampp\php\php.exe
    goto :found_php
)

:: 5. Kiểm tra Laragon ổ C (C:\laragon\bin\php)
if exist "C:\laragon\bin\php" (
    for /f "delims=" %%i in ('dir /b /s "C:\laragon\bin\php\php.exe" 2^>nul') do (
        if exist "%%i" (
            set PHP_PATH=%%i
            goto :found_php
        )
    )
)

:: 6. Kiểm tra Laragon ổ D (D:\laragon\bin\php)
if exist "D:\laragon\bin\php" (
    for /f "delims=" %%i in ('dir /b /s "D:\laragon\bin\php\php.exe" 2^>nul') do (
        if exist "%%i" (
            set PHP_PATH=%%i
            goto :found_php
        )
    )
)

echo [!] CẢNH BÁO: Không tìm thấy PHP trên máy tính này (Không có XAMPP/Laragon/PATH).
echo.
set /p choice="[+] Bạn có muốn TỰ ĐỘNG TẢI PHP Portable (25MB) từ trang chủ PHP để chạy ngay không? (Y/N): "
if /i "%choice%"=="Y" goto :download_php
if /i "%choice%"=="y" goto :download_php
exit

:download_php
echo.
echo [+] Đang chuẩn bị tải PHP Portable từ windows.php.net (khoảng 25MB)...
echo [!] Vui lòng đợi trong giây lát, quá trình tải và giải nén có thể mất 10-20 giây...
echo.

powershell -NoProfile -Command " ^
    $ProgressPreference = 'SilentlyContinue'; ^
    $url = 'https://windows.php.net/downloads/releases/archives/php-8.2.12-nts-Win32-vs16-x64.zip'; ^
    $zipFile = Join-Path '%~dp0' 'php.zip'; ^
    $destDir = Join-Path '%~dp0' 'php'; ^
    [Net.ServicePointManager]::SecurityProtocol = [Net.SecurityProtocolType]::Tls12; ^
    Write-Host '   -> [1/3] Đang tải PHP Portable...'; ^
    Invoke-WebRequest -Uri $url -OutFile $zipFile; ^
    Write-Host '   -> [2/3] Đang giải nén vào thư mục php/...'; ^
    Expand-Archive -Path $zipFile -DestinationPath $destDir -Force; ^
    Write-Host '   -> [3/3] Đang dọn dẹp file cài đặt...'; ^
    Remove-Item $zipFile -Force; ^
    Write-Host '[+] Tải và giải nén thành công!'; ^
"

if exist "%~dp0php\php.exe" (
    set PHP_PATH=%~dp0php\php.exe
    goto :found_php
) else (
    echo.
    echo [!] LỖI: Tải hoặc giải nén thất bại. Vui lòng kiểm tra lại kết nối mạng.
    pause
    exit
)

:found_php
echo [+] Đã phát hiện PHP tại: %PHP_PATH%
echo [+] Đang tự động cấu hình SQLite cho PHP...

:: Tạo php_run.ini tùy chỉnh để đảm bảo chạy được SQLite
powershell -NoProfile -Command " ^
    $phpExe = '%PHP_PATH%'; ^
    $phpDir = Split-Path $phpExe; ^
    $iniSource = Join-Path $phpDir 'php.ini'; ^
    if (-not (Test-Path $iniSource)) { ^
        if (Test-Path (Join-Path $phpDir 'php.ini-development')) { ^
            $iniSource = Join-Path $phpDir 'php.ini-development'; ^
        } elseif (Test-Path (Join-Path $phpDir 'php.ini-production')) { ^
            $iniSource = Join-Path $phpDir 'php.ini-production'; ^
        } ^
    } ^
    $localIni = Join-Path '%~dp0' 'php_run.ini'; ^
    if (Test-Path $iniSource) { ^
        Copy-Item $iniSource $localIni -Force; ^
        $content = Get-Content $localIni -Raw; ^
        $content = $content -replace ';?\s*extension\s*=\s*pdo_sqlite', 'extension=pdo_sqlite'; ^
        $content = $content -replace ';?\s*extension\s*=\s*sqlite3', 'extension=sqlite3'; ^
        $extDir = Join-Path $phpDir 'ext'; ^
        $extDir = $extDir.Replace('\', '/'); ^
        $content = $content -replace ';?\s*extension_dir\s*=\s*[^;\r\n]+', ('extension_dir = ' + [char]34 + $extDir + [char]34); ^
        Set-Content $localIni $content; ^
        Write-Host '[+] Đã tạo cấu hình php_run.ini tối ưu với SQLite.'; ^
    } else { ^
        Write-Host '[!] Cảnh báo: Không tìm thấy file php.ini gốc để tự động cấu hình.'; ^
    } ^
"

echo [+] Đang khởi tạo Web Server tại địa chỉ: http://localhost:8000
echo.
echo [LƯU Ý] Cơ sở dữ liệu SQLite đã được tích hợp sẵn tại 'backend/database.sqlite' 
echo         nên bạn KHÔNG cần chạy bất kỳ lệnh khởi tạo dữ liệu nào khác!
echo.
echo [+] Đang tự động mở trình duyệt...
echo [!] Nhấn giữ Ctrl + C trong cửa sổ này để tắt Server khi dùng xong.
echo ============================================================
echo.

:: Tự động mở trình duyệt web mặc định
start http://localhost:8000

:: Chạy server PHP built-in với cấu hình php_run.ini vừa tạo
if exist "%~dp0php_run.ini" (
    "%PHP_PATH%" -c "%~dp0php_run.ini" -S localhost:8000
) else (
    "%PHP_PATH%" -S localhost:8000
)

pause

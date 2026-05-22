@echo off
title Room Booking System - Quick Launch
echo ============================================================
echo      ROOM BOOKING SYSTEM - QUICK LAUNCH
echo ============================================================
echo.

:: 1. Check local PHP Portable in project directory ("php" folder)
if exist "%~dp0php\php.exe" (
    set PHP_PATH=%~dp0php\php.exe
    goto :found_php
)

:: 2. Check PHP in system PATH
where php >nul 2>nul
if %errorlevel% equ 0 (
    for /f "tokens=*" %%i in ('where php') do (
        set PHP_PATH=%%i
        goto :found_php
    )
)

:: 3. Check PHP in C:\xampp\php\php.exe
if exist "C:\xampp\php\php.exe" (
    set PHP_PATH=C:\xampp\php\php.exe
    goto :found_php
)

:: 4. Check PHP in D:\xampp\php\php.exe
if exist "D:\xampp\php\php.exe" (
    set PHP_PATH=D:\xampp\php\php.exe
    goto :found_php
)

:: 5. Check Laragon on C: (C:\laragon\bin\php)
if exist "C:\laragon\bin\php" (
    for /f "delims=" %%i in ('dir /b /s "C:\laragon\bin\php\php.exe" 2^>nul') do (
        if exist "%%i" (
            set PHP_PATH=%%i
            goto :found_php
        )
    )
)

:: 6. Check Laragon on D: (D:\laragon\bin\php)
if exist "D:\laragon\bin\php" (
    for /f "delims=" %%i in ('dir /b /s "D:\laragon\bin\php\php.exe" 2^>nul') do (
        if exist "%%i" (
            set PHP_PATH=%%i
            goto :found_php
        )
    )
)

echo [!] WARNING: PHP was not found on this computer.
echo.
set /p choice="[+] Do you want to AUTOMATICALLY DOWNLOAD PHP Portable (25MB) to run? (Y/N): "
if /i "%choice%"=="Y" goto :download_php
if /i "%choice%"=="y" goto :download_php
exit

:download_php
echo.
echo [+] Preparing to download PHP Portable from windows.php.net (~25MB)...
echo [!] Please wait, downloading and extracting may take 10-20 seconds...
echo.

:: Use native curl and tar instead of PowerShell to bypass execution blocks
set "ZIP_FILE=%~dp0php.zip"
set "DEST_DIR=%~dp0php"

if not exist "%DEST_DIR%" mkdir "%DEST_DIR%"

echo    -> [1/3] Downloading PHP Portable using curl...
curl -L -# -o "%ZIP_FILE%" "https://windows.php.net/downloads/releases/archives/php-8.2.12-nts-Win32-vs16-x64.zip"
if %errorlevel% neq 0 (
    echo.
    echo [!] ERROR: PHP download failed. Please check your internet connection.
    pause
    exit
)

echo    -> [2/3] Extracting using tar...
tar -xf "%ZIP_FILE%" -C "%DEST_DIR%"
if %errorlevel% neq 0 (
    echo.
    echo [!] ERROR: PHP extraction failed.
    pause
    exit
)

echo    -> [3/3] Cleaning up installer files...
del /f /q "%ZIP_FILE%"
echo [+] Download and extraction completed successfully!

if exist "%~dp0php\php.exe" (
    set PHP_PATH=%~dp0php\php.exe
    goto :found_php
) else (
    echo.
    echo [!] ERROR: php.exe not found after extraction.
    pause
    exit
)

:found_php
echo [+] PHP detected at: %PHP_PATH%
echo [+] Automatically configuring SQLite for PHP...

:: Use PHP itself to run the configuration script to bypass PowerShell script execution blocks
"%PHP_PATH%" "%~dp0backend\config\setup_ini.php" "%PHP_PATH%"

echo [+] Starting Web Server at: http://localhost:8000
echo.
echo [NOTE] SQLite database is already integrated at 'backend/database.sqlite' 
echo        so you do NOT need to run any database initialization!
echo.
echo [+] Automatically opening browser...
echo [!] Press Ctrl + C in this window to stop the Server when finished.
echo ============================================================
echo.

:: Auto-open default browser
start http://localhost:8000

:: Run the PHP built-in server with custom php_run.ini
if exist "%~dp0php_run.ini" (
    "%PHP_PATH%" -c "%~dp0php_run.ini" -S localhost:8000
) else (
    "%PHP_PATH%" -S localhost:8000
)

pause

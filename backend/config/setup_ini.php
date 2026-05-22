<?php
/**
 * Automatically configure php_run.ini for SQLite.
 * Does not use PowerShell to avoid script execution blocks on school PCs.
 */

if ($argc < 2) {
    echo "[!] Missing PHP executable path parameter.\n";
    exit(1);
}

$phpExe = $argv[1];
$phpDir = dirname($phpExe);
$iniSource = '';

// Find the base php.ini file
foreach (['php.ini', 'php.ini-development', 'php.ini-production'] as $file) {
    $path = $phpDir . DIRECTORY_SEPARATOR . $file;
    if (file_exists($path)) {
        $iniSource = $path;
        break;
    }
}

// Save php_run.ini to the project root directory
$localIni = dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'php_run.ini';

if ($iniSource) {
    $content = file_get_contents($iniSource);
    
    // Enable SQLite3 and PDO SQLite extensions (handling different formats like php_pdo_sqlite.dll or appending if not found)
    if (preg_match('/extension\s*=\s*(php_)?pdo_sqlite(\.dll)?/i', $content)) {
        $content = preg_replace('/;?\s*extension\s*=\s*(php_)?pdo_sqlite(\.dll)?/i', 'extension=pdo_sqlite', $content);
    } else {
        $content .= "\r\nextension=pdo_sqlite\r\n";
    }

    if (preg_match('/extension\s*=\s*(php_)?sqlite3(\.dll)?/i', $content)) {
        $content = preg_replace('/;?\s*extension\s*=\s*(php_)?sqlite3(\.dll)?/i', 'extension=sqlite3', $content);
    } else {
        $content .= "\r\nextension=sqlite3\r\n";
    }
    
    // Set the absolute ext directory path
    $extDir = $phpDir . DIRECTORY_SEPARATOR . 'ext';
    $extDir = str_replace('\\', '/', $extDir);
    
    // Replace or append the extension_dir setting
    if (preg_match('/;\s*extension_dir\s*=\s*"/i', $content) || preg_match('/extension_dir\s*=\s*/i', $content)) {
        $content = preg_replace('/;?\s*extension_dir\s*=\s*[^;\r\n]+/i', 'extension_dir = "' . $extDir . '"', $content);
    } else {
        $content .= "\r\nextension_dir = \"" . $extDir . "\"\r\n";
    }
    
    file_put_contents($localIni, $content);
    echo "[+] Successfully created SQLite-compatible php_run.ini.\n";
} else {
    echo "[!] Warning: Original php.ini not found for SQLite configuration.\n";
}
?>

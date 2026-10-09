<?php
// api/index.php - Vercel Serverless Router

// Pastikan direktori session writable
$tempDir = sys_get_temp_dir();
if (!empty($tempDir) && is_dir($tempDir)) {
    @session_save_path($tempDir);
}

$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$uri = '/' . ltrim($uri, '/');

// Root path -> index.php
if ($uri === '/' || $uri === '/index.php') {
    chdir(__DIR__ . '/..');
    require __DIR__ . '/../index.php';
    exit;
}

$target = realpath(__DIR__ . '/..' . $uri);
$rootDir = realpath(__DIR__ . '/..');

// Pastikan file berada di dalam root proyek (mencegah path traversal)
if ($target && strpos($target, $rootDir) === 0) {
    if (is_file($target) && pathinfo($target, PATHINFO_EXTENSION) === 'php') {
        chdir(dirname($target));
        require $target;
        exit;
    }
    
    if (is_dir($target)) {
        if (is_file($target . '/index.php')) {
            chdir($target);
            require $target . '/index.php';
            exit;
        }
        if (is_file($target . '/dashboard.php')) {
            chdir($target);
            require $target . '/dashboard.php';
            exit;
        }
    }
}

// 404 handler
http_response_code(404);
echo "404 Not Found: " . htmlspecialchars($uri);
?>

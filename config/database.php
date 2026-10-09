<?php
// config/database.php

error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);

$host = getenv('DB_HOST') ?: 'gateway01.ap-southeast-1.prod.aws.tidbcloud.com';
$port = getenv('DB_PORT') ?: '4000';
$dbname = getenv('DB_NAME') ?: 'skill_gap_db';
$username = getenv('DB_USER') ?: 'iXqhPX7YdcK1hyP.root';
$password = getenv('DB_PASS') ?: 'wtFm0I9idQmQcFUM';

$ca_cert = __DIR__ . '/isrgrootx1.pem';

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

// Aktifkan SSL CA jika sertifikat ada (kompatibel PHP 8.0 s/d PHP 8.5+)
if (file_exists($ca_cert)) {
    if (class_exists('Pdo\Mysql') && defined('Pdo\Mysql::ATTR_SSL_CA')) {
        $options[\Pdo\Mysql::ATTR_SSL_CA] = $ca_cert;
    } else {
        $options[PDO::MYSQL_ATTR_SSL_CA] = $ca_cert;
    }
}

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $username, $password, $options);
} catch(PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
?>
<?php
// ==========================================
// EKSTRAKSI SEMUA SKILL DARI KAGGLE
// (DENGAN PROGRESS BAR & STATISTIK LENGKAP)
// ==========================================

include '../config/database.php';

echo "<h1>📚 EKSTRAKSI SEMUA SKILL DARI KAGGLE</h1>";
echo "<p>Sumber: Final_Updated_DMA_DATASET_Indian_Names.csv</p>";
echo "<hr>";

// ==========================================
// 1. CEK FILE
// ==========================================
$csv_file = '../Final_Updated_DMA_DATASET_Indian_Names.csv';

if (!file_exists($csv_file)) {
    die("❌ File CSV tidak ditemukan! Pastikan file ada di folder utama.");
}

// Hitung total baris dulu
$total_lines = 0;
$handle = fopen($csv_file, 'r');
while (fgets($handle) !== false) {
    $total_lines++;
}
fclose($handle);

echo "📊 Total baris dalam CSV: $total_lines <br>";

// ==========================================
// 2. BACA ULANG UNTUK EKSTRAKSI
// ==========================================
$handle = fopen($csv_file, 'r');
if (!$handle) {
    die("❌ Gagal membuka file!");
}

$header = fgetcsv($handle);
$col_technical = array_search('technical_skills', $header);
$col_programming = array_search('programming_languages', $header);
$col_soft = array_search('soft_skills', $header);

if ($col_technical === false && $col_programming === false && $col_soft === false) {
    die("❌ Kolom yang dibutuhkan tidak ditemukan!");
}

$all_skills = [];
$row_count = 0;

echo "<h3>🔍 Memproses ekstraksi...</h3>";

while (($row = fgetcsv($handle)) !== FALSE) {
    $row_count++;
    
    // Progress setiap 50 baris
    if ($row_count % 50 == 0) {
        echo "   - Memproses baris ke-$row_count...<br>";
    }
    
    $technical = isset($row[$col_technical]) ? trim($row[$col_technical]) : '';
    $programming = isset($row[$col_programming]) ? trim($row[$col_programming]) : '';
    $soft = isset($row[$col_soft]) ? trim($row[$col_soft]) : '';
    
    // Technical Skills
    if (!empty($technical)) {
        $skills = array_map('trim', explode(',', $technical));
        foreach ($skills as $skill) {
            if (!empty($skill)) {
                $all_skills[strtolower($skill)] = $skill;
            }
        }
    }
    
    // Programming Languages
    if (!empty($programming)) {
        $skills = array_map('trim', explode(',', $programming));
        foreach ($skills as $skill) {
            if (!empty($skill)) {
                $all_skills[strtolower($skill)] = $skill;
            }
        }
    }
    
    // Soft Skills
    if (!empty($soft)) {
        $skills = array_map('trim', explode(',', $soft));
        foreach ($skills as $skill) {
            if (!empty($skill)) {
                $all_skills[strtolower($skill)] = $skill;
            }
        }
    }
}

fclose($handle);

echo "✅ Selesai memproses $row_count baris.<br>";

$all_skills = array_values($all_skills);
sort($all_skills);

echo "📊 Total skill unik ditemukan: " . count($all_skills) . "<br><br>";

// ==========================================
// 3. TAMPILKAN SEMUA SKILL
// ==========================================
echo "<h4>📋 Daftar Semua Skill (". count($all_skills) ."):</h4>";
echo "<div style='max-height:300px; overflow:auto; border:1px solid #ddd; padding:10px;'>";
$i = 0;
foreach ($all_skills as $skill) {
    $i++;
    echo "$i. $skill<br>";
}
echo "</div><br>";

// ==========================================
// 4. MASUKKAN KE DATABASE
// ==========================================
echo "<h3>💾 Menyimpan ke tabel 'skills'...</h3>";

// Matikan foreign key check sementara
$pdo->exec("SET FOREIGN_KEY_CHECKS = 0");

// Kosongkan tabel
$pdo->exec("TRUNCATE TABLE skills");
echo "🔄 Tabel 'skills' dikosongkan.<br>";

$inserted = 0;
foreach ($all_skills as $skill_name) {
    $stmt = $pdo->prepare("INSERT INTO skills (skill_name) VALUES (?)");
    $stmt->execute([$skill_name]);
    $inserted++;
}

$pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

echo "✅ $inserted skill berhasil dimasukkan ke tabel 'skills'.<br>";

// ==========================================
// 5. SELESAI
// ==========================================
echo "<br><hr>";
echo "<h2>🎉 PROSES EKSTRAKSI SELESAI!</h2>";
echo "<ul>
        <li>📊 Total baris diproses: $row_count</li>
        <li>📊 Total skill unik ditemukan: " . count($all_skills) . "</li>
        <li>💾 Skill di tabel 'skills': $inserted</li>
      </ul>";
echo "<p><a href='http://localhost/phpmyadmin' target='_blank'>🔗 Cek di phpMyAdmin</a></p>";
?>
<?php
// process/analyze_skill_gap.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}
require_once '../config/database.php';

// ==========================================
// 1. AMBIL SKILL PENGGUNA (dengan kategori)
// ==========================================
$stmt = $pdo->prepare("
    SELECT s.skill_name, s.category 
    FROM user_skills us 
    JOIN skills s ON us.skill_id = s.id 
    WHERE us.user_id = ?
");
$stmt->execute([$_SESSION['user_id']]);
$userSkills = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ==========================================
// 2. AMBIL SKILL INDUSTRI (dengan kategori & demand_level)
// ==========================================
$industrySkills = $pdo->query("
    SELECT skill_name, category, demand_level 
    FROM industry_skills
")->fetchAll(PDO::FETCH_ASSOC);

// ==========================================
// 3. PROSES ANALISIS DENGAN 3 ATURAN
// ==========================================

$matched = [];        // Exact Match (100%)
$partialMatch = [];   // Partial Match (60-70%)
$gap = [];            // No Match (0%)

$userSkillNames = array_column($userSkills, 'skill_name');
$userSkillCategories = array_column($userSkills, 'category');

$totalWeightedScore = 0;
$totalSkills = count($industrySkills);

foreach ($industrySkills as $industry) {
    $industryName = $industry['skill_name'];
    $industryCategory = $industry['category'] ?? '';
    $demandLevel = $industry['demand_level'] ?? 'Medium';
    
    // ==========================================
    // ATURAN 1: EXACT MATCH (100%)
    // ==========================================
    if (in_array($industryName, $userSkillNames)) {
        $matched[] = $industryName;
        $totalWeightedScore += 100;
    }
    // ==========================================
    // ATURAN 2: PARTIAL MATCH (60-70%)
    // ==========================================
    elseif (!empty($industryCategory) && in_array($industryCategory, $userSkillCategories)) {
        $baseScore = ($demandLevel == 'High') ? 70 : (($demandLevel == 'Low') ? 60 : 65);
        $partialMatch[] = $industryName;
        $totalWeightedScore += $baseScore;
    }
    // ==========================================
    // ATURAN 3: NO MATCH (0%)
    // ==========================================
    else {
        $gap[] = $industryName;
    }
}

// ==========================================
// 4. HITUNG PERSENTASE
// ==========================================
$maxScore = $totalSkills * 100;
$percentage = $totalSkills > 0 ? round(($totalWeightedScore / $maxScore) * 100) : 0;

$matchCount = count($matched);
$partialCount = count($partialMatch);
$gapCount = count($gap);

// ==========================================
// 5. SIMPAN HISTORY (HANYA HASIL ANALISIS)
// ==========================================
$matchedJson = json_encode($matched);
$partialJson = json_encode($partialMatch);
$gapJson = json_encode($gap);

// Hapus data lama user
$stmt = $pdo->prepare("DELETE FROM analysis_history WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);

// Simpan data baru
$stmt = $pdo->prepare("
    INSERT INTO analysis_history 
    (user_id, match_percentage, matched_skills, gap_skills) 
    VALUES (?, ?, ?, ?)
");
$stmt->execute([
    $_SESSION['user_id'], 
    $percentage, 
    $matchedJson, 
    $gapJson
]);

// ==========================================
// 6. REDIRECT
// ==========================================
header('Location: ../dashboard/analysis.php?saved=1');
exit;
?>
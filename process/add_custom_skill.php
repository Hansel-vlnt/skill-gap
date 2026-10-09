<?php
// process/add_custom_skill.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_SESSION['user_id'];
    $skillName = trim($_POST['skill_name']);
    $level = $_POST['level'] ?? 'Beginner';
    $description = trim($_POST['description'] ?? '');
    
    if (empty($skillName)) {
        header('Location: ../dashboard/skills.php?error=empty');
        exit;
    }
    
    // Cek apakah skill sudah ada di tabel skills
    $stmt = $pdo->prepare("SELECT id FROM skills WHERE LOWER(skill_name) = LOWER(?)");
    $stmt->execute([$skillName]);
    $existing = $stmt->fetch();
    
    if ($existing) {
        $skillId = $existing['id'];
    } else {
        $stmt = $pdo->prepare("INSERT INTO skills (skill_name) VALUES (?)");
        $stmt->execute([$skillName]);
        $skillId = $pdo->lastInsertId();
    }
    
    // Simpan ke user_skills
    $stmt = $pdo->prepare("INSERT INTO user_skills (user_id, skill_id, proficiency_level, description) VALUES (?, ?, ?, ?)");
    $stmt->execute([$userId, $skillId, $level, $description]);
    
    header('Location: ../dashboard/skills.php?saved=1');
    exit;
}
?>
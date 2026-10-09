<?php
// process/save_skill.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_SESSION['user_id'];
    
    $skills = isset($_POST['skills']) ? $_POST['skills'] : [];
    $levels = isset($_POST['level']) ? $_POST['level'] : [];
    $descriptions = isset($_POST['description']) ? $_POST['description'] : [];
    
    // Hapus skill lama
    $stmt = $pdo->prepare("DELETE FROM user_skills WHERE user_id = ?");
    $stmt->execute([$userId]);
    
    // Simpan skill baru beserta deskripsi
    if (!empty($skills)) {
        $stmt = $pdo->prepare("INSERT INTO user_skills (user_id, skill_id, proficiency_level, description) VALUES (?, ?, ?, ?)");
        foreach ($skills as $skillId => $value) {
            $level = isset($levels[$skillId]) ? $levels[$skillId] : 'Beginner';
            $description = isset($descriptions[$skillId]) ? trim($descriptions[$skillId]) : '';
            $stmt->execute([$userId, $skillId, $level, $description]);
        }
    }
    
    header('Location: ../dashboard/skills.php?saved=1');
    exit;
}
?>
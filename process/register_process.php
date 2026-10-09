<?php
// process/register_process.php
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    if (empty($name) || empty($email) || empty($password)) {
        header('Location: ../auth/register.php?error=1');
        exit;
    }
    
    if (strlen($password) < 6) {
        header('Location: ../auth/register.php?error=2');
        exit;
    }
    
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    try {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $hashedPassword]);
        header('Location: ../auth/login.php?registered=1');
        exit;
    } catch(PDOException $e) {
        if ($e->errorInfo[1] == 1062) {
            header('Location: ../auth/register.php?error=3');
            exit;
        } else {
            header('Location: ../auth/register.php?error=1');
            exit;
        }
    }
}
?>
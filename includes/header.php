<?php
// includes/header.php
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'Skill Gap Analyzer' ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
</head>
<body>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>

    <div class="container" style="height:95vh; max-height:750px; display:flex; flex-direction:column; padding:16px 24px 12px;">
        <div class="header" style="flex-shrink:0; margin-bottom:10px; padding-bottom:8px; background: linear-gradient(135deg, rgba(255,255,255,0.08), rgba(255,255,255,0.03)); border-radius: 16px; padding: 16px 24px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.08);">
            <div class="logo-container" style="display:flex; align-items:center; gap:18px;">
                <div style="width:60px; height:60px; flex-shrink:0;">
                    <img src="../assets/images/FMIPA.png" alt="FMIPA Logo" style="width:100%; height:100%; object-fit:contain; display:block;">
                </div>
                <div class="logo-text">
                    <h1 style="font-size:24px; font-weight:900; margin:0; background: linear-gradient(135deg, #ffffff, #c3d4ff); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; letter-spacing: 1px; text-shadow: 0 2px 20px rgba(255,255,255,0.1);">Skill Gap Analyzer</h1>
                    <p style="font-size:12px; margin:4px 0 0 0; color: rgba(255,255,255,0.75); font-weight:600; letter-spacing: 1.5px; text-transform: uppercase;">UNIVERSITAS NEGERI MEDAN • FAKULTAS MIPA</p>
                </div>
            </div>
            <div class="header-user" style="display:flex; align-items:center; gap:16px;">
                <div style="display:flex; flex-direction:column; align-items:flex-end;">
                    <span class="user-name" style="font-size:18px; color:#ffffff; font-weight:700; letter-spacing: 0.5px;"><?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?></span>
                    <span style="font-size:12px; color: rgba(255,255,255,0.6); font-weight:500; text-transform: uppercase; letter-spacing: 1px;"><?= htmlspecialchars($_SESSION['user_role'] ?? 'User') ?></span>
                </div>
                <span class="user-avatar" style="width:45px; height:45px; font-size:18px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius:50%; display:flex; align-items:center; justify-content:center; color:#fff; font-weight:800; box-shadow: 0 4px 20px rgba(102, 126, 234, 0.4); border: 2px solid rgba(255,255,255,0.25);"><?= strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 1)) ?></span>
                <a href="../auth/logout.php" class="btn btn-success" style="padding:10px 24px; font-size:14px; font-weight:700; border-radius:10px; background: linear-gradient(135deg, #00b894, #00a381); border: none; color: #fff; transition: all 0.3s ease; box-shadow: 0 4px 20px rgba(0, 184, 148, 0.4); text-decoration: none; letter-spacing: 0.5px;">Logout</a>
            </div>
        </div>
<?php
// includes/sidebar.php
?>
<div style="width:160px; flex-shrink:0; background:rgba(255,255,255,0.03); border-radius:10px; padding:10px 0; border:1px solid rgba(255,255,255,0.04); display:flex; flex-direction:column; gap:2px;">
    <a href="dashboard.php" style="display:flex; align-items:center; gap:8px; padding:6px 12px; color:rgba(255,255,255,0.5); text-decoration:none; font-size:12px; font-weight:500; border-radius:6px; <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'background:rgba(46,204,113,0.1); color:#2ecc71; border-left:3px solid #2ecc71;' : '' ?>">
        <span style="font-size:14px;">📊</span> Dashboard
    </a>
    <a href="profile.php" style="display:flex; align-items:center; gap:8px; padding:6px 12px; color:rgba(255,255,255,0.5); text-decoration:none; font-size:12px; font-weight:500; border-radius:6px; <?= basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'background:rgba(46,204,113,0.1); color:#2ecc71; border-left:3px solid #2ecc71;' : '' ?>">
        <span style="font-size:14px;">👤</span> Profil
    </a>
    <a href="skills.php" style="display:flex; align-items:center; gap:8px; padding:6px 12px; color:rgba(255,255,255,0.5); text-decoration:none; font-size:12px; font-weight:500; border-radius:6px; <?= basename($_SERVER['PHP_SELF']) == 'skills.php' ? 'background:rgba(46,204,113,0.1); color:#2ecc71; border-left:3px solid #2ecc71;' : '' ?>">
        <span style="font-size:14px;">✏️</span> Input Skill
    </a>
    <a href="analysis.php" style="display:flex; align-items:center; gap:8px; padding:6px 12px; color:rgba(255,255,255,0.5); text-decoration:none; font-size:12px; font-weight:500; border-radius:6px; <?= basename($_SERVER['PHP_SELF']) == 'analysis.php' ? 'background:rgba(46,204,113,0.1); color:#2ecc71; border-left:3px solid #2ecc71;' : '' ?>">
        <span style="font-size:14px;">📈</span> Analisis
    </a>
    <a href="history.php" style="display:flex; align-items:center; gap:8px; padding:6px 12px; color:rgba(255,255,255,0.5); text-decoration:none; font-size:12px; font-weight:500; border-radius:6px; <?= basename($_SERVER['PHP_SELF']) == 'history.php' ? 'background:rgba(46,204,113,0.1); color:#2ecc71; border-left:3px solid #2ecc71;' : '' ?>">
        <span style="font-size:14px;">📜</span> History
    </a>
    <a href="recommendations.php" style="display:flex; align-items:center; gap:8px; padding:6px 12px; color:rgba(255,255,255,0.5); text-decoration:none; font-size:12px; font-weight:500; border-radius:6px; <?= basename($_SERVER['PHP_SELF']) == 'recommendations.php' ? 'background:rgba(46,204,113,0.1); color:#2ecc71; border-left:3px solid #2ecc71;' : '' ?>">
        <span style="font-size:14px;">💼</span> Rekomendasi
    </a>
    <a href="../auth/logout.php" style="display:flex; align-items:center; gap:8px; padding:6px 12px; color:rgba(255,255,255,0.3); text-decoration:none; font-size:12px; font-weight:500; border-radius:6px; margin-top:6px; border-top:1px solid rgba(255,255,255,0.04); padding-top:10px;">
        <span style="font-size:14px;">🚪</span> Logout
    </a>
</div>
<?php

// dashboard/dashboard.php

session_start();



if (!isset($_SESSION['user_id'])) {

    header('Location: ../auth/login.php');

    exit;

}



require_once '../config/database.php';



$page_title = 'Dashboard - Skill Gap Analyzer';



// Ambil data user

$stmt = $pdo->prepare("SELECT name FROM users WHERE id = ?");

$stmt->execute([$_SESSION['user_id']]);

$user = $stmt->fetch();



// Statistik

$totalSkills = $pdo->query("SELECT COUNT(*) FROM skills")->fetchColumn();

$totalIndustry = $pdo->query("SELECT COUNT(*) FROM industry_skills")->fetchColumn();



$stmt = $pdo->prepare("SELECT COUNT(*) FROM user_skills WHERE user_id = ?");

$stmt->execute([$_SESSION['user_id']]);

$userSkillCount = $stmt->fetchColumn();



$stmt = $pdo->prepare("SELECT COUNT(*) FROM analysis_history WHERE user_id = ?");

$stmt->execute([$_SESSION['user_id']]);

$historyCount = $stmt->fetchColumn();



// Trending

$trendingSkills = $pdo->query("SELECT * FROM trending_skills ORDER BY trend_score DESC LIMIT 6")->fetchAll();

$trendingJobs = $pdo->query("SELECT * FROM trending_jobs ORDER BY trend_score DESC LIMIT 6")->fetchAll();



// Ambil hasil analisis terakhir

$stmt = $pdo->prepare("SELECT * FROM analysis_history WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");

$stmt->execute([$_SESSION['user_id']]);

$lastAnalysis = $stmt->fetch();



include '../includes/header.php';

?>



<style>

/* ==========================================

   DASHBOARD MODERN - UKURAN SANGAT BESAR

   ========================================== */



/* Reset container untuk dashboard */

.container {

    max-width: 100% !important;

    border-radius: 0 !important;

    padding: 28px 56px 24px !important;

    min-height: 100vh !important;

}



.dashboard-wrapper {

    display: flex;

    gap: 32px;

    margin-top: 12px;

    flex: 1;

    min-height: 0;

}



.dashboard-main {

    flex: 1;

    display: flex;

    flex-direction: column;

    gap: 28px;

    overflow-y: auto;

    padding-right: 8px;

}



.dashboard-main::-webkit-scrollbar {

    width: 6px;

}



.dashboard-main::-webkit-scrollbar-track {

    background: rgba(255,255,255,0.02);

    border-radius: 10px;

}



.dashboard-main::-webkit-scrollbar-thumb {

    background: rgba(0,255,136,0.3);

    border-radius: 10px;

}



/* ==========================================

   HEADER DASHBOARD - BESAR SEKALI

   ========================================== */

.dashboard-header {

    display: flex;

    justify-content: space-between;

    align-items: center;

    flex-shrink: 0;

    padding-bottom: 8px;

    flex-wrap: wrap;

    gap: 16px;

}



.dashboard-header .left-section {

    display: flex;

    align-items: center;

    gap: 20px;

}



.dashboard-header .welcome-badge {

    background: linear-gradient(135deg, rgba(0,255,136,0.12), rgba(0,204,102,0.06));

    border: 1px solid rgba(0,255,136,0.12);

    padding: 10px 24px 10px 16px;

    border-radius: 100px;

    font-size: 16px;

    color: #00ff88;

    display: flex;

    align-items: center;

    gap: 10px;

}



.dashboard-header .welcome-badge .dot {

    width: 10px;

    height: 10px;

    background: #00ff88;

    border-radius: 50%;

    animation: pulse-dot 2s infinite;

}



@keyframes pulse-dot {

    0%, 100% { opacity: 1; transform: scale(1); }

    50% { opacity: 0.4; transform: scale(0.7); }

}



.dashboard-header h1 {

    color: #ffffff;

    font-size: 42px !important;

    font-weight: 900 !important;

    letter-spacing: -1px !important;

    margin: 0 !important;

}



.dashboard-header h1 span {

    background: linear-gradient(135deg, #00ff88, #00cc66);

    -webkit-background-clip: text;

    -webkit-text-fill-color: transparent;

    background-clip: text;

}



.dashboard-header .greeting {

    color: rgba(255,255,255,0.45);

    font-size: 20px !important;

    font-weight: 500;

}



.dashboard-header .greeting strong {

    color: rgba(255,255,255,0.85);

    font-size: 20px;

}



/* ==========================================

   STATS CARDS - BESAR SEKALI

   ========================================== */

.stats-grid {

    display: grid;

    grid-template-columns: repeat(4, 1fr);

    gap: 20px;

    flex-shrink: 0;

}



.stat-card {

    background: rgba(255,255,255,0.04);

    border: 1px solid rgba(255,255,255,0.06);

    border-radius: 20px;

    padding: 28px 32px;

    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);

    position: relative;

    overflow: hidden;

    display: flex;

    align-items: center;

    gap: 24px;

}



.stat-card::before {

    content: '';

    position: absolute;

    top: 0;

    left: 0;

    right: 0;

    height: 4px;

    background: linear-gradient(90deg, #00ff88, #00cc66);

    opacity: 0;

    transition: opacity 0.3s ease;

}



.stat-card:hover {

    transform: translateY(-4px);

    border-color: rgba(0,255,136,0.2);

    background: rgba(255,255,255,0.06);

    box-shadow: 0 12px 40px rgba(0,0,0,0.3);

}



.stat-card:hover::before {

    opacity: 1;

}



.stat-card .stat-icon-wrap {

    width: 64px;

    height: 64px;

    border-radius: 16px;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 30px;

    flex-shrink: 0;

}



.stat-card .stat-icon-wrap.green {

    background: rgba(0,255,136,0.12);

}



.stat-card .stat-icon-wrap.blue {

    background: rgba(0,200,255,0.12);

}



.stat-card .stat-icon-wrap.purple {

    background: rgba(155,89,182,0.12);

}



.stat-card .stat-icon-wrap.orange {

    background: rgba(255,200,0,0.12);

}



.stat-card .stat-info {

    flex: 1;

}



.stat-card .stat-number {

    font-size: 40px !important;

    font-weight: 900 !important;

    color: #ffffff;

    letter-spacing: -1px;

    line-height: 1.2;

}



.stat-card .stat-label {

    font-size: 18px !important;

    color: rgba(255,255,255,0.5);

    font-weight: 500;

}



.stat-card .stat-sub {

    font-size: 14px !important;

    color: rgba(255,255,255,0.2);

    margin-top: 4px;

}



.stat-card .stat-trend {

    font-size: 14px !important;

    font-weight: 600;

    padding: 4px 14px;

    border-radius: 20px;

    margin-left: auto;

}



.stat-card .stat-trend.up {

    background: rgba(0,255,136,0.12);

    color: #00ff88;

}



.stat-card .stat-trend.down {

    background: rgba(255,60,60,0.12);

    color: #ff6666;

}



/* ==========================================

   LAST ANALYSIS - BESAR SEKALI

   ========================================== */

.last-analysis {

    background: linear-gradient(135deg, rgba(0,255,136,0.06), rgba(0,204,102,0.03));

    border: 1px solid rgba(0,255,136,0.12);

    border-radius: 20px;

    padding: 24px 32px;

    flex-shrink: 0;

    transition: all 0.3s ease;

}



.last-analysis:hover {

    border-color: rgba(0,255,136,0.2);

    background: linear-gradient(135deg, rgba(0,255,136,0.08), rgba(0,204,102,0.05));

}



.last-analysis .analysis-content {

    display: flex;

    justify-content: space-between;

    align-items: center;

    flex-wrap: wrap;

    gap: 16px;

}



.last-analysis .analysis-left {

    display: flex;

    align-items: center;

    gap: 28px;

    flex-wrap: wrap;

}



.last-analysis .analysis-item {

    display: flex;

    align-items: center;

    gap: 16px;

}



.last-analysis .analysis-label {

    color: rgba(255,255,255,0.35);

    font-size: 17px !important;

    font-weight: 500;

}



.last-analysis .analysis-percentage {

    font-size: 44px !important;

    font-weight: 900 !important;

    color: #00ff88;

    letter-spacing: -1px;

}



.last-analysis .analysis-text {

    color: rgba(255,255,255,0.45);

    font-size: 18px !important;

}



.last-analysis .analysis-status {

    padding: 6px 20px;

    border-radius: 20px;

    font-size: 16px !important;

    font-weight: 600;

}



.last-analysis .analysis-status.high {

    background: rgba(0,255,136,0.15);

    color: #00ff88;

}



.last-analysis .analysis-status.medium {

    background: rgba(255,200,0,0.15);

    color: #ffcc00;

}



.last-analysis .analysis-status.low {

    background: rgba(255,60,60,0.15);

    color: #ff6666;

}



.last-analysis .analysis-action {

    display: flex;

    gap: 14px;

    align-items: center;

}



/* ==========================================

   TOMBOL LIHAT DETAIL - WARNA HIJAU TERANG

   ========================================== */

.btn-detail {

    display: inline-flex;

    align-items: center;

    gap: 8px;

    padding: 12px 28px;

    font-size: 16px !important;

    font-weight: 700 !important;

    font-family: 'Inter', sans-serif;

    background: linear-gradient(135deg, #00ff88, #00cc66) !important;

    color: #0a1628 !important;

    border: none !important;

    border-radius: 14px !important;

    cursor: pointer;

    text-decoration: none;

    transition: all 0.3s ease;

    box-shadow: 0 4px 25px rgba(0,255,136,0.35) !important;

}



.btn-detail:hover {

    transform: translateY(-3px);

    box-shadow: 0 8px 40px rgba(0,255,136,0.5) !important;

}



.btn-detail:active {

    transform: translateY(0);

}



/* ==========================================

   TRENDING SECTION - BESAR SEKALI

   ========================================== */

.trending-section {

    display: grid;

    grid-template-columns: 1fr 1fr;

    gap: 20px;

    flex: 1;

    min-height: 0;

}



.trending-box {

    background: rgba(255,255,255,0.03);

    border: 1px solid rgba(255,255,255,0.06);

    border-radius: 20px;

    padding: 24px 28px;

    display: flex;

    flex-direction: column;

    overflow: hidden;

    transition: all 0.3s ease;

}



.trending-box:hover {

    border-color: rgba(255,255,255,0.1);

    background: rgba(255,255,255,0.04);

}



.trending-box .trending-header {

    display: flex;

    justify-content: space-between;

    align-items: center;

    margin-bottom: 16px;

    flex-shrink: 0;

}



.trending-box .trending-header h3 {

    color: #ffffff;

    font-size: 22px !important;

    font-weight: 700 !important;

    display: flex;

    align-items: center;

    gap: 12px;

}



.trending-box .trending-header .trending-count {

    font-size: 15px !important;

    color: rgba(255,255,255,0.25);

    font-weight: 400;

    background: rgba(255,255,255,0.04);

    padding: 4px 16px;

    border-radius: 20px;

}



.trending-box .trending-list {

    flex: 1;

    overflow-y: auto;

    display: flex;

    flex-direction: column;

    gap: 4px;

}



.trending-box .trending-list::-webkit-scrollbar {

    width: 4px;

}



.trending-box .trending-list::-webkit-scrollbar-thumb {

    background: rgba(255,255,255,0.1);

    border-radius: 10px;

}



.trending-item {

    display: flex;

    justify-content: space-between;

    align-items: center;

    padding: 12px 16px;

    border-radius: 12px;

    transition: all 0.2s ease;

}



.trending-item:hover {

    background: rgba(255,255,255,0.04);

}



.trending-item .item-left {

    display: flex;

    align-items: center;

    gap: 14px;

}



.trending-item .item-rank {

    font-size: 15px !important;

    font-weight: 700;

    color: rgba(255,255,255,0.15);

    min-width: 28px;

}



.trending-item .item-name {

    font-size: 18px !important;

    color: rgba(255,255,255,0.8);

    font-weight: 500;

}



.trending-item .item-badge {

    padding: 4px 16px;

    border-radius: 14px;

    font-size: 15px !important;

    font-weight: 600;

}



.trending-item .item-badge.green {

    background: rgba(0,255,136,0.12);

    color: #00ff88;

}



.trending-item .item-badge.blue {

    background: rgba(0,200,255,0.12);

    color: #00ccff;

}



.trending-item .item-badge.purple {

    background: rgba(155,89,182,0.12);

    color: #9b59b6;

}



.trending-item .item-badge.orange {

    background: rgba(255,200,0,0.12);

    color: #ffcc00;

}



/* ==========================================

   ACTION BUTTONS - BESAR SEKALI

   ========================================== */

.actions-bar {

    display: grid;

    grid-template-columns: repeat(4, 1fr);

    gap: 16px;

    flex-shrink: 0;

    padding-top: 20px;

    border-top: 1px solid rgba(255,255,255,0.05);

}



.actions-bar .btn-action {

    padding: 20px 28px;

    font-size: 20px !important;

    font-weight: 700 !important;

    border-radius: 16px;

    border: none;

    cursor: pointer;

    text-decoration: none;

    transition: all 0.3s ease;

    display: inline-flex;

    align-items: center;

    justify-content: center;

    gap: 12px;

}



.actions-bar .btn-action.primary {

    background: linear-gradient(135deg, #00ff88, #00cc66);

    color: #0a1628;

    box-shadow: 0 4px 25px rgba(0,255,136,0.25);

}



.actions-bar .btn-action.primary:hover {

    transform: translateY(-4px);

    box-shadow: 0 10px 45px rgba(0,255,136,0.4);

}



.actions-bar .btn-action.secondary {

    background: rgba(255,255,255,0.06);

    color: rgba(255,255,255,0.85);

    border: 1px solid rgba(255,255,255,0.08);

}



.actions-bar .btn-action.secondary:hover {

    background: rgba(255,255,255,0.12);

    transform: translateY(-4px);

}



.actions-bar .btn-action.purple {

    background: linear-gradient(135deg, #8b5cf6, #7c3aed);

    color: #ffffff;

    box-shadow: 0 4px 25px rgba(139,92,246,0.25);

}



.actions-bar .btn-action.purple:hover {

    transform: translateY(-4px);

    box-shadow: 0 10px 45px rgba(139,92,246,0.35);

}



.actions-bar .btn-action.orange {

    background: linear-gradient(135deg, #f59e0b, #d97706);

    color: #ffffff;

    box-shadow: 0 4px 25px rgba(245,158,11,0.25);

}



.actions-bar .btn-action.orange:hover {

    transform: translateY(-4px);

    box-shadow: 0 10px 45px rgba(245,158,11,0.35);

}



/* ==========================================

   RESPONSIVE

   ========================================== */

@media (max-width: 1200px) {

    .container {

        padding: 24px 36px 20px !important;

    }

}



@media (max-width: 1024px) {

    .container {

        padding: 20px 28px 16px !important;

    }

    

    .stats-grid {

        grid-template-columns: repeat(2, 1fr);

    }

    

    .trending-section {

        grid-template-columns: 1fr;

    }

    

    .actions-bar {

        grid-template-columns: repeat(2, 1fr);

    }

    

    .dashboard-header h1 {

        font-size: 36px !important;

    }

    

    .stat-card .stat-number {

        font-size: 34px !important;

    }

}



@media (max-width: 768px) {

    .container {

        padding: 16px 18px 14px !important;

    }

    

    .dashboard-header {

        flex-direction: column;

        align-items: flex-start;

        gap: 12px;

    }

    

    .dashboard-header .left-section {

        flex-wrap: wrap;

    }

    

    .dashboard-header h1 {

        font-size: 30px !important;

    }

    

    .dashboard-header .greeting {

        font-size: 17px !important;

    }

    

    .dashboard-header .greeting strong {

        font-size: 17px;

    }

    

    .stats-grid {

        grid-template-columns: repeat(2, 1fr);

        gap: 14px;

    }

    

    .stat-card {

        padding: 18px 20px;

        gap: 16px;

        border-radius: 16px;

    }

    

    .stat-card .stat-icon-wrap {

        width: 50px;

        height: 50px;

        font-size: 24px;

    }

    

    .stat-card .stat-number {

        font-size: 28px !important;

    }

    

    .stat-card .stat-label {

        font-size: 15px !important;

    }

    

    .last-analysis .analysis-content {

        flex-direction: column;

        align-items: flex-start;

    }

    

    .last-analysis .analysis-left {

        flex-direction: column;

        align-items: flex-start;

        gap: 12px;

    }

    

    .last-analysis .analysis-percentage {

        font-size: 36px !important;

    }

    

    .last-analysis .analysis-text {

        font-size: 16px !important;

    }

    

    .actions-bar {

        grid-template-columns: 1fr 1fr;

        gap: 10px;

    }

    

    .actions-bar .btn-action {

        padding: 14px 20px;

        font-size: 16px !important;

    }

    

    .btn-detail {

        padding: 10px 22px;

        font-size: 14px !important;

    }

}



@media (max-width: 480px) {

    .container {

        padding: 12px 14px 10px !important;

    }

    

    .dashboard-header h1 {

        font-size: 24px !important;

    }

    

    .dashboard-header .greeting {

        font-size: 14px !important;

    }

    

    .dashboard-header .greeting strong {

        font-size: 14px;

    }

    

    .dashboard-header .welcome-badge {

        font-size: 13px;

        padding: 6px 16px 6px 12px;

    }

    

    .dashboard-header .welcome-badge .dot {

        width: 8px;

        height: 8px;

    }

    

    .stats-grid {

        grid-template-columns: 1fr 1fr;

        gap: 10px;

    }

    

    .stat-card {

        padding: 14px 16px;

        gap: 12px;

        border-radius: 14px;

    }

    

    .stat-card .stat-icon-wrap {

        width: 42px;

        height: 42px;

        font-size: 20px;

        border-radius: 12px;

    }

    

    .stat-card .stat-number {

        font-size: 24px !important;

    }

    

    .stat-card .stat-label {

        font-size: 13px !important;

    }

    

    .stat-card .stat-sub {

        font-size: 11px !important;

    }

    

    .stat-card .stat-trend {

        font-size: 11px !important;

        padding: 2px 10px;

        display: none;

    }

    

    .last-analysis {

        padding: 16px 18px;

        border-radius: 16px;

    }

    

    .last-analysis .analysis-percentage {

        font-size: 30px !important;

    }

    

    .last-analysis .analysis-text {

        font-size: 14px !important;

    }

    

    .last-analysis .analysis-label {

        font-size: 14px !important;

    }

    

    .last-analysis .analysis-status {

        font-size: 13px !important;

        padding: 4px 16px;

    }

    

    .trending-box {

        padding: 16px 18px;

        border-radius: 16px;

    }

    

    .trending-box .trending-header h3 {

        font-size: 18px !important;

    }

    

    .trending-item .item-name {

        font-size: 15px !important;

    }

    

    .trending-item .item-badge {

        font-size: 13px !important;

        padding: 3px 12px;

    }

    

    .trending-item .item-rank {

        font-size: 13px !important;

        min-width: 22px;

    }

    

    .actions-bar {

        grid-template-columns: 1fr 1fr;

        gap: 8px;

        padding-top: 14px;

    }

    

    .actions-bar .btn-action {

        padding: 12px 16px;

        font-size: 14px !important;

        border-radius: 12px;

        gap: 8px;

    }

    

    .btn-detail {

        padding: 8px 16px;

        font-size: 12px !important;

        border-radius: 10px;

    }

}

</style>



<!-- ==========================================

     DASHBOARD CONTENT

========================================== -->

<div class="dashboard-wrapper">

    <?php include '../includes/sidebar.php'; ?>

    

    <div class="dashboard-main">

        

        <!-- Header -->

        <div class="dashboard-header">

            <div class="left-section">

                <h1>📊 <span>Dashboard</span></h1>

                <div class="welcome-badge">

                    <span class="dot"></span>

                    Active

                </div>

            </div>

            <div class="greeting">

                👋 Selamat datang, <strong><?= htmlspecialchars($user['name']) ?></strong>

            </div>

        </div>



        <!-- Stats Cards -->

        <div class="stats-grid">

            <div class="stat-card">

                <div class="stat-icon-wrap green">📚</div>

                <div class="stat-info">

                    <div class="stat-number"><?= $totalSkills ?></div>

                    <div class="stat-label">Total Skill</div>

                    <div class="stat-sub">Di database</div>

                </div>

            </div>

            <div class="stat-card">

                <div class="stat-icon-wrap blue">🏢</div>

                <div class="stat-info">

                    <div class="stat-number"><?= $totalIndustry ?></div>

                    <div class="stat-label">Skill Industri</div>

                    <div class="stat-sub">Dibutuhkan</div>

                </div>

            </div>

            <div class="stat-card">

                <div class="stat-icon-wrap purple">✅</div>

                <div class="stat-info">

                    <div class="stat-number"><?= $userSkillCount ?></div>

                    <div class="stat-label">Skill Anda</div>

                    <div class="stat-sub">Sudah diinput</div>

                </div>

                <span class="stat-trend up">↑ +12%</span>

            </div>

            <div class="stat-card">

                <div class="stat-icon-wrap orange">📈</div>

                <div class="stat-info">

                    <div class="stat-number"><?= $historyCount ?></div>

                    <div class="stat-label">Analisis</div>

                    <div class="stat-sub">Pernah dilakukan</div>

                </div>

            </div>

        </div>



        <!-- Last Analysis -->

        <?php if ($lastAnalysis): ?>

        <div class="last-analysis">

            <div class="analysis-content">

                <div class="analysis-left">

                    <div class="analysis-item">

                        <span class="analysis-label">📊 Hasil Analisis Terakhir</span>

                    </div>

                    <div class="analysis-item">

                        <span class="analysis-percentage"><?= $lastAnalysis['match_percentage'] ?>%</span>

                        <span class="analysis-text">Tingkat Kesesuaian</span>

                    </div>

                    <span class="analysis-status <?= $lastAnalysis['match_percentage'] >= 70 ? 'high' : ($lastAnalysis['match_percentage'] >= 50 ? 'medium' : 'low') ?>">

                        <?= $lastAnalysis['match_percentage'] >= 70 ? '✅ Baik' : ($lastAnalysis['match_percentage'] >= 50 ? '⚡ Sedang' : '⚠️ Perlu Perbaikan') ?>

                    </span>

                </div>

                <div class="analysis-action">

                    <span style="font-size:11px; color:rgba(255,255,255,0.15);">

                        <?= date('d M Y', strtotime($lastAnalysis['created_at'])) ?>

                    </span>

                    <!-- TOMBOL LIHAT DETAIL - WARNA HIJAU TERANG -->

                    <a href="analysis.php" class="btn-detail">

                        Lihat Detail →

                    </a>

                </div>

            </div>

        </div>

        <?php endif; ?>



        <!-- Trending Section -->

        <div class="trending-section">

            <!-- Trending Skills -->

            <div class="trending-box">

                <div class="trending-header">

                    <h3>🔥 Skill Hype</h3>

                    <span class="trending-count">Top <?= count($trendingSkills) ?></span>

                </div>

                <div class="trending-list">

                    <?php foreach ($trendingSkills as $index => $skill): ?>

                        <div class="trending-item">

                            <div class="item-left">

                                <span class="item-rank">#<?= $index + 1 ?></span>

                                <span class="item-name"><?= htmlspecialchars($skill['skill_name']) ?></span>

                            </div>

                            <span class="item-badge green"><?= $skill['trend_score'] ?>%</span>

                        </div>

                    <?php endforeach; ?>

                </div>

            </div>



            <!-- Trending Jobs -->

            <div class="trending-box">

                <div class="trending-header">

                    <h3>💼 Pekerjaan Hype</h3>

                    <span class="trending-count">Top <?= count($trendingJobs) ?></span>

                </div>

                <div class="trending-list">

                    <?php foreach ($trendingJobs as $index => $job): ?>

                        <div class="trending-item">

                            <div class="item-left">

                                <span class="item-rank">#<?= $index + 1 ?></span>

                                <span class="item-name"><?= htmlspecialchars($job['job_title']) ?></span>

                            </div>

                            <span class="item-badge blue"><?= htmlspecialchars($job['company_name']) ?></span>

                        </div>

                    <?php endforeach; ?>

                </div>

            </div>

        </div>



        <!-- Action Buttons -->

        <div class="actions-bar">

            <a href="skills.php" class="btn-action primary">✏️ Input Skill</a>

            <a href="analysis.php" class="btn-action primary">📊 Analisis Gap</a>

            <a href="recommendations.php" class="btn-action purple">💼 Rekomendasi</a>

            <a href="history.php" class="btn-action orange">📜 History</a>

        </div>



    </div>

</div>


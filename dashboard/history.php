<?php
// dashboard/history.php

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

require_once '../config/database.php';

$page_title = 'History Analisis - Skill Gap Analyzer';

$stmt = $pdo->prepare("
    SELECT *
    FROM analysis_history
    WHERE user_id = ?
    ORDER BY created_at DESC
");

$stmt->execute([$_SESSION['user_id']]);
$history = $stmt->fetchAll();

include '../includes/header.php';
?>

<div class="dashboard-container">

    <?php include '../includes/sidebar.php'; ?>

    <div class="main-content">

        <div class="page-header">
            <h1>📜 History Analisis</h1>
            <p>Riwayat seluruh analisis skill gap yang pernah dilakukan.</p>
        </div>

        <?php if(count($history) > 0): ?>

            <div class="history-list">

                <?php foreach($history as $item): ?>

                    <?php
                    $matchedSkills = json_decode($item['matched_skills'] ?? '[]', true);
                    $gapSkills = json_decode($item['gap_skills'] ?? '[]', true);

                    $matchedCount = is_array($matchedSkills) ? count($matchedSkills) : 0;
                    $gapCount = is_array($gapSkills) ? count($gapSkills) : 0;
                    ?>

                    <div class="history-card">

                        <div class="history-row">

                            <div class="history-score">
                                <h2><?= $item['match_percentage'] ?>%</h2>
                                <span>Tingkat Kesesuaian</span>
                            </div>

                            <div class="history-stat success">
                                <h3><?= $matchedCount ?></h3>
                                <p>Skill Sesuai</p>
                            </div>

                            <div class="history-stat danger">
                                <h3><?= $gapCount ?></h3>
                                <p>Skill Kurang</p>
                            </div>

                            <div class="history-date">
                                <?= date('d M Y Y • H:i', strtotime($item['created_at'])) ?>
                            </div>

                        </div>

                        <div class="progress-container">
                            <div class="progress-bar">
                                <div class="progress-fill"
                                     style="width: <?= $item['match_percentage'] ?>%">
                                </div>
                            </div>
                        </div>

                    </div>

                <?php endforeach; ?>

            </div>

        <?php else: ?>

            <div class="empty-state">

                <div class="empty-icon">📭</div>

                <h2>Belum Ada Riwayat Analisis</h2>

                <p>
                    Anda belum memiliki data history analisis skill gap.
                </p>

                <a href="analysis.php" class="btn-start">
                    📊 Mulai Analisis
                </a>

            </div>

        <?php endif; ?>

    </div>

</div>

<style>

.dashboard-container{
    display:flex;
    width:100%;
    min-height:100vh;
}

.main-content{
    flex:1;
    padding:30px;
}

.page-header{
    margin-bottom:25px;
}

.page-header h1{
    color:#ffffff;
    font-size:34px;
    margin-bottom:8px;
}

.page-header p{
    color:rgba(255,255,255,0.6);
    font-size:16px;
}

.history-list{
    display:flex;
    flex-direction:column;
    gap:20px;
    width:100%;
}

.history-card{
    width:100%;
    background:rgba(255,255,255,0.03);
    border:1px solid rgba(255,255,255,0.08);
    border-radius:18px;
    padding:25px;
    transition:0.3s;
}

.history-card:hover{
    transform:translateY(-3px);
    border-color:#2ecc71;
}

.history-row{
    display:grid;
    grid-template-columns:
        250px
        180px
        180px
        1fr;
    gap:20px;
    align-items:center;
}

.history-score h2{
    margin:0;
    font-size:52px;
    color:#2ecc71;
    font-weight:800;
}

.history-score span{
    color:rgba(255,255,255,0.5);
    font-size:14px;
}

.history-stat{
    background:rgba(255,255,255,0.04);
    border-radius:14px;
    padding:15px;
    text-align:center;
}

.history-stat h3{
    margin:0;
    font-size:32px;
    font-weight:700;
}

.history-stat p{
    margin-top:5px;
    color:rgba(255,255,255,0.5);
    font-size:14px;
}

.success h3{
    color:#2ecc71;
}

.danger h3{
    color:#ff6b6b;
}

.history-date{
    text-align:right;
    color:rgba(255,255,255,0.5);
    font-size:14px;
}

.progress-container{
    margin-top:25px;
}

.progress-bar{
    width:100%;
    height:12px;
    background:rgba(255,255,255,0.08);
    border-radius:999px;
    overflow:hidden;
}

.progress-fill{
    height:100%;
    background:linear-gradient(
        90deg,
        #00c896,
        #2ecc71
    );
}

.empty-state{
    text-align:center;
    padding:80px 20px;
    background:rgba(255,255,255,0.03);
    border:1px solid rgba(255,255,255,0.08);
    border-radius:18px;
}

.empty-icon{
    font-size:80px;
    margin-bottom:20px;
}

.empty-state h2{
    color:#fff;
    margin-bottom:10px;
}

.empty-state p{
    color:rgba(255,255,255,0.5);
    margin-bottom:25px;
}

.btn-start{
    display:inline-block;
    background:#2ecc71;
    color:#fff;
    text-decoration:none;
    padding:12px 25px;
    border-radius:10px;
    font-weight:600;
}

.btn-start:hover{
    opacity:0.9;
}

@media(max-width:1200px){

    .history-row{
        grid-template-columns:1fr 1fr;
    }

    .history-date{
        text-align:left;
    }

}

@media(max-width:768px){

    .dashboard-container{
        flex-direction:column;
    }

    .history-row{
        grid-template-columns:1fr;
    }

    .history-date{
        text-align:left;
    }

    .history-score h2{
        font-size:42px;
    }

}

</style>


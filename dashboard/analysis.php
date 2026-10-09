<?php
// dashboard/analysis.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}
require_once '../config/database.php';

$page_title = 'Analisis Skill Gap - Skill Gap Analyzer';

// ==========================================
// 1. AMBIL DATA USER SKILL
// ==========================================
$stmt = $pdo->prepare("
    SELECT s.skill_name, s.category, us.proficiency_level, us.description 
    FROM user_skills us 
    JOIN skills s ON us.skill_id = s.id 
    WHERE us.user_id = ?
");
$stmt->execute([$_SESSION['user_id']]);
$userSkills = $stmt->fetchAll();

// ==========================================
// 2. AMBIL SKILL INDUSTRI
// ==========================================
$industrySkills = $pdo->query("
    SELECT skill_name, category, demand_level 
    FROM industry_skills
")->fetchAll();

// ==========================================
// 3. PROSES ANALISIS 3 ATURAN
// ==========================================
$matched = [];
$partialMatch = [];
$gap = [];

$userSkillNames = array_column($userSkills, 'skill_name');
$userSkillCategories = array_column($userSkills, 'category');

$totalWeightedScore = 0;
$totalSkills = count($industrySkills);

foreach ($industrySkills as $industry) {
    $industryName = $industry['skill_name'];
    $industryCategory = $industry['category'] ?? '';
    $demandLevel = $industry['demand_level'] ?? 'Medium';
    
    if (in_array($industryName, $userSkillNames)) {
        $matched[] = $industryName;
        $totalWeightedScore += 100;
    }
    elseif (!empty($industryCategory) && in_array($industryCategory, $userSkillCategories)) {
        $baseScore = ($demandLevel == 'High') ? 70 : (($demandLevel == 'Low') ? 60 : 65);
        $partialMatch[] = $industryName;
        $totalWeightedScore += $baseScore;
    }
    else {
        $gap[] = $industryName;
    }
}

$maxScore = $totalSkills * 100;
$percentage = $totalSkills > 0 ? round(($totalWeightedScore / $maxScore) * 100) : 0;

$matchCount = count($matched);
$partialCount = count($partialMatch);
$gapCount = count($gap);

// ==========================================
// 4. CEK HISTORY TERAKHIR
// ==========================================
$stmt = $pdo->prepare("SELECT * FROM analysis_history WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");
$stmt->execute([$_SESSION['user_id']]);
$lastAnalysis = $stmt->fetch();

include '../includes/header.php';
?>

<style>
.results-summary {
    background: linear-gradient(135deg, rgba(0,184,148,0.08), rgba(0,210,211,0.04));
    border: 1px solid rgba(0,184,148,0.1);
    border-radius: 16px;
    padding: 24px 32px;
    text-align: center;
}
.results-summary .percentage {
    font-size: 52px;
    font-weight: 900;
    background: linear-gradient(135deg, #ffffff, #55efc4);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    line-height: 1;
}
.results-summary .label {
    color: rgba(255,255,255,0.4);
    font-size: 14px;
    margin-top: 4px;
}
.results-summary .stats {
    display: flex;
    justify-content: center;
    gap: 20px;
    margin-top: 12px;
    flex-wrap: wrap;
}
.results-summary .stats .match { color: #2ecc71; }
.results-summary .stats .partial { color: #f39c12; }
.results-summary .stats .gap { color: #e74c3c; }
.results-summary .stats .total { color: rgba(255,255,255,0.3); }

.detail-box {
    border-radius: 12px;
    padding: 16px 18px;
}
.detail-box ul {
    list-style: none;
    padding: 0;
    margin: 0;
    max-height: 180px;
    overflow-y: auto;
}
.detail-box ul li {
    padding: 3px 0;
    color: rgba(255,255,255,0.7);
    font-size: 13px;
    border-bottom: 1px solid rgba(255,255,255,0.03);
}
.detail-box ul li .badge {
    background: rgba(231,76,60,0.15);
    color: #e74c3c;
    padding: 0 8px;
    border-radius: 10px;
    font-size: 10px;
    margin-left: 6px;
}
.detail-box ul li .score {
    color: #f39c12;
    font-size: 11px;
    float: right;
}
.detail-box .empty {
    color: rgba(255,255,255,0.3);
    font-size: 13px;
}
</style>

<div style="display:flex; gap:24px; margin-top:20px; flex:1; min-height:0;">
    <?php include '../includes/sidebar.php'; ?>
    
    <div style="flex:1; display:flex; flex-direction:column; gap:16px; overflow-y:auto; padding-right:4px;">

        <div style="display:flex; justify-content:space-between; align-items:center; flex-shrink:0;">
            <h2 style="color:#ffffff; font-size:22px;">📊 Analisis Skill Gap</h2>
            <?php if (isset($_GET['saved'])): ?>
                <span style="background:rgba(46,204,113,0.1); color:#2ecc71; padding:4px 16px; border-radius:20px; font-size:13px;">✅ Disimpan</span>
            <?php endif; ?>
        </div>

        <?php if (empty($userSkills)): ?>
            <div style="background:rgba(241,196,15,0.05); border:1px solid rgba(241,196,15,0.1); border-radius:12px; padding:30px; text-align:center;">
                <span style="font-size:48px; display:block; margin-bottom:10px;">⚠️</span>
                <p style="color:rgba(255,255,255,0.5); font-size:16px;">Anda belum menginput skill.</p>
                <p style="color:rgba(255,255,255,0.2); font-size:14px;">Silakan input skill terlebih dahulu untuk melakukan analisis.</p>
                <a href="skills.php" class="btn btn-primary" style="margin-top:15px; display:inline-block;">✏️ Input Skill</a>
            </div>
        <?php else: ?>

            <!-- ===== SUMMARY ===== -->
            <div class="results-summary">
                <div class="percentage"><?= $percentage ?>%</div>
                <div class="label">Tingkat Kesesuaian Skill Anda</div>
                <div class="stats">
                    <span class="match">✅ <?= $matchCount ?> skill sesuai (100%)</span>
                    <span class="partial">🔄 <?= $partialCount ?> skill sesuai sebagian (60-70%)</span>
                    <span class="gap">❌ <?= $gapCount ?> skill kurang (0%)</span>
                    <span class="total">📊 Total: <?= $totalSkills ?> skill</span>
                </div>
            </div>

            <!-- ===== GRAFIK ===== -->
            <div style="background:rgba(255,255,255,0.02); border:1px solid rgba(255,255,255,0.04); border-radius:14px; padding:20px; flex-shrink:0;">
                <h3 style="color:#ffffff; font-size:16px; margin-bottom:12px;">📈 Visualisasi Skill Gap</h3>
                <canvas id="skillChart" height="180"></canvas>
            </div>

            <!-- ===== 3 KATEGORI ===== -->
            <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:16px; flex-shrink:0;">
                
                <div class="detail-box" style="background:rgba(46,204,113,0.04); border:1px solid rgba(46,204,113,0.1);">
                    <h4 style="color:#2ecc71; font-size:15px; margin-bottom:8px;">✅ Sesuai (100%)</h4>
                    <?php if ($matchCount > 0): ?>
                        <ul>
                            <?php foreach ($matched as $skill): ?>
                                <li>✅ <?= htmlspecialchars($skill) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="empty">Belum ada skill yang sesuai.</p>
                    <?php endif; ?>
                </div>

                <div class="detail-box" style="background:rgba(241,196,15,0.04); border:1px solid rgba(241,196,15,0.1);">
                    <h4 style="color:#f39c12; font-size:15px; margin-bottom:8px;">🔄 Sesuai Sebagian (60-70%)</h4>
                    <?php if ($partialCount > 0): ?>
                        <ul>
                            <?php foreach ($partialMatch as $skill): ?>
                                <li>🔄 <?= htmlspecialchars($skill) ?> <span class="score">65%</span></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="empty">Tidak ada skill yang sesuai sebagian.</p>
                    <?php endif; ?>
                </div>

                <div class="detail-box" style="background:rgba(231,76,60,0.04); border:1px solid rgba(231,76,60,0.1);">
                    <h4 style="color:#e74c3c; font-size:15px; margin-bottom:8px;">❌ Kurang (0%)</h4>
                    <?php if ($gapCount > 0): ?>
                        <ul>
                            <?php foreach ($gap as $skill): ?>
                                <li>❌ <?= htmlspecialchars($skill) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="empty">🎉 Semua skill sudah sesuai!</p>
                    <?php endif; ?>
                </div>

            </div>

            <!-- ===== TOMBOL ===== -->
            <div style="display:flex; gap:12px; flex-shrink:0; padding-top:4px; border-top:1px solid rgba(255,255,255,0.04); flex-wrap:wrap;">
                <a href="../process/analyze_skill_gap.php" class="btn btn-primary" style="padding:8px 20px; font-size:14px;">🔄 Analisis Ulang</a>
                <a href="recommendations.php" class="btn btn-primary" style="padding:8px 20px; font-size:14px; background:linear-gradient(135deg,#00b894,#00d2d3);">💼 Lihat Rekomendasi</a>
                <a href="skills.php" class="btn btn-secondary" style="padding:8px 20px; font-size:14px;">✏️ Update Skill</a>
            </div>

        <?php endif; ?>
    </div>
</div>

<?php if (!empty($userSkills)): ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    new Chart(document.getElementById('skillChart'), {
        type: 'bar',
        data: {
            labels: ['Skill Sesuai (100%)', 'Skill Sesuai Sebagian (60-70%)', 'Skill Kurang (0%)'],
            datasets: [{
                data: [<?= $matchCount ?>, <?= $partialCount ?>, <?= $gapCount ?>],
                backgroundColor: ['#2ecc71', '#f39c12', '#e74c3c'],
                borderColor: ['#27ae60', '#d68910', '#c0392b'],
                borderWidth: 2,
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1, color: 'rgba(255,255,255,0.3)' },
                    grid: { color: 'rgba(255,255,255,0.03)' }
                },
                x: {
                    ticks: { color: 'rgba(255,255,255,0.5)', font: { size: 11 } },
                    grid: { display: false }
                }
            }
        }
    });
});
</script>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>
<?php
// dashboard/skills.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}
require_once '../config/database.php';

$page_title = 'Input Skill - Skill Gap Analyzer';

// Ambil semua skill dari database
$skills = $pdo->query("SELECT * FROM skills ORDER BY skill_name")->fetchAll();

// Ambil skill user dengan deskripsi
$stmt = $pdo->prepare("SELECT skill_id, proficiency_level, description FROM user_skills WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$userSkillsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Buat array untuk cek skill yang sudah dipilih
$userSkillIds = [];
$userSkillLevels = [];
$userSkillDescriptions = [];
foreach ($userSkillsData as $data) {
    $userSkillIds[] = $data['skill_id'];
    $userSkillLevels[$data['skill_id']] = $data['proficiency_level'];
    $userSkillDescriptions[$data['skill_id']] = $data['description'] ?? '';
}

include '../includes/header.php';
?>

<style>
/* ==========================================
   SKILLS PAGE - 2 KOLOM LIST - BESAR
   ========================================== */

.skills-wrapper {
    display: flex;
    gap: 32px;
    margin-top: 12px;
    flex: 1;
    min-height: 0;
}

.skills-main {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 20px;
    width: 100%;
    min-height: 0;
}

/* ===== HEADER ===== */
.skills-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
    padding-bottom: 14px;
    border-bottom: 1px solid rgba(255,255,255,0.06);
    width: 100%;
    flex-shrink: 0;
}

.skills-header h1 {
    color: #ffffff;
    font-size: 48px !important;
    font-weight: 900 !important;
    letter-spacing: -1.5px !important;
    margin: 0 !important;
}

.skills-header h1 span {
    background: linear-gradient(135deg, #00ff88, #00cc66);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.skills-header .skill-count {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    background: rgba(0, 255, 136, 0.08);
    border: 1px solid rgba(0, 255, 136, 0.12);
    padding: 14px 32px;
    border-radius: 50px;
    font-size: 20px;
    font-weight: 600;
    color: #00ff88;
}

/* ===== ALERT ===== */
.alert-box {
    padding: 18px 24px;
    border-radius: 14px;
    font-size: 18px;
    font-weight: 500;
    border: 1px solid;
    width: 100%;
    flex-shrink: 0;
}

.alert-box.success {
    background: rgba(0, 255, 136, 0.08);
    color: #00ff88;
    border-color: rgba(0, 255, 136, 0.15);
}

.alert-box.error {
    background: rgba(255, 60, 60, 0.08);
    color: #ff6666;
    border-color: rgba(255, 60, 60, 0.15);
}

/* ===== TOMBOL AKSI ===== */
.action-buttons {
    display: flex;
    gap: 14px;
    flex-wrap: wrap;
    width: 100%;
    flex-shrink: 0;
}

.btn-action {
    padding: 16px 32px;
    font-size: 19px !important;
    font-weight: 600 !important;
    border-radius: 14px;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    font-family: 'Inter', sans-serif;
    display: inline-flex;
    align-items: center;
    gap: 10px;
}

.btn-action.primary {
    background: linear-gradient(135deg, #00ff88, #00cc66);
    color: #0a1628;
    box-shadow: 0 4px 20px rgba(0, 255, 136, 0.25);
}

.btn-action.primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 35px rgba(0, 255, 136, 0.4);
}

.btn-action.secondary {
    background: rgba(255, 255, 255, 0.06);
    color: rgba(255, 255, 255, 0.85);
    border: 1px solid rgba(255, 255, 255, 0.08);
}

.btn-action.secondary:hover {
    background: rgba(255, 255, 255, 0.12);
    transform: translateY(-3px);
}

/* ===== FORM KUSTOM ===== */
.custom-skill-form {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(0, 255, 136, 0.12);
    border-radius: 20px;
    padding: 32px 36px;
    display: none;
    width: 100%;
    flex-shrink: 0;
}

.custom-skill-form.active {
    display: block;
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

.custom-skill-form h3 {
    color: #ffffff;
    font-size: 26px !important;
    font-weight: 700 !important;
    margin: 0 0 6px 0;
}

.custom-skill-form .subtitle {
    color: rgba(255, 255, 255, 0.4);
    font-size: 18px !important;
    margin: 0 0 22px 0;
}

.custom-skill-form .form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.custom-skill-form .form-group {
    margin-bottom: 0;
}

.custom-skill-form .form-group label {
    display: block;
    font-size: 18px !important;
    font-weight: 500;
    color: rgba(255, 255, 255, 0.75);
    margin-bottom: 8px;
}

.custom-skill-form .form-group label .required {
    color: #ff6666;
}

.custom-skill-form .form-group input,
.custom-skill-form .form-group select,
.custom-skill-form .form-group textarea {
    width: 100%;
    padding: 16px 20px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 12px;
    color: #ffffff;
    font-size: 19px;
    font-family: 'Inter', sans-serif;
    transition: all 0.3s ease;
    outline: none;
}

.custom-skill-form .form-group input:focus,
.custom-skill-form .form-group select:focus,
.custom-skill-form .form-group textarea:focus {
    border-color: #00ff88;
    box-shadow: 0 0 0 4px rgba(0, 255, 136, 0.06);
}

.custom-skill-form .form-actions {
    display: flex;
    gap: 14px;
    margin-top: 18px;
}

/* ===== SKILL LIST WRAPPER - FULL HEIGHT ===== */
.skill-list-wrapper {
    background: rgba(255, 255, 255, 0.02);
    border-radius: 20px;
    padding: 12px;
    width: 100%;
    flex: 1;
    min-height: 0;
    display: flex;
    flex-direction: column;
}

/* ===== SKILL LIST - 2 KOLOM ===== */
.skill-list {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    flex: 1;
    overflow-y: auto;
    padding: 6px;
    align-content: start;
}

.skill-list::-webkit-scrollbar {
    width: 6px;
}

.skill-list::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.02);
    border-radius: 10px;
}

.skill-list::-webkit-scrollbar-thumb {
    background: rgba(0, 255, 136, 0.25);
    border-radius: 10px;
}

/* ===== SKILL ITEM ===== */
.skill-item {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.05);
    border-radius: 14px;
    padding: 18px 22px;
    transition: all 0.25s ease;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.skill-item:hover {
    background: rgba(255, 255, 255, 0.06);
    border-color: rgba(255, 255, 255, 0.10);
}

.skill-item.selected {
    border-color: rgba(0, 255, 136, 0.25);
    background: rgba(0, 255, 136, 0.05);
}

.skill-item .skill-row {
    display: flex;
    align-items: center;
    gap: 14px;
    width: 100%;
}

.skill-item .skill-icon {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 21px;
    flex-shrink: 0;
}

.skill-item .skill-icon.green { background: rgba(0, 255, 136, 0.12); }
.skill-item .skill-icon.blue { background: rgba(0, 200, 255, 0.12); }
.skill-item .skill-icon.purple { background: rgba(155, 89, 182, 0.12); }
.skill-item .skill-icon.orange { background: rgba(255, 200, 0, 0.12); }
.skill-item .skill-icon.red { background: rgba(255, 60, 60, 0.12); }
.skill-item .skill-icon.pink { background: rgba(255, 100, 200, 0.12); }
.skill-item .skill-icon.teal { background: rgba(0, 200, 200, 0.12); }
.skill-item .skill-icon.indigo { background: rgba(100, 100, 255, 0.12); }

.skill-item input[type="checkbox"] {
    width: 24px;
    height: 24px;
    accent-color: #00ff88;
    cursor: pointer;
    flex-shrink: 0;
}

.skill-item .skill-name {
    flex: 1;
    font-size: 20px !important;
    color: rgba(255, 255, 255, 0.88);
    cursor: pointer;
    font-weight: 600;
    min-width: 60px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.skill-item .skill-level-wrapper {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-shrink: 0;
}

.skill-item .level-badge {
    display: inline-flex;
    align-items: center;
    padding: 5px 18px;
    border-radius: 20px;
    font-size: 16px;
    font-weight: 600;
    white-space: nowrap;
}

.skill-item .level-badge.beginner {
    background: rgba(0, 200, 255, 0.12);
    color: #00ccff;
}

.skill-item .level-badge.intermediate {
    background: rgba(255, 200, 0, 0.12);
    color: #ffcc00;
}

.skill-item .level-badge.advanced {
    background: rgba(0, 255, 136, 0.12);
    color: #00ff88;
}

.skill-item select {
    padding: 6px 14px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.06);
    border-radius: 8px;
    color: #ffffff;
    font-size: 17px;
    font-family: 'Inter', sans-serif;
    cursor: pointer;
    outline: none;
}

.skill-item select:focus {
    border-color: #00ff88;
}

/* ===== SKILL DESC - SELALU TAMPIL ===== */
.skill-desc-wrapper {
    padding-left: 58px;
    width: 100%;
}

.skill-desc-wrapper textarea {
    width: 100%;
    padding: 10px 14px;
    background: rgba(255, 255, 255, 0.02);
    border: 1px solid rgba(255, 255, 255, 0.05);
    border-radius: 8px;
    color: rgba(255, 255, 255, 0.65);
    font-size: 17px;
    font-family: 'Inter', sans-serif;
    resize: vertical;
    outline: none;
    transition: all 0.3s ease;
    min-height: 44px;
}

.skill-desc-wrapper textarea:focus {
    border-color: rgba(0, 255, 136, 0.2);
}

.skill-desc-wrapper .hint {
    color: rgba(255, 255, 255, 0.10);
    font-size: 13px !important;
    display: block;
    margin-top: 3px;
}

/* ===== TOMBOL SIMPAN ===== */
.save-section {
    display: flex;
    gap: 16px;
    margin-top: 12px;
    flex-wrap: wrap;
    width: 100%;
    flex-shrink: 0;
}

.btn-save {
    padding: 18px 44px;
    font-size: 21px !important;
    font-weight: 700 !important;
    border-radius: 16px;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    font-family: 'Inter', sans-serif;
    display: inline-flex;
    align-items: center;
    gap: 12px;
}

.btn-save.primary {
    background: linear-gradient(135deg, #00ff88, #00cc66);
    color: #0a1628;
    box-shadow: 0 4px 25px rgba(0, 255, 136, 0.25);
}

.btn-save.primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 40px rgba(0, 255, 136, 0.4);
}

.btn-save.secondary {
    background: rgba(255, 255, 255, 0.06);
    color: rgba(255, 255, 255, 0.85);
    border: 1px solid rgba(255, 255, 255, 0.08);
}

.btn-save.secondary:hover {
    background: rgba(255, 255, 255, 0.12);
    transform: translateY(-3px);
}

/* ===== TIPS BOX ===== */
.tips-box {
    background: linear-gradient(135deg, rgba(0, 200, 255, 0.04), rgba(0, 255, 136, 0.02));
    border: 1px solid rgba(0, 200, 255, 0.08);
    border-radius: 16px;
    padding: 18px 26px;
    width: 100%;
    flex-shrink: 0;
}

.tips-box p {
    color: rgba(255, 255, 255, 0.45);
    font-size: 18px !important;
    margin: 0;
}

.tips-box strong {
    color: #00ccff;
}

/* ==========================================
   RESPONSIVE
   ========================================== */
@media (max-width: 1024px) {
    .custom-skill-form .form-row {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .skills-wrapper {
        flex-direction: column;
        gap: 20px;
    }
    
    .skills-header h1 {
        font-size: 32px !important;
    }
    
    .skills-header .skill-count {
        font-size: 16px;
        padding: 10px 20px;
    }
    
    .skill-list {
        grid-template-columns: 1fr;
    }
    
    .skill-item .skill-name {
        font-size: 18px !important;
    }
    
    .skill-desc-wrapper {
        padding-left: 0;
    }
    
    .btn-save {
        padding: 14px 28px;
        font-size: 18px !important;
        width: 100%;
        justify-content: center;
    }
    
    .save-section {
        flex-direction: column;
    }
    
    .btn-action {
        padding: 12px 20px;
        font-size: 16px !important;
        justify-content: center;
        flex: 1;
    }
    
    .action-buttons {
        width: 100%;
    }
    
    .custom-skill-form {
        padding: 20px 18px;
    }
    
    .custom-skill-form h3 {
        font-size: 22px !important;
    }
    
    .custom-skill-form .subtitle {
        font-size: 16px !important;
    }
}

@media (max-width: 480px) {
    .skills-header h1 {
        font-size: 26px !important;
    }
    
    .skills-header .skill-count {
        font-size: 14px;
        padding: 8px 16px;
    }
    
    .custom-skill-form {
        padding: 16px 14px;
        border-radius: 14px;
    }
    
    .custom-skill-form .form-group label {
        font-size: 15px !important;
    }
    
    .custom-skill-form .form-group input,
    .custom-skill-form .form-group select,
    .custom-skill-form .form-group textarea {
        font-size: 16px;
        padding: 12px 14px;
    }
    
    .skill-list-wrapper {
        padding: 4px;
    }
    
    .skill-list {
        gap: 8px;
        padding: 2px;
    }
    
    .skill-item {
        padding: 14px 16px;
    }
    
    .skill-item .skill-name {
        font-size: 16px !important;
        min-width: 40px;
    }
    
    .skill-item .skill-icon {
        width: 34px;
        height: 34px;
        font-size: 16px;
    }
    
    .skill-item input[type="checkbox"] {
        width: 20px;
        height: 20px;
    }
    
    .skill-item .level-badge {
        font-size: 13px;
        padding: 3px 12px;
    }
    
    .skill-item select {
        font-size: 14px;
        padding: 4px 10px;
    }
    
    .skill-desc-wrapper {
        padding-left: 0;
    }
    
    .skill-desc-wrapper textarea {
        font-size: 15px;
        padding: 8px 10px;
        min-height: 36px;
    }
    
    .btn-save {
        padding: 12px 20px;
        font-size: 16px !important;
    }
    
    .btn-action {
        font-size: 14px !important;
        padding: 10px 16px;
    }
    
    .alert-box {
        font-size: 15px;
        padding: 12px 16px;
    }
    
    .tips-box {
        padding: 12px 16px;
        border-radius: 12px;
    }
    
    .tips-box p {
        font-size: 15px !important;
    }
}
</style>

<!-- ==========================================
     SKILLS CONTENT
========================================== -->
<div class="skills-wrapper">
    <?php include '../includes/sidebar.php'; ?>
    
    <div class="skills-main">
        
        <!-- HEADER -->
        <div class="skills-header">
            <h1>✏️ <span>Input Skill Saya</span></h1>
            <div class="skill-count">
                📊 <?= count($userSkillIds) ?> Skill Dipilih
            </div>
        </div>

        <!-- ALERT -->
        <?php if (isset($_GET['saved'])): ?>
            <div class="alert-box success">✅ Skill berhasil disimpan!</div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert-box error">❌ Gagal menyimpan skill!</div>
        <?php endif; ?>

        <!-- ACTION BUTTONS -->
        <div class="action-buttons">
            <button type="button" class="btn-action primary" onclick="showAddSkillForm()">
                ➕ Tambah Skill Kustom
            </button>
            <button type="button" class="btn-action secondary" onclick="toggleAllSkills()">
                ☑️ Pilih Semua
            </button>
            <button type="button" class="btn-action secondary" onclick="uncheckAllSkills()">
                ⬜ Batal Pilih
            </button>
        </div>

        <!-- FORM TAMBAH SKILL KUSTOM -->
        <div id="addSkillForm" class="custom-skill-form">
            <h3>📝 Tambah Skill Kustom</h3>
            <p class="subtitle">Tambahkan skill yang tidak ada di daftar. Skill ini akan disimpan dan bisa digunakan untuk analisis.</p>
            
            <form method="POST" action="../process/add_custom_skill.php">
                <div class="form-row">
                    <div class="form-group">
                        <label>Nama Skill <span class="required">*</span></label>
                        <input type="text" name="skill_name" placeholder="Contoh: Figma, AWS, dll" required>
                    </div>
                    <div class="form-group">
                        <label>Tingkat Kemampuan</label>
                        <select name="level">
                            <option value="Beginner">Pemula</option>
                            <option value="Intermediate" selected>Menengah</option>
                            <option value="Advanced">Mahir</option>
                        </select>
                    </div>
                </div>
                <div class="form-group" style="margin-top: 4px;">
                    <label>Deskripsi Skill (Opsional)</label>
                    <textarea name="description" rows="2" placeholder="Jelaskan pengalaman Anda dengan skill ini..."></textarea>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-action primary">➕ Tambahkan</button>
                    <button type="button" class="btn-action secondary" onclick="hideAddSkillForm()">Batal</button>
                </div>
            </form>
        </div>

        <!-- SKILL LIST - 2 KOLOM -->
        <form method="POST" action="../process/save_skill.php" style="flex:1; display:flex; flex-direction:column; min-height:0;">
            <div class="skill-list-wrapper">
                <div class="skill-list">
                    <?php 
                    $skillIcons = ['💻', '🔒', '📊', '☁️', '💬', '🛡️', '📈', '🐹', '☕', '📜', '👑', '🤖', '🧩', '🐍', '🗄️', '🤝', '⏰', '🎯', '⚡', '📱', '🌐', '🔧', '📝', '🎨'];
                    $iconClasses = ['green', 'blue', 'purple', 'orange', 'red', 'pink', 'teal', 'indigo', 'green', 'blue', 'purple', 'orange', 'red', 'pink', 'teal', 'indigo', 'green', 'blue', 'purple', 'orange', 'red', 'pink', 'teal', 'indigo'];
                    ?>
                    <?php foreach ($skills as $index => $skill): ?>
                        <?php 
                        $isChecked = in_array($skill['id'], $userSkillIds);
                        $level = $userSkillLevels[$skill['id']] ?? 'Beginner';
                        $desc = $userSkillDescriptions[$skill['id']] ?? '';
                        $iconIndex = $index % count($skillIcons);
                        $icon = $skillIcons[$iconIndex];
                        $iconClass = $iconClasses[$iconIndex % count($iconClasses)];
                        $levelClass = strtolower($level);
                        $levelNames = ['Beginner' => 'Pemula', 'Intermediate' => 'Menengah', 'Advanced' => 'Mahir'];
                        ?>
                        <div class="skill-item <?= $isChecked ? 'selected' : '' ?>" id="skill_item_<?= $skill['id'] ?>">
                            <div class="skill-row">
                                <div class="skill-icon <?= $iconClass ?>"><?= $icon ?></div>
                                <input type="checkbox" name="skills[<?= $skill['id'] ?>]" 
                                       value="checked" 
                                       <?= $isChecked ? 'checked' : '' ?>
                                       id="skill_<?= $skill['id'] ?>"
                                       onchange="toggleDescription(this, <?= $skill['id'] ?>)">
                                <label for="skill_<?= $skill['id'] ?>" class="skill-name" title="<?= htmlspecialchars($skill['skill_name']) ?>">
                                    <?= htmlspecialchars($skill['skill_name']) ?>
                                </label>
                                <div class="skill-level-wrapper">
                                    <span class="level-badge <?= $levelClass ?>">
                                        <?= $levelNames[$level] ?? $level ?>
                                    </span>
                                    <select name="level[<?= $skill['id'] ?>]" onchange="updateLevelBadge(this, <?= $skill['id'] ?>)">
                                        <option value="Beginner" <?= $level == 'Beginner' ? 'selected' : '' ?>>Pemula</option>
                                        <option value="Intermediate" <?= $level == 'Intermediate' ? 'selected' : '' ?>>Menengah</option>
                                        <option value="Advanced" <?= $level == 'Advanced' ? 'selected' : '' ?>>Mahir</option>
                                    </select>
                                </div>
                            </div>
                            <!-- DESKRIPSI - SELALU TAMPIL -->
                            <div id="desc_container_<?= $skill['id'] ?>" class="skill-desc-wrapper" style="display:<?= $isChecked ? 'block' : 'block' ?>;">
                                <textarea name="description[<?= $skill['id'] ?>]" rows="1" placeholder="Deskripsikan skill ini (opsional)"><?= htmlspecialchars($desc) ?></textarea>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- SAVE SECTION -->
            <div class="save-section">
                <button type="submit" class="btn-save primary">💾 Simpan Semua Skill</button>
                <a href="analysis.php" class="btn-save secondary">📊 Analisis Skill</a>
            </div>
        </form>

        <!-- TIPS BOX -->
        <div class="tips-box">
            <p>
                💡 <strong>Tips:</strong> Semakin detail deskripsi skill Anda, semakin akurat analisis yang akan diberikan.
                Sistem akan mencocokkan deskripsi Anda dengan kebutuhan industri untuk mengurangi gap.
            </p>
        </div>

    </div>
</div>

<script>
function showAddSkillForm() {
    const form = document.getElementById('addSkillForm');
    form.classList.toggle('active');
    if (form.classList.contains('active')) {
        form.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
}

function hideAddSkillForm() {
    document.getElementById('addSkillForm').classList.remove('active');
}

function toggleDescription(checkbox, skillId) {
    const container = document.getElementById('desc_container_' + skillId);
    const item = document.getElementById('skill_item_' + skillId);
    if (checkbox.checked) {
        container.style.display = 'block';
        item.classList.add('selected');
    } else {
        container.style.display = 'block';
        item.classList.remove('selected');
    }
}

function updateLevelBadge(select, skillId) {
    const item = document.getElementById('skill_item_' + skillId);
    const badge = item.querySelector('.level-badge');
    const levelMap = {
        'Beginner': 'Pemula',
        'Intermediate': 'Menengah',
        'Advanced': 'Mahir'
    };
    const levelClassMap = {
        'Beginner': 'beginner',
        'Intermediate': 'intermediate',
        'Advanced': 'advanced'
    };
    badge.textContent = levelMap[select.value] || select.value;
    badge.className = 'level-badge ' + levelClassMap[select.value];
}

function toggleAllSkills() {
    const checkboxes = document.querySelectorAll('.skill-item input[type="checkbox"]');
    checkboxes.forEach((cb) => {
        cb.checked = true;
        const skillId = cb.id.replace('skill_', '');
        const container = document.getElementById('desc_container_' + skillId);
        const item = document.getElementById('skill_item_' + skillId);
        container.style.display = 'block';
        item.classList.add('selected');
    });
}

function uncheckAllSkills() {
    const checkboxes = document.querySelectorAll('.skill-item input[type="checkbox"]');
    checkboxes.forEach((cb) => {
        cb.checked = false;
        const skillId = cb.id.replace('skill_', '');
        const container = document.getElementById('desc_container_' + skillId);
        const item = document.getElementById('skill_item_' + skillId);
        container.style.display = 'block';
        item.classList.remove('selected');
    });
}
</script>

<?php include '../includes/footer.php'; ?>
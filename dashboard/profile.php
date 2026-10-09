<?php
// dashboard/profile.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}
require_once '../config/database.php';

$page_title = 'Profil - Skill Gap Analyzer';

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

include '../includes/header.php';
?>

<style>
/* ==========================================
   PROFILE PAGE - FULL WIDTH & BESAR
   ========================================== */

.profile-wrapper {
    display: flex;
    gap: 32px;
    margin-top: 12px;
    flex: 1;
    min-height: 0;
}

.profile-main {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 24px;
    width: 100%;
}

/* Header */
.profile-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-shrink: 0;
    padding-bottom: 8px;
    flex-wrap: wrap;
    gap: 16px;
    width: 100%;
}

.profile-header h1 {
    color: #ffffff;
    font-size: 42px !important;
    font-weight: 900 !important;
    letter-spacing: -1px !important;
    margin: 0 !important;
}

.profile-header h1 span {
    background: linear-gradient(135deg, #00ff88, #00cc66);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Status Badge */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: rgba(0, 255, 136, 0.10);
    border: 1px solid rgba(0, 255, 136, 0.12);
    padding: 10px 24px 10px 18px;
    border-radius: 100px;
    font-size: 16px;
    color: #00ff88;
}

.status-badge .dot {
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

/* Card Profile - FULL WIDTH */
.profile-card {
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 24px;
    padding: 44px 48px;
    width: 100%;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.profile-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #00ff88, #00cc66);
    opacity: 0.6;
}

.profile-card:hover {
    border-color: rgba(0, 255, 136, 0.15);
    background: rgba(255, 255, 255, 0.06);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.3);
}

/* Profile Content Grid - FULL WIDTH */
.profile-content {
    display: grid;
    grid-template-columns: auto 1fr;
    gap: 48px;
    align-items: start;
}

/* Avatar */
.profile-avatar {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 16px;
}

.profile-avatar .avatar {
    width: 140px;
    height: 140px;
    border-radius: 50%;
    background: linear-gradient(135deg, #00ff88, #00cc66);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 56px;
    font-weight: 800;
    color: #0a1628;
    flex-shrink: 0;
    box-shadow: 0 8px 35px rgba(0, 255, 136, 0.3);
    border: 4px solid rgba(0, 255, 136, 0.15);
}

.profile-avatar .avatar-name {
    color: #ffffff;
    font-size: 20px !important;
    font-weight: 600 !important;
    text-align: center;
}

.profile-avatar .avatar-email {
    color: rgba(255, 255, 255, 0.45);
    font-size: 17px !important;
    text-align: center;
}

/* Profile Info - FULL WIDTH */
.profile-info {
    display: flex;
    flex-direction: column;
    gap: 20px;
    width: 100%;
}

.profile-info .info-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    padding-bottom: 16px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.06);
}

.profile-info .info-header h2 {
    color: #ffffff;
    font-size: 28px !important;
    font-weight: 700 !important;
    margin: 0;
}

.profile-info .info-header .edit-link {
    color: #00ff88;
    text-decoration: none;
    font-size: 16px !important;
    font-weight: 500;
    padding: 8px 20px;
    border: 1px solid rgba(0, 255, 136, 0.15);
    border-radius: 10px;
    transition: all 0.3s ease;
}

.profile-info .info-header .edit-link:hover {
    background: rgba(0, 255, 136, 0.10);
    transform: translateY(-2px);
}

/* Detail Grid - FULL WIDTH */
.profile-details {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px 40px;
    width: 100%;
}

.profile-details .detail-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
    padding: 12px 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.04);
}

.profile-details .detail-item .label {
    color: rgba(255, 255, 255, 0.35);
    font-size: 14px !important;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.profile-details .detail-item .value {
    color: rgba(255, 255, 255, 0.85);
    font-size: 19px !important;
    font-weight: 600;
}

.profile-details .detail-item .value.email {
    color: #00ff88;
}

/* Actions - FULL WIDTH */
.profile-actions {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
    margin-top: 8px;
    padding-top: 20px;
    border-top: 1px solid rgba(255, 255, 255, 0.06);
    width: 100%;
}

.profile-actions .btn-action {
    padding: 16px 24px;
    font-size: 17px !important;
    font-weight: 600 !important;
    border-radius: 14px;
    border: none;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.profile-actions .btn-action.primary {
    background: linear-gradient(135deg, #00ff88, #00cc66);
    color: #0a1628;
    box-shadow: 0 4px 20px rgba(0, 255, 136, 0.25);
}

.profile-actions .btn-action.primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 35px rgba(0, 255, 136, 0.4);
}

.profile-actions .btn-action.secondary {
    background: rgba(255, 255, 255, 0.06);
    color: rgba(255, 255, 255, 0.85);
    border: 1px solid rgba(255, 255, 255, 0.08);
}

.profile-actions .btn-action.secondary:hover {
    background: rgba(255, 255, 255, 0.12);
    transform: translateY(-3px);
}

.profile-actions .btn-action.purple {
    background: linear-gradient(135deg, #8b5cf6, #7c3aed);
    color: #ffffff;
    box-shadow: 0 4px 20px rgba(139, 92, 246, 0.25);
}

.profile-actions .btn-action.purple:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 35px rgba(139, 92, 246, 0.35);
}

.profile-actions .btn-action.danger {
    background: rgba(255, 60, 60, 0.12);
    color: #ff6666;
    border: 1px solid rgba(255, 60, 60, 0.10);
}

.profile-actions .btn-action.danger:hover {
    background: rgba(255, 60, 60, 0.2);
    transform: translateY(-3px);
}

/* Responsive */
@media (max-width: 1024px) {
    .profile-content {
        grid-template-columns: 1fr;
        gap: 32px;
    }
    
    .profile-avatar {
        flex-direction: row;
        gap: 24px;
    }
    
    .profile-avatar .avatar {
        width: 100px;
        height: 100px;
        font-size: 40px;
    }
    
    .profile-actions {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .profile-wrapper {
        flex-direction: column;
        gap: 20px;
    }
    
    .profile-header h1 {
        font-size: 32px !important;
    }
    
    .profile-card {
        padding: 24px 20px;
        border-radius: 18px;
    }
    
    .profile-avatar {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .profile-avatar .avatar {
        width: 80px;
        height: 80px;
        font-size: 34px;
    }
    
    .profile-info .info-header h2 {
        font-size: 22px !important;
    }
    
    .profile-details {
        grid-template-columns: 1fr;
        gap: 4px;
    }
    
    .profile-details .detail-item .value {
        font-size: 17px !important;
    }
    
    .profile-actions {
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }
    
    .profile-actions .btn-action {
        padding: 14px 18px;
        font-size: 15px !important;
    }
    
    .status-badge {
        font-size: 14px;
        padding: 8px 18px 8px 14px;
    }
}

@media (max-width: 480px) {
    .profile-header h1 {
        font-size: 24px !important;
    }
    
    .profile-card {
        padding: 16px 14px;
        border-radius: 16px;
    }
    
    .profile-avatar .avatar {
        width: 64px;
        height: 64px;
        font-size: 28px;
    }
    
    .profile-avatar .avatar-name {
        font-size: 17px !important;
    }
    
    .profile-avatar .avatar-email {
        font-size: 14px !important;
    }
    
    .profile-info .info-header h2 {
        font-size: 18px !important;
    }
    
    .profile-info .info-header .edit-link {
        font-size: 13px !important;
        padding: 6px 14px;
    }
    
    .profile-details .detail-item .label {
        font-size: 12px !important;
    }
    
    .profile-details .detail-item .value {
        font-size: 15px !important;
    }
    
    .profile-actions {
        grid-template-columns: 1fr 1fr;
        gap: 8px;
    }
    
    .profile-actions .btn-action {
        padding: 12px 14px;
        font-size: 13px !important;
        border-radius: 10px;
        gap: 6px;
    }
    
    .status-badge {
        font-size: 12px;
        padding: 6px 14px 6px 10px;
    }
    
    .status-badge .dot {
        width: 8px;
        height: 8px;
    }
}
</style>

<!-- ==========================================
     PROFILE CONTENT - FULL WIDTH
========================================== -->
<div class="profile-wrapper">
    <?php include '../includes/sidebar.php'; ?>
    
    <div class="profile-main">
        
        <!-- Header -->
        <div class="profile-header">
            <h1>👤 <span>Profil Saya</span></h1>
            <div class="status-badge">
                <span class="dot"></span>
                Aktif
            </div>
        </div>

        <!-- Profile Card - FULL WIDTH -->
        <div class="profile-card">
            
            <div class="profile-content">
                
                <!-- Avatar Section -->
                <div class="profile-avatar">
                    <div class="avatar">
                        <?= strtoupper(substr($user['name'], 0, 1)) ?>
                    </div>
                    <div class="avatar-name"><?= htmlspecialchars($user['name']) ?></div>
                    <div class="avatar-email"><?= htmlspecialchars($user['email']) ?></div>
                </div>

                <!-- Info Section - FULL WIDTH -->
                <div class="profile-info">
                    
                    <div class="info-header">
                        <h2>Informasi Akun</h2>
                        <a href="edit-profile.php" class="edit-link">✏️ Edit Profil</a>
                    </div>

                    <!-- Details Grid - FULL WIDTH -->
                    <div class="profile-details">
                        <div class="detail-item">
                            <span class="label">📧 Email</span>
                            <span class="value email"><?= htmlspecialchars($user['email']) ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="label">👤 Nama</span>
                            <span class="value"><?= htmlspecialchars($user['name']) ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="label">📅 Bergabung</span>
                            <span class="value"><?= date('d F Y', strtotime($user['created_at'])) ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="label">🆔 User ID</span>
                            <span class="value">#<?= str_pad($user['id'], 4, '0', STR_PAD_LEFT) ?></span>
                        </div>
                    </div>

                </div>

            </div>

            <!-- Actions - FULL WIDTH -->
            <div class="profile-actions">
                <a href="skills.php" class="btn-action primary">📚 Kelola Skill</a>
                <a href="analysis.php" class="btn-action purple">📊 Analisis</a>
                <a href="edit-profile.php" class="btn-action secondary">✏️ Edit Profil</a>
                <a href="../auth/logout.php" class="btn-action danger">🚪 Logout</a>
            </div>

        </div>

    </div>
</div>

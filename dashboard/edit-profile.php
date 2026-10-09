<?php
// dashboard/edit-profile.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}
require_once '../config/database.php';

$page_title = 'Edit Profil - Skill Gap Analyzer';

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $new_password = $_POST['new_password'] ?? '';
    
    if (empty($name)) {
        $error = 'Nama tidak boleh kosong!';
    } else {
        try {
            if (!empty($new_password)) {
                if (strlen($new_password) < 6) {
                    $error = 'Password baru minimal 6 karakter!';
                } else {
                    $hashed = password_hash($new_password, PASSWORD_DEFAULT);
                    $updateStmt = $pdo->prepare("UPDATE users SET name = ?, password = ? WHERE id = ?");
                    $updateStmt->execute([$name, $hashed, $_SESSION['user_id']]);
                    $_SESSION['user_name'] = $name;
                    $success = 'Profil dan password berhasil diperbarui!';
                }
            } else {
                $updateStmt = $pdo->prepare("UPDATE users SET name = ? WHERE id = ?");
                $updateStmt->execute([$name, $_SESSION['user_id']]);
                $_SESSION['user_name'] = $name;
                $success = 'Profil berhasil diperbarui!';
            }
            // Refresh data user
            $stmt->execute([$_SESSION['user_id']]);
            $user = $stmt->fetch();
        } catch (PDOException $e) {
            $error = 'Gagal menyimpan perubahan: ' . $e->getMessage();
        }
    }
}

include '../includes/header.php';
?>

<div class="profile-wrapper" style="display:flex; gap:24px; margin-top:12px; flex:1; min-height:0;">
    <?php include '../includes/sidebar.php'; ?>

    <div class="profile-main" style="flex:1; display:flex; flex-direction:column; gap:20px; overflow-y:auto; padding-right:8px;">
        <div class="profile-header" style="display:flex; justify-content:space-between; align-items:center;">
            <div>
                <h1 style="font-size:24px; font-weight:800; color:#fff; margin:0;">✏️ Edit Profil</h1>
                <p style="color:rgba(255,255,255,0.6); font-size:14px; margin:4px 0 0 0;">Perbarui nama akun atau ganti kata sandi Anda.</p>
            </div>
            <a href="profile.php" class="btn btn-secondary" style="background:rgba(255,255,255,0.1); color:#fff; text-decoration:none; padding:8px 16px; border-radius:8px; font-size:14px; font-weight:600;">← Kembali</a>
        </div>

        <?php if ($success): ?>
            <div style="background:rgba(46,204,113,0.15); border:1px solid rgba(46,204,113,0.3); color:#2ecc71; padding:12px 16px; border-radius:10px; font-size:14px;">
                ✅ <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div style="background:rgba(231,76,60,0.15); border:1px solid rgba(231,76,60,0.3); color:#e74c3c; padding:12px 16px; border-radius:10px; font-size:14px;">
                ⚠️ <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <div style="background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.06); border-radius:16px; padding:24px; max-width:600px;">
            <form method="POST" action="">
                <div style="margin-bottom:16px;">
                    <label style="display:block; color:rgba(255,255,255,0.8); font-size:13px; font-weight:600; margin-bottom:6px;">Email (Tidak dapat diubah)</label>
                    <input type="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" disabled style="width:100%; padding:10px 14px; background:rgba(255,255,255,0.02); border:1px solid rgba(255,255,255,0.06); border-radius:8px; color:rgba(255,255,255,0.4); font-size:14px; cursor:not-allowed;">
                </div>

                <div style="margin-bottom:16px;">
                    <label for="name" style="display:block; color:rgba(255,255,255,0.8); font-size:13px; font-weight:600; margin-bottom:6px;">Nama Lengkap</label>
                    <input type="text" id="name" name="name" value="<?= htmlspecialchars($user['name'] ?? '') ?>" required style="width:100%; padding:10px 14px; background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.12); border-radius:8px; color:#fff; font-size:14px; outline:none;">
                </div>

                <div style="margin-bottom:24px;">
                    <label for="new_password" style="display:block; color:rgba(255,255,255,0.8); font-size:13px; font-weight:600; margin-bottom:6px;">Password Baru (Kosongkan jika tidak ingin mengubah)</label>
                    <input type="password" id="new_password" name="new_password" placeholder="Minimal 6 karakter" minlength="6" style="width:100%; padding:10px 14px; background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.12); border-radius:8px; color:#fff; font-size:14px; outline:none;">
                </div>

                <div style="display:flex; gap:12px;">
                    <button type="submit" style="padding:10px 24px; background:linear-gradient(135deg, #00b894, #00a381); color:#fff; border:none; border-radius:8px; font-weight:700; font-size:14px; cursor:pointer;">💾 Simpan Perubahan</button>
                    <a href="profile.php" style="padding:10px 20px; background:rgba(255,255,255,0.08); color:rgba(255,255,255,0.8); text-decoration:none; border-radius:8px; font-weight:600; font-size:14px; display:inline-flex; align-items:center;">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include '../includes/footer.php'; ?>

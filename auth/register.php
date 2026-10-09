<?php
// auth/register.php
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: ../dashboard/dashboard.php');
    exit;
}

require_once '../config/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    if (empty($name) || empty($email) || empty($password)) {
        $error = "Semua field harus diisi!";
    } elseif (strlen($password) < 6) {
        $error = "Password minimal 6 karakter!";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        try {
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $hashedPassword]);
            header('Location: login.php?registered=1');
            exit;
        } catch(PDOException $e) {
            if ($e->errorInfo[1] == 1062) {
                $error = "Email sudah terdaftar!";
            } else {
                $error = "Terjadi kesalahan: " . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Skill Gap Analyzer</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    
    <style>
        /* ==========================================
           REGISTER PAGE - FULL & BESAR
           ========================================== */
        
        /* Reset container untuk register */
        .container {
            max-width: 100% !important;
            min-height: 100vh !important;
            padding: 30px 50px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            background: linear-gradient(135deg, #0a1628 0%, #1a2a3a 50%, #0d1b2a 100%) !important;
        }

        /* Card Register - BESAR */
        .register-card {
            max-width: 580px;
            width: 100%;
            background: rgba(255, 255, 255, 0.06);
            backdrop-filter: blur(30px);
            -webkit-backdrop-filter: blur(30px);
            border: 1px solid rgba(255, 255, 255, 0.10);
            border-radius: 28px;
            padding: 44px 48px 40px;
            box-shadow: 0 40px 100px rgba(0, 0, 0, 0.6);
            position: relative;
            overflow: hidden;
        }

        .register-card::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            border-radius: 28px;
            background: linear-gradient(135deg, rgba(0, 255, 136, 0.10), transparent 50%, rgba(0, 255, 136, 0.05));
            z-index: -1;
            opacity: 0.6;
        }

        /* Logo di card - BESAR */
        .register-logo {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 12px;
        }

        .register-logo-img {
            width: 72px;
            height: 72px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: transparent;
        }

        .register-logo-img img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
        }

        .register-logo-text h1 {
            font-size: 32px;
            font-weight: 800;
            color: #ffffff;
            margin: 0;
            line-height: 1.2;
            background: linear-gradient(135deg, #ffffff 60%, #00ff88);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .register-logo-text p {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.40);
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin: 4px 0 0 0;
            font-weight: 500;
        }

        /* Title Register - BESAR */
        .register-title {
            font-size: 36px;
            font-weight: 700;
            color: #ffffff;
            margin: 20px 0 6px 0;
            letter-spacing: -0.5px;
        }

        .register-subtitle {
            font-size: 18px;
            color: rgba(255, 255, 255, 0.50);
            margin-bottom: 30px;
        }

        /* Form - BESAR */
        .register-form .form-group {
            margin-bottom: 24px;
        }

        .register-form label {
            display: block;
            font-size: 17px;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.80);
            margin-bottom: 10px;
        }

        .register-form input {
            width: 100%;
            padding: 18px 22px;
            background: rgba(255, 255, 255, 0.07);
            border: 2px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            font-size: 20px;
            color: #ffffff;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s ease;
            outline: none;
        }

        .register-form input:focus {
            border-color: #00ff88;
            background: rgba(255, 255, 255, 0.10);
            box-shadow: 0 0 0 6px rgba(0, 255, 136, 0.08);
        }

        .register-form input::placeholder {
            color: rgba(255, 255, 255, 0.25);
            font-size: 18px;
        }

        /* Button Register - BESAR */
        .register-btn {
            width: 100%;
            padding: 20px;
            font-size: 22px;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #00ff88, #00cc66);
            color: #0a1628;
            border: none;
            border-radius: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 6px 40px rgba(0, 255, 136, 0.30);
            margin-top: 8px;
        }

        .register-btn:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 55px rgba(0, 255, 136, 0.45);
        }

        .register-btn:active {
            transform: translateY(0);
        }

        /* Error - BESAR */
        .alert-box {
            padding: 18px 24px;
            border-radius: 14px;
            font-size: 18px;
            font-weight: 500;
            margin-bottom: 24px;
            border: 2px solid;
        }

        .alert-box.error {
            background: rgba(255, 60, 60, 0.12);
            color: #ff6666;
            border-color: rgba(255, 60, 60, 0.20);
        }

        .alert-box.success {
            background: rgba(0, 255, 136, 0.12);
            color: #00ff88;
            border-color: rgba(0, 255, 136, 0.20);
        }

        /* Login Link - BESAR */
        .login-link {
            text-align: center;
            margin-top: 28px;
            font-size: 19px;
            color: rgba(255, 255, 255, 0.50);
        }

        .login-link a {
            color: #00ff88;
            text-decoration: none;
            font-weight: 600;
            font-size: 19px;
            transition: color 0.3s ease;
        }

        .login-link a:hover {
            color: #00cc66;
            text-decoration: underline;
        }

        /* Back to home - BESAR */
        .back-home {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: rgba(255, 255, 255, 0.40);
            text-decoration: none;
            font-size: 17px;
            margin-top: 20px;
            transition: color 0.3s ease;
        }

        .back-home:hover {
            color: rgba(255, 255, 255, 0.65);
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .register-card {
                max-width: 500px;
                padding: 38px 40px 34px;
            }
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px 30px !important;
            }

            .register-card {
                max-width: 460px;
                padding: 32px 28px 28px;
                border-radius: 24px;
            }

            .register-title {
                font-size: 30px;
            }

            .register-subtitle {
                font-size: 16px;
            }

            .register-form input {
                font-size: 18px;
                padding: 16px 20px;
            }

            .register-btn {
                font-size: 20px;
                padding: 18px;
            }

            .register-logo-text h1 {
                font-size: 26px;
            }

            .register-logo-img {
                width: 60px;
                height: 60px;
            }

            .register-form label {
                font-size: 16px;
            }

            .login-link {
                font-size: 17px;
            }

            .login-link a {
                font-size: 17px;
            }

            .alert-box {
                font-size: 16px;
                padding: 14px 18px;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 12px 16px !important;
            }

            .register-card {
                max-width: 100%;
                padding: 24px 18px 20px;
                border-radius: 20px;
            }

            .register-title {
                font-size: 24px;
                margin-top: 14px;
            }

            .register-subtitle {
                font-size: 14px;
                margin-bottom: 22px;
            }

            .register-form .form-group {
                margin-bottom: 16px;
            }

            .register-form label {
                font-size: 14px;
            }

            .register-form input {
                font-size: 16px;
                padding: 14px 16px;
                border-radius: 12px;
            }

            .register-form input::placeholder {
                font-size: 15px;
            }

            .register-btn {
                font-size: 18px;
                padding: 16px;
                border-radius: 12px;
            }

            .register-logo {
                gap: 14px;
            }

            .register-logo-text h1 {
                font-size: 20px;
            }

            .register-logo-text p {
                font-size: 9px;
                letter-spacing: 1px;
            }

            .register-logo-img {
                width: 48px;
                height: 48px;
            }

            .alert-box {
                font-size: 14px;
                padding: 12px 16px;
                border-radius: 12px;
            }

            .login-link {
                font-size: 15px;
                margin-top: 20px;
            }

            .login-link a {
                font-size: 15px;
            }

            .back-home {
                font-size: 14px;
                margin-top: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>

    <div class="container">
        <div class="register-card">

            <!-- LOGO - BESAR -->
            <div class="register-logo">
                <div class="register-logo-img">
                    <img src="../assets/images/FMIPA.png" alt="FMIPA UNIMED">
                </div>
                <div class="register-logo-text">
                    <h1>Skill Gap Analyzer</h1>
                    <p>UNIVERSITAS NEGERI MEDAN • ILMU KOMPUTER</p>
                </div>
            </div>

            <!-- TITLE - BESAR -->
            <h2 class="register-title">📝 Registrasi</h2>
            <p class="register-subtitle">Buat akun gratis untuk mulai menganalisis skill Anda</p>

            <!-- ERROR - BESAR -->
            <?php if ($error): ?>
                <div class="alert-box error">⚠️ <?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <!-- FORM - BESAR -->
            <form method="POST" action="" class="register-form">

                <div class="form-group">
                    <label for="name">👤 Nama Lengkap</label>
                    <input 
                        type="text" 
                        id="name"
                        name="name" 
                        placeholder="Masukkan nama lengkap"
                        value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '' ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="email">📧 Email</label>
                    <input 
                        type="email" 
                        id="email"
                        name="email" 
                        placeholder="Masukkan email aktif"
                        value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="password">🔑 Password</label>
                    <input 
                        type="password" 
                        id="password"
                        name="password" 
                        placeholder="Minimal 6 karakter"
                        required 
                        minlength="6"
                    >
                </div>

                <button type="submit" class="register-btn">Daftar</button>

            </form>

            <!-- LOGIN LINK - BESAR -->
            <div class="login-link">
                Sudah punya akun? <a href="login.php">Login di sini</a>
            </div>

            <!-- BACK TO HOME - BESAR -->
            <div style="text-align: center;">
                <a href="../index.php" class="back-home">← Kembali ke Beranda</a>
            </div>

        </div>
    </div>
</body>
</html>
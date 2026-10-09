<?php
session_start();

require_once '../config/database.php';

// Jika sudah login
if (isset($_SESSION['user_id'])) {
    header("Location: ../dashboard/dashboard.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {

        $error = "Email dan password harus diisi!";

    } else {

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {

            if (password_verify($password, $user['password'])) {

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];

                header("Location: ../dashboard/dashboard.php");
                exit;

            } else {
                $error = "Password salah!";
            }

        } else {
            $error = "Email tidak ditemukan!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Skill Gap Analyzer</title>

    <link rel="stylesheet" href="../assets/css/style.css">
    
    <style>
        
        .container {
            max-width: 100% !important;
            min-height: 100vh !important;
            padding: 30px 50px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            background: linear-gradient(135deg, #0a1628 0%, #1a2a3a 50%, #0d1b2a 100%) !important;
        }

        /* Card Login - BESAR */
        .login-card {
            max-width: 600px;
            width: 100%;
            background: rgba(255, 255, 255, 0.06);
            backdrop-filter: blur(30px);
            -webkit-backdrop-filter: blur(30px);
            border: 1px solid rgba(255, 255, 255, 0.10);
            border-radius: 28px;
            padding: 50px 56px 44px;
            box-shadow: 0 40px 100px rgba(0, 0, 0, 0.6);
            position: relative;
            overflow: hidden;
        }

        .login-card::before {
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

        /* Logo di card - LEBIH BESAR */
        .login-logo {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 12px;
        }

        .login-logo-img {
            width: 80px;
            height: 80px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: transparent;
        }

        .login-logo-img img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
        }

        .login-logo-text h1 {
            font-size: 34px;
            font-weight: 800;
            color: #ffffff;
            margin: 0;
            line-height: 1.2;
            background: linear-gradient(135deg, #ffffff 60%, #00ff88);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .login-logo-text p {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.40);
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin: 4px 0 0 0;
            font-weight: 500;
        }

        /* Title Login - BESAR */
        .login-title {
            font-size: 38px;
            font-weight: 700;
            color: #ffffff;
            margin: 24px 0 8px 0;
            letter-spacing: -0.5px;
        }

        .login-subtitle {
            font-size: 18px;
            color: rgba(255, 255, 255, 0.50);
            margin-bottom: 32px;
        }

        /* Form - BESAR */
        .login-form .form-group {
            margin-bottom: 26px;
        }

        .login-form label {
            display: block;
            font-size: 18px;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.80);
            margin-bottom: 10px;
        }

        .login-form input {
            width: 100%;
            padding: 18px 24px;
            background: rgba(255, 255, 255, 0.07);
            border: 2px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            font-size: 20px;
            color: #ffffff;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s ease;
            outline: none;
        }

        .login-form input:focus {
            border-color: #00ff88;
            background: rgba(255, 255, 255, 0.10);
            box-shadow: 0 0 0 6px rgba(0, 255, 136, 0.08);
        }

        .login-form input::placeholder {
            color: rgba(255, 255, 255, 0.25);
            font-size: 18px;
        }

        /* Button Login - BESAR */
        .login-btn {
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

        .login-btn:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 55px rgba(0, 255, 136, 0.45);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        /* Error & Success - BESAR */
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

        /* Register Link - BESAR */
        .register-link {
            text-align: center;
            margin-top: 28px;
            font-size: 19px;
            color: rgba(255, 255, 255, 0.50);
        }

        .register-link a {
            color: #00ff88;
            text-decoration: none;
            font-weight: 600;
            font-size: 19px;
            transition: color 0.3s ease;
        }

        .register-link a:hover {
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
            .login-card {
                max-width: 520px;
                padding: 40px 44px 36px;
            }
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px 30px !important;
            }

            .login-card {
                max-width: 480px;
                padding: 36px 32px 30px;
                border-radius: 24px;
            }

            .login-title {
                font-size: 32px;
            }

            .login-subtitle {
                font-size: 16px;
            }

            .login-form input {
                font-size: 18px;
                padding: 16px 20px;
            }

            .login-btn {
                font-size: 20px;
                padding: 18px;
            }

            .login-logo-text h1 {
                font-size: 28px;
            }

            .login-logo-img {
                width: 64px;
                height: 64px;
            }

            .login-form label {
                font-size: 16px;
            }

            .register-link {
                font-size: 17px;
            }

            .register-link a {
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

            .login-card {
                max-width: 100%;
                padding: 28px 20px 24px;
                border-radius: 20px;
            }

            .login-title {
                font-size: 26px;
                margin-top: 16px;
            }

            .login-subtitle {
                font-size: 14px;
                margin-bottom: 24px;
            }

            .login-form .form-group {
                margin-bottom: 18px;
            }

            .login-form label {
                font-size: 14px;
            }

            .login-form input {
                font-size: 16px;
                padding: 14px 16px;
                border-radius: 12px;
            }

            .login-form input::placeholder {
                font-size: 15px;
            }

            .login-btn {
                font-size: 18px;
                padding: 16px;
                border-radius: 12px;
            }

            .login-logo {
                gap: 14px;
            }

            .login-logo-text h1 {
                font-size: 22px;
            }

            .login-logo-text p {
                font-size: 10px;
                letter-spacing: 1px;
            }

            .login-logo-img {
                width: 52px;
                height: 52px;
            }

            .alert-box {
                font-size: 14px;
                padding: 12px 16px;
                border-radius: 12px;
            }

            .register-link {
                font-size: 15px;
                margin-top: 20px;
            }

            .register-link a {
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

    <!-- ORB BACKGROUND -->
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>

    <div class="container">
        <div class="login-card">

            <!-- LOGO - BESAR -->
            <div class="login-logo">
                <div class="login-logo-img">
                    <img src="../assets/images/FMIPA.png" alt="FMIPA UNIMED">
                </div>
                <div class="login-logo-text">
                    <h1>Skill Gap Analyzer</h1>
                    <p>UNIVERSITAS NEGERI MEDAN • ILMU KOMPUTER</p>
                </div>
            </div>

            <!-- TITLE - BESAR -->
            <h2 class="login-title">🔐 Login</h2>
            <p class="login-subtitle">Masuk ke akun Anda untuk melanjutkan</p>

            <!-- ERROR / SUCCESS - BESAR -->
            <?php if($error): ?>
                <div class="alert-box error">
                    ⚠️ <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <?php if(isset($_GET['registered'])): ?>
                <div class="alert-box success">
                    ✅ Registrasi berhasil! Silakan login.
                </div>
            <?php endif; ?>

            <!-- FORM - BESAR -->
            <form method="POST" class="login-form">

                <div class="form-group">
                    <label for="email">📧 Email</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        placeholder="Masukkan email Anda"
                        value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="password">🔒 Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="Masukkan password Anda"
                        required
                        
                    >
                    
                </div>

                <button type="submit" class="login-btn">
                    Masuk
                </button>

            </form>

            <!-- REGISTER LINK - BESAR -->
            <div class="register-link">
                Belum punya akun? <a href="register.php">Daftar Sekarang</a>
            </div>

            <!-- BACK TO HOME - BESAR -->
            <div style="text-align: center;">
                <a href="../index.php" class="back-home">← Kembali ke Beranda</a>
            </div>

        </div>
    </div>

</body>
</html>
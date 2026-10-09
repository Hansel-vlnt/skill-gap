<?php
// index.php - Halaman Utama
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard/dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skill Gap Analyzer - Analisis Kompetensi IT</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        /* ==========================================
           PERBAIKAN UKURAN TULISAN & WARNA
           ========================================== */
        
        /* Logo dengan gambar - TANPA BACKGROUND */
        .logo-icon-img {
            width: 72px;
            height: 72px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: transparent;
            /* HILANGKAN background, border, shadow */
        }

        .logo-icon-img img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
        }

        /* Header logo container */
        .logo-container {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .logo-text h1 {
            font-size: 34px !important;
            font-weight: 800 !important;
            background: linear-gradient(135deg, #ffffff 60%, #00ff88);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin: 0;
            line-height: 1.2;
        }

        .logo-text .subtitle {
            font-size: 14px !important;
            color: rgba(255, 255, 255, 0.45);
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-top: 4px;
            font-weight: 500;
        }

        /* Hero Title lebih besar */
        .hero-title {
            font-size: 68px !important;
            font-weight: 900 !important;
            line-height: 1.08 !important;
            color: #ffffff !important;
            letter-spacing: -2.5px !important;
        }

        .hero-title .highlight {
            background: linear-gradient(135deg, #00ff88, #00cc66);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-description {
            font-size: 22px !important;
            color: rgba(255, 255, 255, 0.6) !important;
            line-height: 1.9 !important;
            max-width: 520px !important;
        }

        .stat-number {
            font-size: 40px !important;
            font-weight: 800 !important;
            color: #00ff88 !important;
            letter-spacing: -0.5px !important;
        }

        .stat-label {
            font-size: 17px !important;
            color: rgba(255, 255, 255, 0.5) !important;
            font-weight: 500 !important;
        }

        /* Section Title */
        .section-title {
            font-size: 40px !important;
            font-weight: 800 !important;
            color: #ffffff !important;
            text-align: center !important;
            margin-bottom: 10px !important;
            letter-spacing: -1px !important;
        }

        .section-subtitle {
            font-size: 20px !important;
            color: rgba(255, 255, 255, 0.45) !important;
            text-align: center !important;
            margin-bottom: 32px !important;
        }

        /* Feature items */
        .feature-item h4 {
            font-size: 22px !important;
            color: #ffffff !important;
            font-weight: 600 !important;
        }

        .feature-item p {
            font-size: 17px !important;
            color: rgba(255, 255, 255, 0.5) !important;
            line-height: 1.7 !important;
        }

        .feature-item .feature-icon {
            font-size: 48px !important;
        }

        /* Step items */
        .step-item .step-number {
            width: 56px !important;
            height: 56px !important;
            font-size: 24px !important;
            background: linear-gradient(135deg, #00ff88, #00cc66) !important;
            color: #0a1628 !important;
            box-shadow: 0 4px 30px rgba(0, 255, 136, 0.3) !important;
        }

        .step-item h4 {
            font-size: 20px !important;
            color: #ffffff !important;
            font-weight: 600 !important;
        }

        .step-item p {
            font-size: 17px !important;
            color: rgba(255, 255, 255, 0.45) !important;
            line-height: 1.6 !important;
        }

        /* CTA */
        .cta-content h2 {
            font-size: 40px !important;
            font-weight: 800 !important;
            color: #ffffff !important;
            letter-spacing: -1px !important;
        }

        .cta-content p {
            font-size: 20px !important;
            color: rgba(255, 255, 255, 0.5) !important;
        }

        /* Button lebih besar */
        .btn {
            font-size: 20px !important;
            padding: 16px 38px !important;
            border-radius: 14px !important;
        }

        .btn-lg {
            font-size: 22px !important;
            padding: 18px 48px !important;
            border-radius: 16px !important;
        }

        .btn-sm {
            font-size: 16px !important;
            padding: 12px 26px !important;
            border-radius: 10px !important;
        }

        .btn-primary {
            background: linear-gradient(135deg, #00ff88, #00cc66) !important;
            color: #0a1628 !important;
            box-shadow: 0 4px 35px rgba(0, 255, 136, 0.35) !important;
        }

        .btn-primary:hover {
            transform: translateY(-3px) !important;
            box-shadow: 0 8px 50px rgba(0, 255, 136, 0.5) !important;
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.08) !important;
            color: rgba(255, 255, 255, 0.9) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.15) !important;
            transform: translateY(-3px) !important;
        }

        .btn-outline {
            background: transparent !important;
            color: #ffffff !important;
            border: 2px solid rgba(255, 255, 255, 0.15) !important;
        }

        .btn-outline:hover {
            border-color: #00ff88 !important;
            transform: translateY(-3px) !important;
        }

        /* Hero Badge */
        .hero-badge {
            font-size: 17px !important;
            padding: 10px 28px 10px 16px !important;
            background: rgba(0, 255, 136, 0.12) !important;
            border: 1px solid rgba(0, 255, 136, 0.15) !important;
            color: #00ff88 !important;
        }

        .badge-dot {
            width: 12px !important;
            height: 12px !important;
            background: #00ff88 !important;
        }

        /* Auth links */
        .auth-links a {
            color: #00ff88 !important;
        }

        .auth-links a:hover {
            color: #00cc66 !important;
        }

        /* Footer */
        .footer-logo-icon-img {
            width: 48px;
            height: 48px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: transparent;
        }

        .footer-logo-icon-img img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
        }

        .footer-logo strong {
            font-size: 18px !important;
            color: #ffffff !important;
        }

        .footer-logo p {
            font-size: 13px !important;
            color: rgba(255, 255, 255, 0.3) !important;
            margin-top: 2px !important;
        }

        .footer-copyright {
            font-size: 14px !important;
            color: rgba(255, 255, 255, 0.15) !important;
        }

        /* Floating badge */
        .floating-badge span {
            font-size: 20px !important;
            font-weight: 700 !important;
            color: #ffffff !important;
        }

        .floating-badge small {
            font-size: 11px !important;
            color: rgba(255, 255, 255, 0.35) !important;
        }

        /* Hero Graphic lebih besar */
        .hero-graphic {
            width: 380px !important;
            height: 380px !important;
        }

        .graphic-center .graphic-emoji {
            font-size: 68px !important;
        }

        .graphic-center .graphic-text {
            font-size: 18px !important;
            font-weight: 700 !important;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .hero-title {
                font-size: 48px !important;
            }
            
            .hero-description {
                font-size: 19px !important;
            }
            
            .section-title {
                font-size: 32px !important;
            }
            
            .section-subtitle {
                font-size: 17px !important;
            }
            
            .cta-content h2 {
                font-size: 32px !important;
            }
            
            .cta-content p {
                font-size: 17px !important;
            }
            
            .hero-graphic {
                width: 300px !important;
                height: 300px !important;
            }
            
            .logo-icon-img {
                width: 60px;
                height: 60px;
            }
            
            .logo-text h1 {
                font-size: 28px !important;
            }
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 38px !important;
            }
            
            .hero-description {
                font-size: 17px !important;
            }
            
            .section-title {
                font-size: 28px !important;
            }
            
            .section-subtitle {
                font-size: 15px !important;
            }
            
            .stat-number {
                font-size: 30px !important;
            }
            
            .stat-label {
                font-size: 14px !important;
            }
            
            .cta-content h2 {
                font-size: 28px !important;
            }
            
            .cta-content p {
                font-size: 16px !important;
            }
            
            .btn {
                font-size: 17px !important;
                padding: 14px 30px !important;
            }
            
            .btn-lg {
                font-size: 19px !important;
                padding: 16px 36px !important;
            }
            
            .logo-text h1 {
                font-size: 24px !important;
            }
            
            .logo-icon-img {
                width: 52px;
                height: 52px;
            }
            
            .hero-graphic {
                width: 220px !important;
                height: 220px !important;
            }
            
            .graphic-center .graphic-emoji {
                font-size: 44px !important;
            }
            
            .feature-item h4 {
                font-size: 18px !important;
            }
            
            .feature-item p {
                font-size: 14px !important;
            }
            
            .step-item h4 {
                font-size: 17px !important;
            }
            
            .step-item p {
                font-size: 14px !important;
            }
        }

        @media (max-width: 480px) {
            .hero-title {
                font-size: 30px !important;
                letter-spacing: -1px !important;
            }
            
            .hero-description {
                font-size: 15px !important;
            }
            
            .section-title {
                font-size: 22px !important;
            }
            
            .section-subtitle {
                font-size: 13px !important;
            }
            
            .stat-number {
                font-size: 24px !important;
            }
            
            .stat-label {
                font-size: 12px !important;
            }
            
            .cta-content h2 {
                font-size: 22px !important;
            }
            
            .cta-content p {
                font-size: 14px !important;
            }
            
            .btn {
                font-size: 15px !important;
                padding: 12px 24px !important;
            }
            
            .btn-lg {
                font-size: 16px !important;
                padding: 14px 28px !important;
            }
            
            .btn-sm {
                font-size: 13px !important;
                padding: 8px 18px !important;
            }
            
            .logo-text h1 {
                font-size: 20px !important;
            }
            
            .logo-text .subtitle {
                font-size: 10px !important;
            }
            
            .logo-icon-img {
                width: 44px;
                height: 44px;
            }
            
            .hero-graphic {
                width: 180px !important;
                height: 180px !important;
            }
            
            .graphic-center .graphic-emoji {
                font-size: 34px !important;
            }
            
            .graphic-center .graphic-text {
                font-size: 12px !important;
            }
            
            .feature-item h4 {
                font-size: 15px !important;
            }
            
            .feature-item p {
                font-size: 12px !important;
            }
            
            .feature-item .feature-icon {
                font-size: 32px !important;
            }
            
            .step-item h4 {
                font-size: 15px !important;
            }
            
            .step-item p {
                font-size: 13px !important;
            }
            
            .step-item .step-number {
                width: 44px !important;
                height: 44px !important;
                font-size: 18px !important;
            }
            
            .hero-badge {
                font-size: 13px !important;
                padding: 6px 18px 6px 12px !important;
            }
            
            .floating-badge {
                display: none !important;
            }
            
            .footer-logo strong {
                font-size: 15px !important;
            }
            
            .footer-logo p {
                font-size: 10px !important;
            }
            
            .footer-copyright {
                font-size: 11px !important;
            }
            
            .footer-logo-icon-img {
                width: 38px;
                height: 38px;
            }
        }
    </style>
</head>
<body>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>

    <div class="container">
        <!-- HEADER -->
        <div class="header">
            <div class="logo-container">
                <div class="logo-icon-img">
                    <img src="assets/images/FMIPA.png" alt="FMIPA UNIMED">
                </div>
                <div class="logo-text">
                    <h1>Skill Gap Analyzer</h1>
                    <div class="subtitle">UNIVERSITAS NEGERI MEDAN • ILMU KOMPUTER</div>
                </div>
            </div>
            <div class="header-actions">
                <a href="auth/login.php" class="btn btn-primary btn-sm">🔐 Login</a>
                <a href="auth/register.php" class="btn btn-secondary btn-sm">📝 Daftar</a>
            </div>
        </div>

        <!-- HERO -->
        <div class="hero-section">
            <div class="hero-content">
                <div class="hero-badge">
                    <span class="badge-dot"></span>
                    Analisis Skill Gap #1 di Indonesia
                </div>
                <h1 class="hero-title">
                    Temukan <span class="highlight">Skill Gap</span> Anda<br>
                    & Siapkan <span class="highlight">Karir IT</span> Impian
                </h1>
                <p class="hero-description">
                    Analisis kesesuaian skill Anda dengan kebutuhan industri IT di Indonesia.
                    Dapatkan rekomendasi pekerjaan dan perusahaan yang sesuai dengan kompetensi Anda.
                </p>
                <div class="hero-buttons">
                    <a href="auth/register.php" class="btn btn-primary btn-lg">🚀 Mulai Sekarang</a>
                    <a href="#features" class="btn btn-outline btn-lg">Pelajari Lebih Lanjut</a>
                </div>
                <div class="hero-stats">
                    <div class="stat-item">
                        <span class="stat-number">100+</span>
                        <span class="stat-label">Skill IT</span>
                    </div>
                    <div class="stat-divider"></div>
                    <div class="stat-item">
                        <span class="stat-number">500+</span>
                        <span class="stat-label">Data Lowongan</span>
                    </div>
                    <div class="stat-divider"></div>
                    <div class="stat-item">
                        <span class="stat-number">50+</span>
                        <span class="stat-label">Perusahaan</span>
                    </div>
                </div>
            </div>
            <div class="hero-visual">
                <div class="hero-graphic">
                    <div class="graphic-circle graphic-circle-1"></div>
                    <div class="graphic-circle graphic-circle-2"></div>
                    <div class="graphic-circle graphic-circle-3"></div>
                    <div class="graphic-center">
                        <span class="graphic-emoji">💻</span>
                        <span class="graphic-text">Skill Gap<br>Analyzer</span>
                    </div>
                    <div class="floating-badge floating-badge-1">
                        <span>📊 85%</span>
                        <small>Kesesuaian</small>
                    </div>
                    <div class="floating-badge floating-badge-2">
                        <span>🔥 Python</span>
                        <small>Trending</small>
                    </div>
                    <div class="floating-badge floating-badge-3">
                        <span>💼 Gojek</span>
                        <small>Top Hiring</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- FEATURES -->
        <div id="features" class="features-section">
            <h2 class="section-title">Kenapa Harus <span class="highlight">Skill Gap Analyzer</span>?</h2>
            <p class="section-subtitle">Platform analisis skill gap pertama di Indonesia yang terintegrasi dengan data industri nyata</p>
            
            <div class="features-grid">
                <div class="feature-item">
                    <span class="feature-icon">📚</span>
                    <h4>100+ Skill IT</h4>
                    <p>Database skill terlengkap dari berbagai bidang teknologi informasi</p>
                </div>
                <div class="feature-item">
                    <span class="feature-icon">🏢</span>
                    <h4>Data Industri Real</h4>
                    <p>Data kebutuhan industri dari 500+ lowongan pekerjaan IT di Indonesia</p>
                </div>
                <div class="feature-item">
                    <span class="feature-icon">📊</span>
                    <h4>Analisis Akurat</h4>
                    <p>Metode rule-based yang membandingkan skill Anda dengan kebutuhan industri</p>
                </div>
                <div class="feature-item">
                    <span class="feature-icon">💼</span>
                    <h4>Rekomendasi Karir</h4>
                    <p>Dapatkan rekomendasi pekerjaan dan perusahaan yang sesuai</p>
                </div>
                <div class="feature-item">
                    <span class="feature-icon">📈</span>
                    <h4>Visualisasi Data</h4>
                    <p>Lihat hasil analisis dalam bentuk grafik yang mudah dipahami</p>
                </div>
                <div class="feature-item">
                    <span class="feature-icon">🔄</span>
                    <h4>Update Berkala</h4>
                    <p>Data selalu diperbarui mengikuti tren industri terbaru</p>
                </div>
            </div>
        </div>

        <!-- HOW IT WORKS -->
        <div class="how-it-works">
            <h2 class="section-title">Bagaimana <span class="highlight">Cara Kerjanya</span>?</h2>
            <div class="steps-grid">
                <div class="step-item">
                    <div class="step-number">1</div>
                    <h4>📝 Daftar Akun</h4>
                    <p>Buat akun gratis untuk mulai menganalisis skill Anda</p>
                </div>
                <div class="step-item">
                    <div class="step-number">2</div>
                    <h4>✏️ Input Skill</h4>
                    <p>Pilih skill yang Anda kuasai dari database 100+ skill IT</p>
                </div>
                <div class="step-item">
                    <div class="step-number">3</div>
                    <h4>📊 Analisis Gap</h4>
                    <p>Sistem akan membandingkan skill Anda dengan kebutuhan industri</p>
                </div>
                <div class="step-item">
                    <div class="step-number">4</div>
                    <h4>💼 Dapatkan Rekomendasi</h4>
                    <p>Terima rekomendasi pekerjaan dan perusahaan yang sesuai</p>
                </div>
            </div>
        </div>

        <!-- CTA -->
        <div class="cta-section">
            <div class="cta-content">
                <h2>Siap <span class="highlight">Meningkatkan</span> Karir IT Anda?</h2>
                <p>Mulai analisis skill gap Anda sekarang dan temukan peluang karir yang sesuai</p>
                <div class="cta-buttons">
                    <a href="auth/register.php" class="btn btn-primary btn-lg">🚀 Mulai Sekarang</a>
                    <a href="auth/login.php" class="btn btn-secondary btn-lg">🔐 Login</a>
                </div>
            </div>
        </div>

        <!-- FOOTER -->
        <div class="footer">
            <div class="footer-content">
                <div class="footer-logo">
                    <div class="footer-logo-icon-img">
                        <img src="assets/images/FMIPA.png" alt="FMIPA UNIMED">
                    </div>
                    <div>
                        <strong>Skill Gap Analyzer</strong>
                        <p>UNIVERSITAS NEGERI MEDAN • ILMU KOMPUTER</p>
                    </div>
                </div>
                <div class="footer-copyright">
                    &copy; 2026 Skill Gap Analyzer. All rights reserved.
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/script.js"></script>
</body>
</html>
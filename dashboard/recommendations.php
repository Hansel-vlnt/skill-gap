<?php
// dashboard/recommendations.php - VERSI DINAMIS DARI DATABASE
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}
require_once '../config/database.php';

$page_title = 'Rekomendasi - Skill Gap Analyzer';

// Ambil analisis terakhir
$stmt = $pdo->prepare("SELECT * FROM analysis_history WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");
$stmt->execute([$_SESSION['user_id']]);
$analysis = $stmt->fetch();

$recommendedSkills = [];
$recommendedJobs = [];
$recommendedCourses = [];
$recommendedCompanies = [];
$skillGapDetails = [];

if ($analysis) {
    $gapSkills = json_decode($analysis['gap_skills'] ?? '[]', true);
    $percentage = $analysis['match_percentage'];
    $matchedSkills = json_decode($analysis['matched_skills'] ?? '[]', true);
    
    // ==========================================
    // 1. REKOMENDASI SKILL YANG PERLU DITINGKATKAN
    // ==========================================
    if (!empty($gapSkills)) {
        $placeholders = implode(',', array_fill(0, count($gapSkills), '?'));
        $stmt = $pdo->prepare("SELECT * FROM skills WHERE skill_name IN ($placeholders)");
        $stmt->execute($gapSkills);
        $recommendedSkills = $stmt->fetchAll();
        
        foreach ($gapSkills as $skillName) {
            $stmt = $pdo->prepare("SELECT demand_level FROM industry_skills WHERE skill_name = ?");
            $stmt->execute([$skillName]);
            $industry = $stmt->fetch();
            
            $skillGapDetails[] = [
                'skill_name' => $skillName,
                'demand_level' => $industry['demand_level'] ?? 'High',
                'priority' => ($industry['demand_level'] ?? 'High') == 'High' ? '🔥 Prioritas Tinggi' : '📌 Perlu Ditingkatkan'
            ];
        }
    }
    
    // ==========================================
    // 2. REKOMENDASI PEKERJAAN DARI TRENDING_JOBS
    // ==========================================
    $trendingJobs = $pdo->query("SELECT * FROM trending_jobs ORDER BY trend_score DESC LIMIT 8")->fetchAll();
    
    if (!empty($trendingJobs)) {
        $filteredJobs = [];
        foreach ($trendingJobs as $job) {
            if ($percentage >= 60) {
                $filteredJobs[] = $job;
            } elseif ($percentage >= 40 && $job['trend_score'] >= 70) {
                $filteredJobs[] = $job;
            } elseif ($percentage < 40 && $job['trend_score'] >= 85) {
                $filteredJobs[] = $job;
            }
        }
        
        $filteredJobs = array_slice($filteredJobs, 0, 4);
        
        foreach ($filteredJobs as $job) {
            $recommendedJobs[] = [
                'job' => $job['job_title'],
                'company' => $job['company_name'],
                'match' => $percentage >= 80 ? 'Sangat Tinggi' : ($percentage >= 60 ? 'Tinggi' : 'Sedang'),
                'salary' => 'Rp ' . number_format(rand(5, 20) * 1000000, 0, ',', '.') . ' - Rp ' . number_format(rand(15, 35) * 1000000, 0, ',', '.')
            ];
        }
    }
    
    if (empty($recommendedJobs)) {
        if ($percentage >= 80) {
            $recommendedJobs = [
                ['job' => 'Senior Software Engineer', 'company' => 'Gojek', 'match' => 'Sangat Tinggi', 'salary' => 'Rp 15-25 Juta'],
                ['job' => 'Tech Lead', 'company' => 'Tokopedia', 'match' => 'Sangat Tinggi', 'salary' => 'Rp 18-30 Juta'],
                ['job' => 'AI Engineer', 'company' => 'Google Indonesia', 'match' => 'Tinggi', 'salary' => 'Rp 20-35 Juta'],
            ];
        } elseif ($percentage >= 60) {
            $recommendedJobs = [
                ['job' => 'Software Engineer', 'company' => 'Shopee', 'match' => 'Tinggi', 'salary' => 'Rp 10-18 Juta'],
                ['job' => 'Full Stack Developer', 'company' => 'Traveloka', 'match' => 'Tinggi', 'salary' => 'Rp 10-16 Juta'],
                ['job' => 'Backend Developer', 'company' => 'Bukalapak', 'match' => 'Sedang', 'salary' => 'Rp 8-15 Juta'],
            ];
        } elseif ($percentage >= 40) {
            $recommendedJobs = [
                ['job' => 'Junior Developer', 'company' => 'Startup Mid', 'match' => 'Sedang', 'salary' => 'Rp 5-10 Juta'],
                ['job' => 'IT Support', 'company' => 'Bank BCA', 'match' => 'Sedang', 'salary' => 'Rp 4-8 Juta'],
                ['job' => 'Quality Assurance', 'company' => 'Telkom Indonesia', 'match' => 'Sedang', 'salary' => 'Rp 4-7 Juta'],
            ];
        } else {
            $recommendedJobs = [
                ['job' => 'Intern', 'company' => 'Startup Kecil', 'match' => 'Rendah', 'salary' => 'Rp 2-4 Juta'],
                ['job' => 'Trainee', 'company' => 'Bootcamp', 'match' => 'Rendah', 'salary' => 'Rp 3-5 Juta'],
                ['job' => 'Magang', 'company' => 'Perusahaan', 'match' => 'Rendah', 'salary' => 'Rp 2-3 Juta'],
            ];
        }
    }
    
    // ==========================================
    // 3. REKOMENDASI PERUSAHAAN
    // ==========================================
    $allCompanies = $pdo->query("SELECT DISTINCT company_name FROM trending_jobs WHERE company_name IS NOT NULL LIMIT 10")->fetchAll(PDO::FETCH_COLUMN);
    
    if (!empty($allCompanies)) {
        shuffle($allCompanies);
        $recommendedCompanies = array_slice($allCompanies, 0, 5);
    } else {
        $recommendedCompanies = ['Gojek', 'Tokopedia', 'Shopee', 'Traveloka', 'Bukalapak'];
    }
    
    // ==========================================
    // 4. REKOMENDASI KURSUS
    // ==========================================
    if (!empty($gapSkills)) {
        $courses = [
            'Python' => ['Dicoding', 'Udemy', 'Progate'],
            'JavaScript' => ['Dicoding', 'W3Schools', 'freeCodeCamp'],
            'React' => ['Dicoding', 'React.dev', 'Udemy'],
            'Vue.js' => ['Vue Mastery', 'Udemy', 'Dicoding'],
            'Laravel' => ['Laracasts', 'Udemy', 'Dicoding'],
            'Node.js' => ['NodeSchool', 'Udemy', 'Dicoding'],
            'MySQL' => ['MySQL Official', 'Udemy', 'Dicoding'],
            'Docker' => ['Docker Official', 'Udemy', 'KodeKloud'],
            'AWS' => ['AWS Training', 'Udemy', 'Coursera'],
            'Machine Learning' => ['Coursera', 'Dicoding', 'Kaggle'],
            'Data Science' => ['Coursera', 'Dicoding', 'Kaggle'],
            'Cybersecurity' => ['Coursera', 'Cybrary', 'Udemy'],
            'Leadership' => ['LinkedIn Learning', 'Coursera', 'Skillshare'],
            'Communication' => ['LinkedIn Learning', 'Coursera', 'Skillshare'],
            'Flutter' => ['Flutter Dev', 'Udemy', 'Dicoding'],
            'Android' => ['Android Dev', 'Udemy', 'Dicoding'],
            'iOS' => ['Apple Dev', 'Udemy', 'Dicoding'],
            'UI/UX' => ['Google UX', 'Coursera', 'Dicoding'],
            'Figma' => ['Figma Official', 'Udemy', 'Skillshare'],
            'Git' => ['Git Official', 'Udemy', 'Dicoding'],
            'Jira' => ['Atlassian', 'Udemy', 'LinkedIn Learning'],
            'Agile' => ['Agile Alliance', 'Coursera', 'Udemy'],
            'Scrum' => ['Scrum.org', 'Coursera', 'Udemy'],
        ];
        
        foreach ($gapSkills as $skill) {
            $recommendedCourses[$skill] = $courses[$skill] ?? ['Dicoding', 'Udemy', 'Coursera', 'YouTube'];
        }
    }
}

include '../includes/header.php';
?>

<style>
/* ==========================================
   RECOMMENDATIONS PAGE - FULL WIDTH
   ========================================== */

/* Reset container - FULL */
.container {
    max-width: 100% !important;
    padding: 24px 48px 20px !important;
    min-height: 100vh !important;
}

.recommendations-wrapper {
    display: flex;
    gap: 32px;
    margin-top: 12px;
    flex: 1;
    min-height: 0;
    width: 100%;
}

/* ===== SIDEBAR ===== */
.sidebar {
    flex-shrink: 0;
    overflow-y: auto;
    height: calc(100vh - 220px);
    position: sticky;
    top: 20px;
}

/* ===== MAIN CONTENT ===== */
.recommendations-main {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 16px;
    width: 100%;
    min-height: 0;
    overflow-y: auto;
    padding-right: 8px;
    height: calc(100vh - 220px);
}

.recommendations-main::-webkit-scrollbar {
    width: 6px;
}

.recommendations-main::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.02);
    border-radius: 10px;
}

.recommendations-main::-webkit-scrollbar-thumb {
    background: rgba(0, 255, 136, 0.25);
    border-radius: 10px;
}

/* ===== HEADER ===== */
.recommendations-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    padding-bottom: 10px;
    border-bottom: 1px solid rgba(255,255,255,0.06);
    width: 100%;
    flex-shrink: 0;
}

.recommendations-header h1 {
    color: #ffffff;
    font-size: 44px !important;
    font-weight: 900 !important;
    letter-spacing: -1.5px !important;
    margin: 0 !important;
}

.recommendations-header h1 span {
    background: linear-gradient(135deg, #00ff88, #00cc66);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.recommendations-header .match-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(0, 255, 136, 0.08);
    border: 1px solid rgba(0, 255, 136, 0.12);
    padding: 10px 24px;
    border-radius: 50px;
    font-size: 18px;
    font-weight: 600;
    color: #00ff88;
    flex-shrink: 0;
}

/* ===== 2 KOLOM ===== */
.recommendations-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    width: 100%;
    flex-shrink: 0;
}

.jobs-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    width: 100%;
    flex-shrink: 0;
}

/* ===== RECOMMENDATION CARDS ===== */
.recommend-card {
    border-radius: 18px;
    padding: 20px 24px;
    width: 100%;
    transition: all 0.3s ease;
    box-sizing: border-box;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    min-width: 0;
}

.recommend-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.18);
}

.recommend-card .card-title {
    font-size: 22px !important;
    font-weight: 700 !important;
    margin: 0 0 4px 0;
    flex-shrink: 0;
}

.recommend-card .card-subtitle {
    color: rgba(255, 255, 255, 0.35);
    font-size: 16px !important;
    margin: 0 0 12px 0;
    flex-shrink: 0;
}

/* Card Colors */
.recommend-card.danger {
    background: rgba(255, 60, 60, 0.04);
    border: 1px solid rgba(255, 60, 60, 0.10);
}

.recommend-card.danger .card-title {
    color: #ff6666;
}

.recommend-card.green {
    background: rgba(0, 255, 136, 0.04);
    border: 1px solid rgba(0, 255, 136, 0.10);
}

.recommend-card.green .card-title {
    color: #00ff88;
}

.recommend-card.blue {
    background: rgba(0, 200, 255, 0.04);
    border: 1px solid rgba(0, 200, 255, 0.10);
}

.recommend-card.blue .card-title {
    color: #00ccff;
}

.recommend-card.purple {
    background: rgba(155, 89, 182, 0.04);
    border: 1px solid rgba(155, 89, 182, 0.10);
}

.recommend-card.purple .card-title {
    color: #9b59b6;
}

.recommend-card.yellow {
    background: rgba(255, 200, 0, 0.04);
    border: 1px solid rgba(255, 200, 0, 0.10);
}

.recommend-card.yellow .card-title {
    color: #ffcc00;
}

/* ===== SKILL GAP ITEMS ===== */
.skill-gap-items {
    display: flex;
    flex-direction: column;
    gap: 4px;
    overflow-y: auto;
    max-height: 280px;
    padding-right: 4px;
}

.skill-gap-items::-webkit-scrollbar {
    width: 4px;
}

.skill-gap-items::-webkit-scrollbar-thumb {
    background: rgba(255, 60, 60, 0.2);
    border-radius: 10px;
}

.skill-gap-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: rgba(255, 255, 255, 0.02);
    padding: 10px 14px;
    border-radius: 12px;
    border: 1px solid rgba(255, 60, 60, 0.06);
    flex-wrap: wrap;
    gap: 4px;
    overflow: hidden;
    flex-shrink: 0;
}

.skill-gap-item .skill-name {
    font-size: 18px !important;
    color: rgba(255, 255, 255, 0.85);
    font-weight: 600;
    word-break: break-word;
    overflow-wrap: break-word;
}

.skill-gap-item .skill-priority {
    color: #ffcc00;
    font-size: 14px !important;
    white-space: nowrap;
}

.skill-gap-item .skill-demand {
    background: rgba(0, 255, 136, 0.08);
    color: #00ff88;
    padding: 3px 14px;
    border-radius: 20px;
    font-size: 13px !important;
    font-weight: 600;
    white-space: nowrap;
}

/* ===== COURSE ITEMS ===== */
.course-items {
    display: flex;
    flex-direction: column;
    gap: 8px;
    overflow-y: auto;
    max-height: 280px;
    padding-right: 4px;
}

.course-items::-webkit-scrollbar {
    width: 4px;
}

.course-items::-webkit-scrollbar-thumb {
    background: rgba(0, 255, 136, 0.2);
    border-radius: 10px;
}

.course-group {
    overflow: hidden;
    flex-shrink: 0;
}

.course-group .course-skill {
    font-size: 18px !important;
    font-weight: 600;
    color: #ffffff;
    display: block;
    margin-bottom: 4px;
    word-break: break-word;
}

.course-group .course-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 4px;
}

.course-group .course-tag {
    background: rgba(255, 255, 255, 0.04);
    color: rgba(255, 255, 255, 0.55);
    padding: 3px 12px;
    border-radius: 20px;
    font-size: 14px !important;
    border: 1px solid rgba(255, 255, 255, 0.04);
    white-space: nowrap;
}

/* ===== JOB ITEMS ===== */
.job-items {
    display: flex;
    flex-direction: column;
    gap: 4px;
    overflow-y: auto;
    max-height: 260px;
    padding-right: 4px;
}

.job-items::-webkit-scrollbar {
    width: 4px;
}

.job-items::-webkit-scrollbar-thumb {
    background: rgba(0, 200, 255, 0.2);
    border-radius: 10px;
}

.job-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: rgba(255, 255, 255, 0.02);
    padding: 10px 14px;
    border-radius: 12px;
    border: 1px solid rgba(255, 255, 255, 0.04);
    transition: all 0.2s ease;
    flex-wrap: wrap;
    gap: 4px;
    overflow: hidden;
    flex-shrink: 0;
}

.job-item:hover {
    background: rgba(255, 255, 255, 0.04);
}

.job-item .job-info {
    display: flex;
    flex-direction: column;
    min-width: 0;
    flex: 1;
}

.job-item .job-title {
    font-size: 18px !important;
    color: rgba(255, 255, 255, 0.85);
    font-weight: 600;
    word-break: break-word;
    overflow-wrap: break-word;
}

.job-item .job-company {
    font-size: 14px !important;
    color: rgba(255, 255, 255, 0.35);
    word-break: break-word;
}

.job-item .job-right {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
    flex-shrink: 0;
}

.job-item .job-salary {
    color: rgba(255, 255, 255, 0.3);
    font-size: 13px !important;
}

.job-item .job-match {
    padding: 3px 14px;
    border-radius: 20px;
    font-size: 13px !important;
    font-weight: 600;
    white-space: nowrap;
}

.job-item .job-match.high {
    background: rgba(0, 255, 136, 0.12);
    color: #00ff88;
}

.job-item .job-match.medium {
    background: rgba(255, 200, 0, 0.12);
    color: #ffcc00;
}

.job-item .job-match.low {
    background: rgba(255, 60, 60, 0.12);
    color: #ff6666;
}

/* ===== COMPANY TAGS ===== */
.company-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    overflow-y: auto;
    max-height: 130px;
    padding-right: 4px;
    align-content: flex-start;
}

.company-tags::-webkit-scrollbar {
    width: 4px;
}

.company-tags::-webkit-scrollbar-thumb {
    background: rgba(155, 89, 182, 0.2);
    border-radius: 10px;
}

.company-tag {
    display: inline-flex;
    align-items: center;
    background: rgba(155, 89, 182, 0.08);
    color: #9b59b6;
    padding: 6px 18px;
    border-radius: 50px;
    font-size: 16px !important;
    font-weight: 500;
    border: 1px solid rgba(155, 89, 182, 0.12);
    white-space: nowrap;
}

/* ===== TIPS LIST ===== */
.tips-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.tips-list li {
    padding: 8px 0;
    color: rgba(255, 255, 255, 0.5);
    font-size: 17px !important;
    border-bottom: 1px solid rgba(255, 255, 255, 0.03);
    word-break: break-word;
}

.tips-list li:last-child {
    border-bottom: none;
}

.tips-list li strong {
    color: rgba(255, 255, 255, 0.75);
}

/* ===== SUMMARY BOX ===== */
.summary-box {
    background: rgba(255, 255, 255, 0.02);
    border: 1px solid rgba(255, 255, 255, 0.04);
    border-radius: 16px;
    padding: 18px 24px;
    text-align: center;
    width: 100%;
    box-sizing: border-box;
    overflow: hidden;
    flex-shrink: 0;
}

.summary-box p {
    color: rgba(255, 255, 255, 0.4);
    font-size: 17px !important;
    margin: 0;
    word-break: break-word;
}

.summary-box .summary-actions {
    display: flex;
    gap: 12px;
    justify-content: center;
    flex-wrap: wrap;
    margin-top: 12px;
}

/* ===== EMPTY STATE ===== */
.empty-state {
    background: rgba(255, 255, 255, 0.02);
    border: 1px solid rgba(255, 255, 255, 0.06);
    border-radius: 20px;
    padding: 60px 40px;
    text-align: center;
    width: 100%;
    overflow: hidden;
}

.empty-state .icon {
    font-size: 64px;
    display: block;
    margin-bottom: 16px;
}

.empty-state h3 {
    color: #ffffff;
    font-size: 24px !important;
    font-weight: 700 !important;
    margin: 0 0 8px 0;
}

.empty-state p {
    color: rgba(255, 255, 255, 0.35);
    font-size: 18px !important;
    margin: 0 0 20px 0;
}

/* ===== BUTTONS ===== */
.btn-primary {
    background: linear-gradient(135deg, #00ff88, #00cc66);
    color: #0a1628;
    border: none;
    padding: 10px 24px;
    font-size: 17px !important;
    font-weight: 700 !important;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-family: 'Inter', sans-serif;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-decoration: none;
    white-space: nowrap;
}

.btn-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 35px rgba(0, 255, 136, 0.35);
}

.btn-secondary {
    background: rgba(255, 255, 255, 0.06);
    color: rgba(255, 255, 255, 0.85);
    border: 1px solid rgba(255, 255, 255, 0.08);
    padding: 10px 24px;
    font-size: 17px !important;
    font-weight: 700 !important;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-family: 'Inter', sans-serif;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-decoration: none;
    white-space: nowrap;
}

.btn-secondary:hover {
    background: rgba(255, 255, 255, 0.12);
    transform: translateY(-3px);
}

.btn-sm {
    padding: 6px 16px;
    font-size: 14px !important;
}

.empty-state .btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 14px 32px;
}

/* ==========================================
   RESPONSIVE
   ========================================== */
@media (max-width: 1024px) {
    .container {
        padding: 16px 24px 12px !important;
    }
    
    .recommendations-row {
        grid-template-columns: 1fr;
        gap: 14px;
    }
    
    .jobs-row {
        grid-template-columns: 1fr;
        gap: 14px;
    }
    
    .sidebar {
        height: calc(100vh - 200px);
    }
    
    .recommendations-main {
        height: calc(100vh - 200px);
    }
}

@media (max-width: 768px) {
    .container {
        padding: 12px 16px 10px !important;
    }
    
    .recommendations-wrapper {
        flex-direction: column;
        gap: 16px;
    }
    
    .sidebar {
        height: auto;
        max-height: 250px;
        position: static;
    }
    
    .recommendations-main {
        height: auto;
        max-height: none;
        overflow-y: visible;
        padding-right: 0;
    }
    
    .recommendations-header h1 {
        font-size: 32px !important;
    }
    
    .recommendations-header .match-badge {
        font-size: 15px;
        padding: 8px 18px;
    }
    
    .recommend-card {
        padding: 16px 18px;
    }
    
    .recommend-card .card-title {
        font-size: 20px !important;
    }
    
    .skill-gap-items {
        max-height: 200px;
    }
    
    .skill-gap-item .skill-name {
        font-size: 16px !important;
    }
    
    .course-items {
        max-height: 200px;
    }
    
    .course-group .course-skill {
        font-size: 16px !important;
    }
    
    .job-items {
        max-height: 200px;
    }
    
    .job-item .job-title {
        font-size: 16px !important;
    }
    
    .company-tag {
        font-size: 15px !important;
    }
    
    .tips-list li {
        font-size: 16px !important;
    }
    
    .summary-box p {
        font-size: 16px !important;
    }
    
    .btn-primary {
        padding: 10px 20px;
        font-size: 16px !important;
        width: 100%;
        justify-content: center;
        white-space: normal;
    }
    
    .btn-secondary {
        padding: 10px 20px;
        font-size: 16px !important;
        width: 100%;
        justify-content: center;
        white-space: normal;
    }
    
    .summary-box .summary-actions {
        flex-direction: column;
    }
}

@media (max-width: 480px) {
    .container {
        padding: 8px 10px 8px !important;
    }
    
    .recommendations-header h1 {
        font-size: 24px !important;
    }
    
    .recommendations-header .match-badge {
        font-size: 13px;
        padding: 6px 14px;
    }
    
    .recommend-card {
        padding: 12px 14px;
    }
    
    .recommend-card .card-title {
        font-size: 17px !important;
    }
    
    .skill-gap-item .skill-name {
        font-size: 15px !important;
    }
    
    .job-item .job-title {
        font-size: 15px !important;
    }
    
    .company-tag {
        font-size: 13px !important;
        padding: 4px 12px;
    }
    
    .course-group .course-skill {
        font-size: 15px !important;
    }
    
    .course-group .course-tag {
        font-size: 13px !important;
    }
    
    .tips-list li {
        font-size: 14px !important;
    }
    
    .summary-box p {
        font-size: 14px !important;
    }
    
    .btn-primary {
        padding: 8px 16px;
        font-size: 14px !important;
    }
    
    .btn-secondary {
        padding: 8px 16px;
        font-size: 14px !important;
    }
}
</style>

<!-- ==========================================
     RECOMMENDATIONS CONTENT
========================================== -->
<div class="recommendations-wrapper">
    <?php include '../includes/sidebar.php'; ?>
    
    <div class="recommendations-main">
        
        <!-- HEADER -->
        <div class="recommendations-header">
            <h1>💼 <span>Rekomendasi</span></h1>
            <?php if ($analysis): ?>
            <?php endif; ?>
        </div>

        <?php if (!$analysis): ?>
            <!-- EMPTY STATE -->
            <div class="empty-state">
                <span class="icon">📊</span>
                <h3>Belum Ada Analisis</h3>
                <p>Lakukan analisis skill gap terlebih dahulu untuk mendapatkan rekomendasi yang sesuai.</p>
                <a href="analysis.php" class="btn-primary">📊 Lakukan Analisis</a>
            </div>
        <?php else: ?>

            <!-- ==========================================
                2 KOLOM: SKILL & KURSUS
            ========================================== -->
            <div class="recommendations-row">
                
                <!-- KIRI: Skill yang Perlu Ditingkatkan -->
                <div class="recommend-card danger">
                    <h3 class="card-title">📚 Skill yang Perlu Ditingkatkan</h3>
                    <p class="card-subtitle">Fokus pada skill ini untuk menutup gap</p>
                    
                    <div class="skill-gap-items">
                        <?php if (!empty($skillGapDetails)): ?>
                            <?php foreach ($skillGapDetails as $detail): ?>
                                <div class="skill-gap-item">
                                    <span class="skill-name">❌ <?= htmlspecialchars($detail['skill_name']) ?></span>
                                    <span class="skill-priority"><?= $detail['priority'] ?></span>
                                    <span class="skill-demand"><?= $detail['demand_level'] ?? 'High' ?> Demand</span>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p style="color:rgba(255,255,255,0.3); font-size:18px;">🎉 Semua skill sudah sesuai!</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- KANAN: Rekomendasi Belajar & Kursus -->
                <?php if (!empty($recommendedCourses)): ?>
                <div class="recommend-card green">
                    <h3 class="card-title">📖 Rekomendasi Belajar & Kursus</h3>
                    <p class="card-subtitle">Platform untuk menguasai skill yang kurang</p>
                    
                    <div class="course-items">
                        <?php foreach ($recommendedCourses as $skill => $courses): ?>
                            <div class="course-group">
                                <span class="course-skill">📌 <?= htmlspecialchars($skill) ?></span>
                                <div class="course-tags">
                                    <?php foreach ($courses as $course): ?>
                                        <span class="course-tag">📚 <?= htmlspecialchars($course) ?></span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

            </div>

            <!-- ==========================================
                2 KOLOM: PEKERJAAN & PERUSAHAAN
            ========================================== -->
            <div class="jobs-row">
                
                <!-- KIRI: Rekomendasi Pekerjaan -->
                <div class="recommend-card blue">
                    <h3 class="card-title">💼 Rekomendasi Pekerjaan</h3>
                    <p class="card-subtitle">Posisi yang cocok berdasarkan skill Anda</p>
                    
                    <div class="job-items">
                        <?php foreach ($recommendedJobs as $job): ?>
                            <?php 
                            $matchClass = 'low';
                            if ($job['match'] == 'Sangat Tinggi' || $job['match'] == 'Tinggi') {
                                $matchClass = 'high';
                            } elseif ($job['match'] == 'Sedang') {
                                $matchClass = 'medium';
                            }
                            ?>
                            <div class="job-item">
                                <div class="job-info">
                                    <span class="job-title"><?= htmlspecialchars($job['job']) ?></span>
                                    <span class="job-company">🏢 <?= htmlspecialchars($job['company']) ?></span>
                                </div>
                                <div class="job-right">
                                    <span class="job-salary"><?= $job['salary'] ?? '' ?></span>
                                    <span class="job-match <?= $matchClass ?>"><?= $job['match'] ?? 'Sesuai' ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- KANAN: Rekomendasi Perusahaan -->
                <div class="recommend-card purple">
                    <h3 class="card-title">🏢 Rekomendasi Perusahaan</h3>
                    <p class="card-subtitle">Perusahaan yang sedang mencari talenta seperti Anda</p>
                    
                    <div class="company-tags">
                        <?php foreach ($recommendedCompanies as $company): ?>
                            <span class="company-tag">🏢 <?= htmlspecialchars($company) ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>

            </div>

            <!-- ==========================================
                TIPS & SARAN
            ========================================== -->
            <div class="recommend-card yellow" style="width:100%;">
                <h3 class="card-title">💡 Tips & Saran</h3>
                <p class="card-subtitle">Langkah-langkah untuk meningkatkan karir Anda</p>
                
                <ul class="tips-list">
                    <li>🎯 <strong>Prioritas:</strong> Fokus pada skill dengan label "🔥 Prioritas Tinggi" untuk hasil maksimal.</li>
                    <li>📚 <strong>Belajar:</strong> Ikuti kursus yang direkomendasikan untuk menguasai skill yang kurang.</li>
                    <li>💼 <strong>Portofolio:</strong> Buat proyek untuk menunjukkan kemampuan Anda di skill yang sedang hype.</li>
                    <li>🤝 <strong>Networking:</strong> Terhubung dengan profesional di perusahaan target melalui LinkedIn.</li>
                    <li>📈 <strong>Update:</strong> Pantau terus tren industri dan teknologi terbaru.</li>
                </ul>
            </div>

            <!-- ==========================================
                SUMMARY
            ========================================== -->
            <div class="summary-box">
                <p>💡 Rekomendasi ini berdasarkan hasil analisis skill gap Anda. Fokus pada skill yang kurang untuk meningkatkan kesiapan kerja!</p>
                <div class="summary-actions">
                    <a href="analysis.php" class="btn-primary btn-sm">📊 Lihat Analisis</a>
                    <a href="skills.php" class="btn-secondary btn-sm">✏️ Update Skill</a>
                </div>
            </div>

        <?php endif; ?>
    </div>
</div>


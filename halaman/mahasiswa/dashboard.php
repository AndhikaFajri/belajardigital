<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Validasi session mahasiswa
if (!isset($_SESSION['user_id']) || !isset($_SESSION['level']) || $_SESSION['level'] !== 'mahasiswa') {
    header("Location: https://www.belajardigital.andhikafajri.my.id/login.php");
    exit();
}

// Definisikan base URL untuk domain
define('BASE_URL', 'https://www.belajardigital.andhikafajri.my.id/');

$title = "Dashboard Mahasiswa - BelajarDigital";
include $_SERVER['DOCUMENT_ROOT'] . '/template/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/konfigurasi/koneksi.php';

// Fungsi untuk mendapatkan statistik dengan prepared statements
function getCount($conn, $query, $params = []) {
    $stmt = mysqli_prepare($conn, $query);
    if (!$stmt) {
        // Debug: tampilkan error query dan error MySQL
        error_log("Query prepare failed: " . $query . " | Error: " . mysqli_error($conn));
        return 0;
    }
    if (!empty($params)) {
        $types = str_repeat('s', count($params));
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    return $row['total'] ?? 0;
}

// Inisialisasi variabel dengan nilai default
$total_kursus = 0;
$total_selesai = 0;
$avg_progress = 0;
$total_sertifikat = 0;
$result_available = null;

try {
    $user_id = $_SESSION['user_id'];
    
    // Hitung statistik sesuai database_structure_updated.sql
    $total_kursus = getCount($conn, "SELECT COUNT(*) as total FROM pendaftaran WHERE id_pengguna = ? AND status = 'Aktif'", [$user_id]);
    $total_selesai = getCount($conn, "SELECT COUNT(*) as total FROM pendaftaran WHERE id_pengguna = ? AND status = 'Selesai'", [$user_id]);
    
    // Hitung progress rata-rata - handle case where progress column might not exist
    $query_progress = "SELECT AVG(progress) as avg_progress FROM pendaftaran WHERE id_pengguna = ? AND status = 'Aktif'";
    $stmt_progress = mysqli_prepare($conn, $query_progress);
    if ($stmt_progress) {
        mysqli_stmt_bind_param($stmt_progress, "s", $user_id); // pastikan tipe sesuai, ganti "s" jika id_pengguna integer
        mysqli_stmt_execute($stmt_progress);
        $result_progress = mysqli_stmt_get_result($stmt_progress);
        $avg_progress_row = $result_progress ? $result_progress->fetch_assoc() : null;
        $avg_progress = $avg_progress_row && $avg_progress_row['avg_progress'] !== null ? round($avg_progress_row['avg_progress'], 1) : 0;
        mysqli_stmt_close($stmt_progress);
    } else {
        error_log("Query prepare failed (progress): " . mysqli_error($conn));
        $avg_progress = 0;
    }

    // Hitung total sertifikat
    $query_sertifikat = "SELECT COUNT(*) as total FROM pendaftaran WHERE id_pengguna = ? AND status = 'Selesai' AND progress = 100";
    $stmt_sertifikat = mysqli_prepare($conn, $query_sertifikat);
    if ($stmt_sertifikat) {
        mysqli_stmt_bind_param($stmt_sertifikat, "s", $user_id); // pastikan tipe sesuai
        mysqli_stmt_execute($stmt_sertifikat);
        $result_sertifikat = mysqli_stmt_get_result($stmt_sertifikat);
        $row_sertifikat = $result_sertifikat ? $result_sertifikat->fetch_assoc() : null;
        $total_sertifikat = $row_sertifikat ? $row_sertifikat['total'] : 0;
        mysqli_stmt_close($stmt_sertifikat);
    } else {
        // fallback jika kolom progress tidak ada
        $total_sertifikat = getCount($conn, "SELECT COUNT(*) as total FROM pendaftaran WHERE id_pengguna = ? AND status = 'Selesai'", [$user_id]);
    }
    
    // Ambil kursus yang belum diambil mahasiswa
    $query_available = "SELECT k.* FROM kursus k 
                       WHERE k.status = 'Aktif' 
                       AND k.id_kursus NOT IN (
                           SELECT id_kursus FROM pendaftaran 
                           WHERE id_pengguna = ?
                       )
                       ORDER BY k.tanggal_buat DESC";
    
    $stmt_available = mysqli_prepare($conn, $query_available);
    mysqli_stmt_bind_param($stmt_available, "i", $user_id);
    mysqli_stmt_execute($stmt_available);
    $result_available = mysqli_stmt_get_result($stmt_available);
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>

<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        --warning-gradient: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        --info-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        --shadow-light: 0 4px 20px rgba(0, 0, 0, 0.08);
        --shadow-medium: 0 8px 30px rgba(0, 0, 0, 0.12);
        --shadow-heavy: 0 15px 40px rgba(0, 0, 0, 0.15);
        --border-radius: 16px;
        --border-radius-sm: 12px;
    }

    .dashboard-header {
        background: var(--primary-gradient);
        color: white;
        padding: 2rem;
        border-radius: var(--border-radius);
        margin-bottom: 2rem;
        box-shadow: var(--shadow-medium);
    }

    .stat-card {
        background: white;
        border-radius: var(--border-radius);
        padding: 2rem;
        box-shadow: var(--shadow-light);
        transition: all 0.3s ease;
        border: none;
        position: relative;
        overflow: hidden;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-heavy);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--primary-gradient);
    }

    .stat-card.bg-primary::before {
        background: var(--primary-gradient);
    }

    .stat-card.bg-success::before {
        background: var(--success-gradient);
    }

    .stat-card.bg-warning::before {
        background: var(--warning-gradient);
    }

    .stat-card.bg-info::before {
        background: var(--info-gradient);
    }

    .stat-card .stat-icon {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        opacity: 0.8;
    }

    .stat-card .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .stat-card .stat-label {
        color: rgba(255, 255, 255, 0.9);
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
    }

    .action-card {
        background: white;
        border-radius: var(--border-radius);
        padding: 2rem;
        box-shadow: var(--shadow-light);
        transition: all 0.3s ease;
        border: none;
    }

    .action-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-medium);
    }

    .action-btn {
        border-radius: var(--border-radius-sm);
        padding: 1rem 1.5rem;
        font-weight: 600;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border: none;
        position: relative;
        overflow: hidden;
        cursor: pointer;
    }

    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .action-btn:active {
        transform: translateY(0);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .course-card-modern {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-light);
        transition: all 0.3s ease;
        overflow: hidden;
        border: none;
    }

    .course-card-modern:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-heavy);
    }

    .course-image-modern {
        height: 150px;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .course-card-modern:hover .course-image-modern {
        transform: scale(1.05);
    }

    .course-badge-modern {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(255, 255, 255, 0.9);
        color: #333;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .welcome-section {
        background: var(--primary-gradient);
        color: white;
        padding: 3rem;
        border-radius: var(--border-radius);
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }

    .welcome-section::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: float 6s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(180deg); }
    }

    .section-title-modern {
        font-size: 1.75rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 1.5rem;
        position: relative;
    }

    .section-title-modern::after {
        content: '';
        position: absolute;
        bottom: -5px;
        left: 0;
        width: 50px;
        height: 3px;
        background: var(--primary-gradient);
        border-radius: 2px;
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #6c757d;
    }

    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
</style>

<div class="container-fluid px-4 py-5">
    <!-- Welcome Section -->
    <div class="welcome-section">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="display-4 fw-bold mb-3">Selamat Datang, <?= htmlspecialchars($_SESSION['nama_lengkap']) ?>! 👋</h1>
                <p class="lead mb-4">Lanjutkan perjalanan belajar Anda dan capai tujuan pendidikan digital Anda.</p>
                <div class="d-flex gap-3">
                </div>
            </div>
            <div class="col-md-4 text-center">
                <i class="fas fa-graduation-cap" style="font-size: 6rem; opacity: 0.3;"></i>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-5">
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="stat-card bg-primary text-white">
                <div class="stat-icon">
                    <i class="fas fa-book-open"></i>
                </div>
                <div class="stat-number"><?php echo $total_kursus; ?></div>
                <div class="stat-label">Kursus Aktif</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="stat-card bg-success text-white">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-number"><?php echo $total_selesai; ?></div>
                <div class="stat-label">Kursus Selesai</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="stat-card bg-warning text-white">
                <div class="stat-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-number"><?php echo $avg_progress; ?>%</div>
                <div class="stat-label">Progress Rata-rata</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="stat-card bg-info text-white">
                <div class="stat-icon">
                    <i class="fas fa-certificate"></i>
                </div>
                <div class="stat-number"><?php echo $total_sertifikat; ?></div>
                <div class="stat-label">Sertifikat</div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-5">
        <div class="col-lg-6 mb-4">
            <div class="action-card">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                        <i class="fas fa-book"></i>
                    </div>
                    <div>
                        <h5 class="mb-1">Kursus Saya</h5>
                        <p class="text-muted mb-0">Kelola kursus yang sedang Anda ikuti</p>
                    </div>
                </div>
                <a href="courses.php" class="btn btn-primary action-btn w-100">
                    <i class="fas fa-arrow-right me-2"></i>Lihat Semua Kursus
                </a>
            </div>
        </div>
        <div class="col-lg-6 mb-4">
            <div class="action-card">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                        <i class="fas fa-user"></i>
                    </div>
                    <div>
                        <h5 class="mb-1">Profil Saya</h5>
                        <p class="text-muted mb-0">Perbarui informasi dan preferensi Anda</p>
                    </div>
                </div>
                <a href="profile.php" class="btn btn-success action-btn w-100">
                    <i class="fas fa-arrow-right me-2"></i>Kelola Profil
                </a>
            </div>
        </div>
    </div>

    <!-- Available Courses -->
    <div class="row">
        <div class="col-12">
            <h2 class="section-title-modern">
                <i class="fas fa-graduation-cap me-2"></i>Kursus Tersedia
            </h2>
            <?php if ($result_available && $result_available->num_rows > 0): ?>
                <div class="row">
                    <?php while($course = $result_available->fetch_assoc()): ?>
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="course-card-modern">
                                <div class="position-relative">
                                    <img src="../../<?= $course['gambar'] ?: 'aset/gambar/default-course.jpg' ?>" 
                                         class="course-image-modern w-100" alt="<?= htmlspecialchars($course['nama_kursus']) ?>">
                                    <span class="course-badge-modern"><?= isset($course['level_kesulitan']) ? htmlspecialchars($course['level_kesulitan']) : 'Umum' ?></span>
                                </div>
                                <div class="p-4">
                                    <h5 class="fw-bold mb-2"><?= htmlspecialchars($course['nama_kursus']) ?></h5>
                                    <p class="text-muted mb-3" style="font-size: 0.9rem;">
                                        <?= htmlspecialchars(substr($course['deskripsi'], 0, 80)) ?>...
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i><?= $course['durasi'] ?> jam
                                        </small>
                                        <span class="badge bg-light text-dark">
                                            Rp <?= number_format($course['harga'], 0, ',', '.') ?>
                                        </span>
                                    </div>
                                    <a href="course_detail.php?id=<?= $course['id_kursus'] ?>" class="btn btn-primary w-100">
                                        <i class="fas fa-eye me-2"></i>Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-graduation-cap"></i>
                    <h4 class="mt-3">Semua kursus sudah Anda ambil!</h4>
                    <p class="text-muted">Tunggu kursus baru yang akan datang atau periksa kembali kursus yang sudah Anda ikuti.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../../template/footer.php'; ?>

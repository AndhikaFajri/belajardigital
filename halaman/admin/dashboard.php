<?php
session_start();

// Validasi session admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['level']) || $_SESSION['level'] !== 'admin') {
    header("Location: ../../login.php");
    exit();
}

$title = "Dashboard Admin - BelajarDigital";
include '../../template/header.php';
include '../../konfigurasi/koneksi.php';

// Fungsi untuk mendapatkan statistik dengan prepared statements
function getCount($conn, $query, $params = []) {
    $stmt = mysqli_prepare($conn, $query);
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

try {
    // Hitung statistik untuk admin
    $total_pengguna = getCount($conn, "SELECT COUNT(*) as total FROM pengguna WHERE level != 'admin'");
    $total_kursus = getCount($conn, "SELECT COUNT(*) as total FROM kursus WHERE status = 'Aktif'");
    $total_pendaftaran = getCount($conn, "SELECT COUNT(*) as total FROM pendaftaran WHERE status = 'Aktif'");
    $total_mahasiswa = getCount($conn, "SELECT COUNT(*) as total FROM pengguna WHERE level = 'mahasiswa'");
    
    // Ambil kursus terbaru untuk ditampilkan
$query_kursus_baru = "SELECT k.* FROM kursus k WHERE k.status = 'Aktif' ORDER BY k.tanggal_buat DESC LIMIT 6";
    $result_kursus_baru = mysqli_query($conn, $query_kursus_baru);
    
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
                <p class="lead mb-4">Kelola sistem pembelajaran digital dengan mudah dan efisien.</p>
                <div class="d-flex gap-3">
                </div>
            </div>
            <div class="col-md-4 text-center">
                <i class="fas fa-user-shield" style="font-size: 6rem; opacity: 0.3;"></i>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-5">
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="stat-card bg-primary text-white">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-number"><?php echo $total_pengguna; ?></div>
                <div class="stat-label">Total Pengguna</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="stat-card bg-success text-white">
                <div class="stat-icon">
                    <i class="fas fa-book"></i>
                </div>
                <div class="stat-number"><?php echo $total_kursus; ?></div>
                <div class="stat-label">Total Kursus</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="stat-card bg-warning text-white">
                <div class="stat-icon">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <div class="stat-number"><?php echo $total_pendaftaran; ?></div>
                <div class="stat-label">Total Pendaftaran</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="stat-card bg-info text-white">
                <div class="stat-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="stat-number"><?php echo $total_mahasiswa; ?></div>
                <div class="stat-label">Total Mahasiswa</div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-5">
        <div class="col-lg-6 mb-4">
            <div class="action-card">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <h5 class="mb-1">Kelola Pengguna</h5>
                        <p class="text-muted mb-0">Kelola data pengguna mahasiswa dan instruktur</p>
                    </div>
                </div>
                <a href="manage_users.php" class="btn btn-primary action-btn w-100">
                    <i class="fas fa-arrow-right me-2"></i>Kelola Pengguna
                </a>
            </div>
        </div>
        <div class="col-lg-6 mb-4">
            <div class="action-card">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                        <i class="fas fa-book"></i>
                    </div>
                    <div>
                        <h5 class="mb-1">Kelola Kursus</h5>
                        <p class="text-muted mb-0">Kelola kursus, materi, dan konten pembelajaran</p>
                    </div>
                </div>
                <a href="manage_courses.php" class="btn btn-success action-btn w-100">
                    <i class="fas fa-arrow-right me-2"></i>Kelola Kursus
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Courses -->
    <div class="row">
        <div class="col-12">
            <h2 class="section-title-modern">
                <i class="fas fa-graduation-cap me-2"></i>Kursus Terbaru
            </h2>
            <?php if ($result_kursus_baru && $result_kursus_baru->num_rows > 0): ?>
                <div class="row">
                    <?php while($course = $result_kursus_baru->fetch_assoc()): ?>
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
                                    <a href="manage_courses.php" class="btn btn-primary w-100">
                                        <i class="fas fa-edit me-2"></i>Kelola Kursus
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-graduation-cap"></i>
                    <h4 class="mt-3">Belum ada kursus tersedia</h4>
                    <p class="text-muted">Tambahkan kursus baru untuk memulai.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../../template/footer.php'; ?>

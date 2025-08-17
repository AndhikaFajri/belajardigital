<?php
// Cek apakah session sudah dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek login
if(!isset($_SESSION['login'])) {
    header("Location: ../../login.php");
    exit();
}

// Ambil data user
$id_pengguna = $_SESSION['user_id'] ?? $_SESSION['id_pengguna'] ?? null;
$nama_lengkap = $_SESSION['nama_lengkap'] ?? 'User';
$level = $_SESSION['level'] ?? 'mahasiswa';

// Fungsi untuk cek halaman aktif
function isActive($page) {
    return basename($_SERVER['PHP_SELF']) == $page ? 'active' : '';
}

// Fungsi untuk mendapatkan base URL
function base_url($path = '') {
    return 'https://www.belajardigital.andhikafajri.my.id/' . ltrim($path, '/');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'BelajarDigital'; ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- DataTables -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="<?php echo base_url('aset/css/style.css'); ?>" rel="stylesheet" type="text/css">
    
    <style>
        :root {
            --primary-color: #6366f1;
            --secondary-color: #8b5cf6;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --dark-color: #1f2937;
            --light-color: #f8fafc;
            --border-color: #e5e7eb;
        }

        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--light-color);
            color: var(--dark-color);
        }

        .navbar-modern {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            box-shadow: 0 4px 20px rgba(99, 102, 241, 0.15);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 1rem 0;
        }

        .navbar-brand-modern {
            font-weight: 700;
            font-size: 1.5rem;
            color: white !important;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .navbar-brand-modern i {
            font-size: 1.75rem;
        }

        .nav-link-modern {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            padding: 0.75rem 1.25rem;
            margin: 0 0.25rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            position: relative;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-link-modern:hover {
            color: white !important;
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-1px);
        }

        .nav-link-modern.active {
            color: white !important;
            background: rgba(255, 255, 255, 0.2);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .user-profile-section {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-profile-btn {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 50px;
            padding: 0.5rem 1rem;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .user-profile-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-1px);
            color: white;
            text-decoration: none;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .logout-btn {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .logout-btn:hover {
            background: rgba(239, 68, 68, 0.2);
            color: white;
            text-decoration: none;
        }

        .navbar-toggler {
            border: none;
            padding: 0.5rem;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .navbar-toggler:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 0.75%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        @media (max-width: 991.98px) {
            .navbar-modern {
                padding: 0.75rem 0;
            }
            
            .user-profile-section {
                margin-top: 1rem;
                padding-top: 1rem;
                border-top: 1px solid rgba(255, 255, 255, 0.1);
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }
            
            .user-profile-btn,
            .logout-btn {
                width: 100%;
                justify-content: flex-start;
                border-radius: 8px;
            }
        }
    </style>
</head>
<body>
    <!-- Modern Navbar -->
    <nav class="navbar navbar-expand-lg navbar-modern">
        <div class="container-fluid">
            <!-- Brand -->
            <a class="navbar-brand-modern" href="<?php echo base_url('halaman/' . $level . '/dashboard.php'); ?>">
                <i class="fas fa-graduation-cap"></i>
                BelajarDigital
            </a>

            <!-- Toggler -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarModern" aria-controls="navbarModern" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar Content -->
            <div class="collapse navbar-collapse" id="navbarModern">
                <!-- Navigation Links -->
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <?php if($level == 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link-modern <?php echo isActive('dashboard.php'); ?>" 
                               href="<?php echo base_url('halaman/admin/dashboard.php'); ?>">
                                <i class="fas fa-tachometer-alt"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link-modern <?php echo isActive('manage_courses.php'); ?>" 
                               href="<?php echo base_url('halaman/admin/manage_courses.php'); ?>">
                                <i class="fas fa-book"></i>
                                Kursus
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link-modern <?php echo isActive('manage_users.php'); ?>" 
                               href="<?php echo base_url('halaman/admin/manage_users.php'); ?>">
                                <i class="fas fa-users"></i>
                                Pengguna
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link-modern <?php echo isActive('reports.php'); ?>" 
                               href="<?php echo base_url('halaman/admin/reports.php'); ?>">
                                <i class="fas fa-chart-bar"></i>
                                Laporan
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link-modern <?php echo isActive('dashboard.php'); ?>" 
                               href="<?php echo base_url('halaman/mahasiswa/dashboard.php'); ?>">
                                <i class="fas fa-home"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link-modern <?php echo isActive('courses.php'); ?>" 
                               href="<?php echo base_url('halaman/mahasiswa/courses.php'); ?>">
                                <i class="fas fa-book-open"></i>
                                Kursus Saya
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>

                <!-- User Profile & Logout Section -->
                <div class="user-profile-section">
                    <a href="<?php echo base_url('halaman/' . htmlspecialchars($level) . '/profile.php'); ?>" 
                       class="user-profile-btn">
                        <div class="user-avatar">
                            <?php echo strtoupper(substr($nama_lengkap, 0, 1)); ?>
                        </div>
                        <span><?php echo htmlspecialchars($nama_lengkap); ?></span>
                    </a>
                    <a href="<?php echo base_url('aksi/aksi_logout.php'); ?>" 
                       class="logout-btn"
                       onclick="return confirm('Apakah Anda yakin ingin logout?')">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    
    <!-- Custom JS -->
    <script src="<?php echo base_url('aset/js/script.js'); ?>"></script>
</body>
</html>

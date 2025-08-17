<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BelajarDigital - Platform LMS Terbaik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="aset/css/style.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #1e40af;
            --accent-color: #3b82f6;
            --text-dark: #1f2937;
            --text-light: #6b7280;
            --bg-light: #f9fafb;
            --border-color: #e5e7eb;
        }
        
        * {
            font-family: 'Inter', sans-serif;
        }
        
        body {
            background-color: var(--bg-light);
            color: var(--text-dark);
            line-height: 1.6;
        }
        
        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--primary-color) !important;
        }
        
        .nav-link {
            font-weight: 500;
            color: var(--text-dark) !important;
            transition: color 0.3s ease;
        }
        
        .nav-link:hover {
            color: var(--primary-color) !important;
        }
        
        .hero-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 120px 0;
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><polygon fill="rgba(255,255,255,0.1)" points="0,1000 1000,0 1000,1000"/></svg>');
            background-size: cover;
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
        }
        
        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 1.5rem;
        }
        
        .hero-subtitle {
            font-size: 1.25rem;
            font-weight: 400;
            margin-bottom: 2rem;
            opacity: 0.9;
        }
        
        .btn-hero {
            padding: 15px 30px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        
        .btn-hero:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }
        
        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 1rem;
        }
        
        .section-subtitle {
            font-size: 1.2rem;
            color: var(--text-light);
            margin-bottom: 3rem;
        }
        
        .course-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border: 1px solid var(--border-color);
        }
        
        .course-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }
        
        .course-image {
            height: 200px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .course-card:hover .course-image {
            transform: scale(1.05);
        }
        
        .course-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(37, 99, 235, 0.9);
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .feature-card {
            background: white;
            padding: 40px 30px;
            border-radius: 16px;
            text-align: center;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border: 1px solid var(--border-color);
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        
        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 2rem;
        }
        
        .stats-section {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 80px 0;
        }
        
        .stat-item {
            text-align: center;
            padding: 20px;
        }
        
        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .stat-label {
            font-size: 1.2rem;
            opacity: 0.9;
        }
        
        .footer {
            background: var(--text-dark);
            color: white;
            padding: 80px 0 40px;
            margin-top: 100px;
        }
        
        .footer h5 {
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            font-weight: 700;
        }
        
        .footer h6 {
            font-size: 1.2rem;
            margin-bottom: 1.25rem;
            font-weight: 600;
            color: #ffffff;
        }
        
        .footer p {
            color: rgba(255, 255, 255, 0.85);
            line-height: 1.7;
            margin-bottom: 1.5rem;
        }
        
        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .footer-links li {
            margin-bottom: 12px;
            line-height: 1.6;
        }
        
        .footer-links a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 400;
            display: inline-block;
        }
        
        .footer-links a:hover {
            color: #3b82f6;
            transform: translateX(5px);
        }
        
        .footer-links li {
            color: rgba(255, 255, 255, 0.8);
            font-weight: 400;
        }
        
        .social-links a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            margin-right: 12px;
            transition: all 0.3s ease;
            text-decoration: none;
            font-size: 1.2rem;
        }
        
        .social-links a:hover {
            background: #3b82f6;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(59, 130, 246, 0.4);
        }
        
        .footer hr {
            border-color: rgba(255, 255, 255, 0.2);
            margin: 3rem 0 2rem;
        }
        
        .footer .text-center p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
            margin: 0;
        }
        
        @media (max-width: 768px) {
            .footer {
                padding: 60px 0 30px;
                margin-top: 60px;
            }
            
            .footer h5 {
                font-size: 1.3rem;
                margin-bottom: 1.2rem;
            }
            
            .footer h6 {
                font-size: 1.1rem;
                margin-bottom: 1rem;
            }
            
            .footer .col-lg-4,
            .footer .col-lg-2,
            .footer .col-lg-3 {
                margin-bottom: 2.5rem;
            }
            
            .footer .col-lg-4:last-child,
            .footer .col-lg-2:last-child,
            .footer .col-lg-3:last-child {
                margin-bottom: 0;
            }
        }
        
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .section-title {
                font-size: 2rem;
            }
        }
        
        .fade-in {
            animation: fadeIn 0.6s ease-in;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">
                <i class="fas fa-graduation-cap me-2"></i>BelajarDigital
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link fw-medium" href="#home">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-medium" href="#courses">Kursus</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-medium" href="#features">Fitur</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-medium" href="#about">Tentang</a>
                    </li>
                    <li class="nav-item ms-2">
                        <a class="btn btn-primary px-4" href="login.php">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero-section">
        <div class="container">
            <div class="row align-items-center" style="min-height: 100vh;">
                <div class="col-lg-6 hero-content">
                    <div class="fade-in">
                        <h1 class="hero-title">
                            Tingkatkan Skill Digitalmu dengan Platform LMS Terbaik
                        </h1>
                        <p class="hero-subtitle">
                            BelajarDigital menyediakan berbagai kursus berkualitas untuk meningkatkan kemampuan digital Anda. 
                            Mulai dari pemula hingga profesional.
                        </p>
                        <div class="d-flex flex-wrap gap-3">
                            <a href="login.php" class="btn btn-light btn-hero">
                                <i class="fas fa-rocket me-2"></i>Mulai Belajar
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="fade-in" style="animation-delay: 0.2s;">
                        <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" 
                             alt="Mahasiswa Belajar Online" class="img-fluid rounded-3 shadow-lg">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-number" data-count="1000">0</div>
                        <div class="stat-label">Pelajar Aktif</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-number" data-count="50">0</div>
                        <div class="stat-label">Kursus Tersedia</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-number" data-count="95">0</div>
                        <div class="stat-label">Tingkat Penyelesaian</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-number" data-count="4.8">0</div>
                        <div class="stat-label">Rating Rata-rata</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Courses Section -->
    <section id="courses" class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2 class="section-title fade-in">Kursus Unggulan</h2>
                    <p class="section-subtitle fade-in">Pilih kursus sesuai dengan kebutuhan dan minat Anda</p>
                </div>
            </div>
            <div class="row">
                <?php
                include 'konfigurasi/koneksi.php';
                $query = "SELECT * FROM kursus WHERE status = 'Aktif' ORDER BY tanggal_buat DESC LIMIT 6";
                $result = mysqli_query($conn, $query);
                if (!$result) {
                    die("Query Error: " . mysqli_error($conn));
                }
                
                $delay = 0;
                while($row = mysqli_fetch_assoc($result)) {
                    $delay += 0.1;
                ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <?php error_log("Gambar: " . $row['gambar']); ?>
                    <div class="course-card fade-in" style="animation-delay: <?php echo $delay; ?>s;">
                        <div class="position-relative">
                            <img src="<?php echo !empty($row['gambar']) ? htmlspecialchars($row['gambar']) : 'https://via.placeholder.com/400x200/2563eb/ffffff?text=' . urlencode(substr($row['nama_kursus'], 0, 20)); ?>" 
                                 class="course-image w-100" alt="<?php echo htmlspecialchars($row['nama_kursus']); ?>" 
                                 onerror="this.src='https://via.placeholder.com/400x200/2563eb/ffffff?text=Kursus'">
                                    <!-- Level kesulitan badge removed as column no longer exists -->
                        </div>
                        <div class="card-body p-4">
                            <h5 class="card-title fw-bold mb-3"><?php echo htmlspecialchars($row['nama_kursus']); ?></h5>
                            <p class="card-text text-muted mb-3">
                                <?php echo htmlspecialchars(substr($row['deskripsi'], 0, 120)); ?>...
                            </p>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-primary fw-bold fs-5">
                                    Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?>
                                </span>
                                <span class="badge bg-light text-dark">
                                    <i class="fas fa-clock me-1"></i><?php echo htmlspecialchars($row['durasi']); ?>
                                </span>
                            </div>
                            <a href="login.php" class="btn btn-primary w-100">
                                <i class="fas fa-sign-in-alt me-2"></i>Daftar Sekarang
                            </a>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
            <div class="text-center mt-4">
                <a href="login.php" class="btn btn-outline-primary btn-lg px-5">
                    <i class="fas fa-arrow-right me-2"></i>Lihat Semua Kursus
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2 class="section-title fade-in">Mengapa BelajarDigital?</h2>
                    <p class="section-subtitle fade-in">Platform belajar yang dirancang untuk kesuksesan Anda</p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <div class="feature-card fade-in">
                        <div class="feature-icon">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Pengajar Profesional</h4>
                        <p class="text-muted">Belajar dari instruktur berpengalaman dengan sertifikasi industri</p>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <div class="feature-card fade-in" style="animation-delay: 0.1s;">
                        <div class="feature-icon">
                            <i class="fas fa-certificate"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Sertifikat Digital</h4>
                        <p class="text-muted">Dapatkan sertifikat resmi yang diakui industri</p>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <div class="feature-card fade-in" style="animation-delay: 0.2s;">
                        <div class="feature-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Belajar Fleksibel</h4>
                        <p class="text-muted">Akses kapan saja, dimana saja sesuai jadwal Anda</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="fade-in">
                        <h2 class="section-title">Tentang BelajarDigital</h2>
                        <p class="lead text-muted mb-4">
                            BelajarDigital adalah platform Learning Management System (LMS) yang dirancang untuk membantu Anda 
                            menguasai skill digital melalui kursus-kursus berkualitas tinggi.
                        </p>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <i class="fas fa-check-circle text-primary me-2"></i>
                                <span>Konten berkualitas premium</span>
                            </div>
                            <div class="col-6 mb-3">
                                <i class="fas fa-check-circle text-primary me-2"></i>
                                <span>Mentor berpengalaman</span>
                            </div>
                            <div class="col-6 mb-3">
                                <i class="fas fa-check-circle text-primary me-2"></i>
                                <span>Platform user-friendly</span>
                            </div>
                            <div class="col-6 mb-3">
                                <i class="fas fa-check-circle text-primary me-2"></i>
                                <span>Dukungan 24/7</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="fade-in" style="animation-delay: 0.2s;">
                        <img src="https://images.unsplash.com/photo-1551434678-e076c223a692?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" 
                             alt="Team Collaboration" class="img-fluid rounded-3 shadow">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5 class="fw-bold mb-3">BelajarDigital</h5>
                    <p class="text-light">
                        Platform LMS terbaik untuk meningkatkan skill digital Anda dengan kursus berkualitas tinggi.
                    </p>
                    <div class="social-links">
                        <a href="https://www.instagram.com/doremifa.jri/" class="text-light me-3"><i class="fab fa-instagram"></i></a>
                        <a href="https://www.linkedin.com/in/andhika-fajri-septiawan" class="text-light"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <h6 class="fw-bold mb-3">Link Cepat</h6>
                    <ul class="footer-links">
                        <li><a href="#home">Beranda</a></li>
                        <li><a href="#courses">Kursus</a></li>
                        <li><a href="#features">Fitur</a></li>
                        <li><a href="#about">Tentang</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 mb-4">
                    <h6 class="fw-bold mb-3">Kontak</h6>
                    <ul class="footer-links">
                        <li><i class="fas fa-envelope me-2"></i> dhikafajri@gmail.com</li>
                        <li><i class="fas fa-phone me-2"></i> +62 813-7644-9306</li>
                        <li><i class="fas fa-map-marker-alt me-2"></i> Jakarta, Indonesia</li>
                    </ul>
                </div>
            </div>
            <hr class="my-4">
            <div class="row">
                <div class="col-12 text-center">
                    <p class="mb-0">&copy; 2025 BelajarDigital. Developed by Andhika Fajri Septiawan.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Smooth scroll for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.style.background = 'rgba(255, 255, 255, 0.98)';
                navbar.style.boxShadow = '0 2px 20px rgba(0, 0, 0, 0.1)';
            } else {
                navbar.style.background = 'rgba(255, 255, 255, 0.95)';
                navbar.style.boxShadow = '0 1px 3px rgba(0, 0, 0, 0.1)';
            }
        });

        // Counter animation
        function animateCounter(element, target) {
            let current = 0;
            const increment = target / 100;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                element.textContent = Math.floor(current);
            }, 20);
        }

        // Intersection Observer for animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animationPlayState = 'running';
                    
                    // Counter animation for stats
                    if (entry.target.classList.contains('stat-number')) {
                        const target = parseFloat(entry.target.getAttribute('data-count'));
                        animateCounter(entry.target, target);
                    }
                }
            });
        }, observerOptions);

        // Observe all fade-in elements
        document.querySelectorAll('.fade-in').forEach(el => {
            el.style.animationPlayState = 'paused';
            observer.observe(el);
        });

        // Observe stat numbers
        document.querySelectorAll('.stat-number').forEach(el => {
            observer.observe(el);
        });
    </script>
</body>
</html>

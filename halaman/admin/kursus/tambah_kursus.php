<?php
session_start();
require_once '../../../konfigurasi/koneksi.php';
include '../../../template/header.php';

$title = "Tambah Kursus Baru";
?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb bg-transparent p-0">
                    <li class="breadcrumb-item"><a href="../dashboard.php" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="../manage_courses.php" class="text-decoration-none">Kelola Kursus</a></li>
                    <li class="breadcrumb-item active text-primary" aria-current="page">Tambah Kursus Baru</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-primary text-white py-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-white bg-opacity-25 rounded-circle p-3 me-3">
                            <i class="fas fa-plus-circle fa-2x"></i>
                        </div>
                        <div>
                            <h3 class="mb-0 fw-bold">Tambah Kursus Baru</h3>
                            <p class="mb-0 opacity-75">Lengkapi informasi kursus dengan data yang akurat</p>
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-4">
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <?= $_SESSION['error']; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>
                    
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            <?= $_SESSION['success']; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php unset($_SESSION['success']); ?>
                    <?php endif; ?>

                    <form action="../../../aksi/aksi_tambah_kursus.php" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-4">
                                    <label for="nama_kursus" class="form-label fw-semibold">
                                        <i class="fas fa-book text-primary me-2"></i>Nama Kursus *
                                    </label>
                                    <input type="text" class="form-control form-control-lg" id="nama_kursus" name="nama_kursus" 
                                           required maxlength="200" placeholder="Contoh: Pemrograman Web Dasar">
                                    <div class="invalid-feedback">Nama kursus wajib diisi</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="mb-4">
                                    <label for="deskripsi" class="form-label fw-semibold">
                                        <i class="fas fa-align-left text-primary me-2"></i>Deskripsi Kursus *
                                    </label>
                                    <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4" 
                                              required maxlength="1000" placeholder="Jelaskan secara detail mengenai kursus ini..."></textarea>
                                    <div class="form-text"><span id="deskripsi-count">0</span>/1000 karakter</div>
                                    <div class="invalid-feedback">Deskripsi kursus wajib diisi</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="kategori" class="form-label fw-semibold">
                                        <i class="fas fa-tags text-primary me-2"></i>Kategori *
                                    </label>
                                    <select class="form-select form-select-lg" id="kategori" name="kategori" required>
                                        <option value="">Pilih Kategori</option>
                                        <option value="pemrograman">Pemrograman</option>
                                        <option value="database">Database</option>
                                        <option value="design">Design</option>
                                        <option value="marketing">Marketing</option>
                                        <option value="bisnis">Bisnis</option>
                                    </select>
                                    <div class="invalid-feedback">Kategori wajib dipilih</div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="harga" class="form-label fw-semibold">
                                        <i class="fas fa-dollar-sign text-primary me-2"></i>Harga (Rp) *
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" class="form-control form-control-lg" id="harga" name="harga" 
                                               min="0" required placeholder="0">
                                    </div>
                                    <div class="invalid-feedback">Harga wajib diisi</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="durasi" class="form-label fw-semibold">
                                        <i class="fas fa-clock text-primary me-2"></i>Durasi (jam) *
                                    </label>
                                    <div class="input-group">
                                        <input type="number" class="form-control form-control-lg" id="durasi" name="durasi" 
                                               min="1" required placeholder="1">
                                        <span class="input-group-text">jam</span>
                                    </div>
                                    <div class="invalid-feedback">Durasi wajib diisi</div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="gambar" class="form-label fw-semibold">
                                        <i class="fas fa-image text-primary me-2"></i>Gambar Kursus
                                    </label>
                                    <input type="file" class="form-control form-control-lg" id="gambar" name="gambar" 
                                           accept="image/*">
                                    <div class="form-text">
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Format: JPG, JPEG, PNG, GIF (Maksimal 2MB)
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                                    <a href="../manage_courses.php" class="btn btn-outline-secondary btn-lg px-4">
                                        <i class="fas fa-arrow-left me-2"></i>Kembali
                                    </a>
                                    <button type="submit" class="btn btn-primary btn-lg px-5">
                                        <i class="fas fa-save me-2"></i>Simpan Kursus
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        border: none;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    
    .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(102, 126, 234, 0.4);
    }
    
    .breadcrumb-item a {
        color: #667eea;
        text-decoration: none;
    }
    
    .breadcrumb-item a:hover {
        color: #764ba2;
    }
    
    .form-control, .form-select {
        border-radius: 0.5rem;
        border: 1px solid #e0e0e0;
    }
    
    .input-group-text {
        background-color: #f8f9fa;
        border: 1px solid #e0e0e0;
    }
</style>

<script>
    // Character counter for description
    const deskripsi = document.getElementById('deskripsi');
    const deskripsiCount = document.getElementById('deskripsi-count');
    
    deskripsi.addEventListener('input', function() {
        deskripsiCount.textContent = this.value.length;
    });
    
    // Form validation
    const form = document.querySelector('.needs-validation');
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
    });
</script>

<?php include '../../../template/footer.php'; ?>

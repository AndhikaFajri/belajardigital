<?php
session_start();
require_once '../../../konfigurasi/koneksi.php';
include '../../../template/header.php';

$title = "Tambah Pengguna Baru";
?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb bg-transparent p-0">
                    <li class="breadcrumb-item"><a href="../dashboard.php" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="../manage_users.php" class="text-decoration-none">Kelola Pengguna</a></li>
                    <li class="breadcrumb-item active text-primary" aria-current="page">Tambah Pengguna Baru</li>
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
                            <i class="fas fa-user-plus fa-2x"></i>
                        </div>
                        <div>
                            <h3 class="mb-0 fw-bold">Tambah Pengguna Baru</h3>
                            <p class="mb-0 opacity-75">Lengkapi informasi pengguna dengan data yang akurat</p>
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

                    <form action="../../../aksi/aksi_tambah_pengguna.php" method="POST" class="needs-validation" novalidate>
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-4">
                                    <label for="nama_lengkap" class="form-label fw-semibold">
                                        <i class="fas fa-user text-primary me-2"></i>Nama Lengkap *
                                    </label>
                                    <input type="text" class="form-control form-control-lg" id="nama_lengkap" name="nama_lengkap" 
                                           required maxlength="100" placeholder="Contoh: John Doe">
                                    <div class="invalid-feedback">Nama lengkap wajib diisi</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="email" class="form-label fw-semibold">
                                        <i class="fas fa-envelope text-primary me-2"></i>Email *
                                    </label>
                                    <input type="email" class="form-control form-control-lg" id="email" name="email" 
                                           required maxlength="100" placeholder="contoh@email.com">
                                    <div class="invalid-feedback">Email wajib diisi dengan format yang valid</div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="username" class="form-label fw-semibold">
                                        <i class="fas fa-user-tag text-primary me-2"></i>Username *
                                    </label>
                                    <input type="text" class="form-control form-control-lg" id="username" name="username" 
                                           required maxlength="50" placeholder="john_doe">
                                    <div class="invalid-feedback">Username wajib diisi</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="password" class="form-label fw-semibold">
                                        <i class="fas fa-lock text-primary me-2"></i>Password *
                                    </label>
                                    <input type="password" class="form-control form-control-lg" id="password" name="password" 
                                           required minlength="6" placeholder="Minimal 6 karakter">
                                    <div class="invalid-feedback">Password minimal 6 karakter</div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="level" class="form-label fw-semibold">
                                        <i class="fas fa-layer-group text-primary me-2"></i>Level *
                                    </label>
                                    <select class="form-select form-select-lg" id="level" name="level" required>
                                        <option value="">Pilih Level</option>
                                        <option value="admin">Admin</option>
                                        <option value="mahasiswa">Mahasiswa</option>
                                    </select>
                                    <div class="invalid-feedback">Level wajib dipilih</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                                    <a href="../manage_users.php" class="btn btn-outline-secondary btn-lg px-4">
                                        <i class="fas fa-arrow-left me-2"></i>Kembali
                                    </a>
                                    <button type="submit" class="btn btn-primary btn-lg px-5">
                                        <i class="fas fa-save me-2"></i>Simpan Pengguna
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
    
    .btn-outline-secondary {
        border-color: #6c757d;
        color: #6c757d;
    }
    
    .btn-outline-secondary:hover {
        background-color: #6c757d;
        border-color: #6c757d;
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
    
    .form-label {
        color: #495057;
    }
</style>

<script>
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

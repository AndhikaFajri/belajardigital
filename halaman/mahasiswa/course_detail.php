<?php
$title = "Detail Kursus - BelajarDigital";
include '../../template/header.php';
include '../../konfigurasi/koneksi.php';

$id_kursus = $_GET['id'];
$query = "SELECT * FROM kursus WHERE id_kursus = '$id_kursus'";
$result = mysqli_query($conn, $query);
$kursus = mysqli_fetch_assoc($result);

// Cek apakah user sudah login
$is_logged_in = isset($_SESSION['user_id']);
$user_id = $is_logged_in ? $_SESSION['user_id'] : 0;

// Cek apakah sudah terdaftar
$is_enrolled = false;
if ($is_logged_in) {
    $check_query = "SELECT id_pendaftaran FROM pendaftaran WHERE id_pengguna = ? AND id_kursus = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("ii", $user_id, $id_kursus);
    $stmt->execute();
    $result_check = $stmt->get_result();
    $is_enrolled = $result_check->num_rows > 0;
    $stmt->close();
}
?>

<div class="container mt-4">
    <!-- Notifikasi Container -->
    <div id="notification-container" style="position: fixed; top: 20px; right: 20px; z-index: 1050;"></div>
    
    <div class="row">
        <div class="col-md-8">
<?php if ($kursus): ?>
    <h1><?php echo htmlspecialchars($kursus['nama_kursus']); ?></h1>
    <p class="text-muted">
        <i class="fas fa-tag"></i> Kategori: <?php echo htmlspecialchars($kursus['kategori']); ?> |
        <i class="fas fa-clock"></i> Durasi: <?php echo htmlspecialchars($kursus['durasi']); ?> jam
    </p>
<?php else: ?>
    <h1>Kursus tidak ditemukan</h1>
    <p class="text-danger">Maaf, kursus yang Anda cari tidak tersedia.</p>
    <?php include '../../template/footer.php'; exit; ?>
<?php endif; ?>
            <p><?php echo htmlspecialchars($kursus['deskripsi']); ?></p>
            
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Materi yang Akan Dipelajari</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li><i class="fas fa-check-circle text-success me-2"></i> Pengenalan dasar</li>
                        <li><i class="fas fa-check-circle text-success me-2"></i> Praktik langsung</li>
                        <li><i class="fas fa-check-circle text-success me-2"></i> Studi kasus</li>
                        <li><i class="fas fa-check-circle text-success me-2"></i> Proyek akhir</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <img src="<?php echo !empty($kursus['gambar']) ? '../../' . htmlspecialchars($kursus['gambar']) : 'https://via.placeholder.com/400x200/2563eb/ffffff?text=' . urlencode(substr($kursus['nama_kursus'], 0, 20)); ?>" 
                     class="card-img-top" alt="<?php echo htmlspecialchars($kursus['nama_kursus']); ?>"
                     style="height: 200px; object-fit: cover;">
                <div class="card-body">
                    <h5 class="card-title">Detail Kursus</h5>
                    <p class="card-text">
                        <strong>Harga:</strong> Rp <?php echo number_format($kursus['harga'], 0, ',', '.'); ?>
                    </p>
                    
                    <?php if (!$is_logged_in): ?>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i> Silakan login terlebih dahulu untuk mendaftar kursus ini.
                        </div>
                        <a href="../../login.php" class="btn btn-primary w-100">
                            <i class="fas fa-sign-in-alt"></i> Login untuk Daftar
                        </a>
                    <?php elseif ($is_enrolled): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> Anda sudah terdaftar di kursus ini.
                        </div>
                        <a href="courses.php" class="btn btn-primary w-100">
                            <i class="fas fa-book"></i> Lihat Kursus Saya
                        </a>
                    <?php else: ?>
                        <button type="button" class="btn btn-success w-100" id="btnDaftarKursus" 
                                data-course-id="<?php echo $id_kursus; ?>"
                                data-course-name="<?php echo htmlspecialchars($kursus['nama_kursus']); ?>">
                            <i class="fas fa-plus"></i> Daftar Kursus
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi -->
<div class="modal fade" id="modalKonfirmasi" tabindex="-1" aria-labelledby="modalKonfirmasiLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalKonfirmasiLabel">Konfirmasi Pendaftaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin mendaftar kursus <strong id="namaKursusModal"></strong>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btnKonfirmasiDaftar">Daftar Sekarang</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnDaftarKursus = document.getElementById('btnDaftarKursus');
    const modalKonfirmasi = new bootstrap.Modal(document.getElementById('modalKonfirmasi'));
    const btnKonfirmasiDaftar = document.getElementById('btnKonfirmasiDaftar');
    const namaKursusModal = document.getElementById('namaKursusModal');
    
    let courseId = null;
    
    // Fungsi untuk menampilkan notifikasi
    function showNotification(message, type = 'success') {
        const notificationContainer = document.getElementById('notification-container');
        const alertClass = type === 'success' ? 'alert-success' : 
                          type === 'error' ? 'alert-danger' : 
                          type === 'warning' ? 'alert-warning' : 'alert-info';
        
        const iconClass = type === 'success' ? 'fas fa-check-circle' :
                         type === 'error' ? 'fas fa-times-circle' :
                         type === 'warning' ? 'fas fa-exclamation-triangle' : 'fas fa-info-circle';
        
        const notification = document.createElement('div');
        notification.className = `alert ${alertClass} alert-dismissible fade show`;
        notification.style.minWidth = '300px';
        notification.style.marginBottom = '10px';
        notification.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="${iconClass} me-2"></i>
                <div>${message}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        
        notificationContainer.appendChild(notification);
        
        // Auto-dismiss after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);
    }
    
    // Event listener untuk tombol daftar kursus
    if (btnDaftarKursus) {
        btnDaftarKursus.addEventListener('click', function() {
            courseId = this.dataset.courseId;
            const courseName = this.dataset.courseName;
            namaKursusModal.textContent = courseName;
            modalKonfirmasi.show();
        });
    }
    
    // Event listener untuk tombol konfirmasi
    if (btnKonfirmasiDaftar) {
        btnKonfirmasiDaftar.addEventListener('click', function() {
            if (!courseId) return;
            
            // Disable button and show loading
            this.disabled = true;
            this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...';
            
            // Create form data
            const formData = new FormData();
            formData.append('id_kursus', courseId);
            
            // Send AJAX request
            fetch('../../aksi/aksi_tambah_kursus_mahasiswa.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(html => {
                modalKonfirmasi.hide();
                
                // Parse the HTML response to extract message
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = html;
                const alertDiv = tempDiv.querySelector('.alert');
                
                if (alertDiv) {
                    const message = alertDiv.textContent.trim();
                    let type = 'info';
                    
                    if (alertDiv.classList.contains('alert-success')) {
                        type = 'success';
                        showNotification('Kursus berhasil ditambahkan! Anda telah berhasil mendaftar kursus ini.', 'success');
                        // Auto refresh after success
                        setTimeout(() => {
                            location.reload();
                        }, 2000);
                    } else if (alertDiv.classList.contains('alert-danger')) {
                        type = 'error';
                        showNotification(message, 'error');
                    } else if (alertDiv.classList.contains('alert-warning')) {
                        type = 'warning';
                        showNotification(message, 'warning');
                    } else {
                        showNotification(message, 'info');
                    }
                } else {
                    showNotification('Terjadi kesalahan saat mendaftar kursus', 'error');
                }
                
                // Re-enable button
                btnKonfirmasiDaftar.disabled = false;
                btnKonfirmasiDaftar.innerHTML = 'Daftar Sekarang';
                
            })
            .catch(error => {
                console.error('Error:', error);
                modalKonfirmasi.hide();
                showNotification('Terjadi kesalahan saat mendaftar kursus', 'error');
                
                // Re-enable button
                btnKonfirmasiDaftar.disabled = false;
                btnKonfirmasiDaftar.innerHTML = 'Daftar Sekarang';
            });
        });
    }
});
</script>

<?php include '../../template/footer.php'; ?>

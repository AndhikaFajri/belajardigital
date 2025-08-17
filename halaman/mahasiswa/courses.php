<?php
require_once '../../konfigurasi/koneksi.php';
include '../../template/header.php';

// Debug: Cek session
error_reporting(E_ALL);
ini_set('display_errors', 0); // Matikan display errors untuk production

// Ambil data kursus yang diambil mahasiswa
$student_id = $_SESSION['user_id'];
$my_courses = [];
$available_courses = [];
$error_message = '';

try {
    // Query untuk Kursus Saya - kursus yang sudah diambil
    $query_my_courses = "SELECT k.*, p.tanggal_daftar as enrollment_date, p.status as progress_status 
                        FROM kursus k 
                        JOIN pendaftaran p ON k.id_kursus = p.id_kursus 
                        WHERE p.id_pengguna = ? AND k.status = 'Aktif'
                        ORDER BY p.tanggal_daftar DESC";
    
    $stmt_my = $conn->prepare($query_my_courses);
    if (!$stmt_my) {
        throw new Exception("Query preparation failed: " . $conn->error);
    }
    
    $stmt_my->bind_param("i", $student_id);
    $stmt_my->execute();
    $result_my = $stmt_my->get_result();
    $my_courses = $result_my->fetch_all(MYSQLI_ASSOC);
    
    // Query untuk Kursus Tersedia - kursus yang belum diambil
    $query_available = "SELECT k.* FROM kursus k 
                       WHERE k.status = 'Aktif' 
                       AND k.id_kursus NOT IN (
                           SELECT id_kursus FROM pendaftaran 
                           WHERE id_pengguna = ?
                       )
                       ORDER BY k.tanggal_buat DESC";
    
    $stmt_available = $conn->prepare($query_available);
    if (!$stmt_available) {
        throw new Exception("Query preparation failed: " . $conn->error);
    }
    
    $stmt_available->bind_param("i", $student_id);
    $stmt_available->execute();
    $result_available = $stmt_available->get_result();
    $available_courses = $result_available->fetch_all(MYSQLI_ASSOC);
    
} catch (Exception $e) {
    $error_message = "Error: " . $e->getMessage();
}
?>

<div class="container mt-4">
    <h2>Manajemen Kursus</h2>
    
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" id="coursesTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="my-courses-tab" data-bs-toggle="tab" data-bs-target="#my-courses" 
                    type="button" role="tab" aria-controls="my-courses" aria-selected="true">
                <i class="fas fa-book me-2"></i>Kursus Saya
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="available-courses-tab" data-bs-toggle="tab" data-bs-target="#available-courses" 
                    type="button" role="tab" aria-controls="available-courses" aria-selected="false">
                <i class="fas fa-graduation-cap me-2"></i>Kursus Tersedia
            </button>
        </li>
    </ul>
    
    <!-- Tab content -->
    <div class="tab-content mt-4" id="coursesTabContent">
        <!-- Kursus Saya -->
        <div class="tab-pane fade show active" id="my-courses" role="tabpanel" aria-labelledby="my-courses-tab">
            <div class="row">
                <?php if (!empty($my_courses)): ?>
                    <?php foreach($my_courses as $course): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 course-card">
                                <img src="<?php echo !empty($course['gambar']) ? '../../' . htmlspecialchars($course['gambar']) : 'https://via.placeholder.com/400x200/2563eb/ffffff?text=' . urlencode(substr($course['nama_kursus'], 0, 20)); ?>" 
                                     class="card-img-top" alt="<?= htmlspecialchars($course['nama_kursus']) ?>" 
                                     style="height: 180px; object-fit: cover;"
                                     onerror="this.src='https://via.placeholder.com/400x200/2563eb/ffffff?text=Kursus'">
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($course['nama_kursus']) ?></h5>
                                    <p class="card-text"><?= htmlspecialchars(substr($course['deskripsi'], 0, 100)) ?>...</p>
                                    <p class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>Enroll: <?= date('d M Y', strtotime($course['enrollment_date'])) ?>
                                        <br>
                                        <i class="fas fa-clock me-1"></i><?= $course['durasi'] ?> jam
                                        <br>
                                        <i class="fas fa-tag me-1"></i><?= $course['kategori'] ?>
                                    </p>
                                    
                                    <div class="progress mb-3">
                                        <div class="progress-bar" role="progressbar" 
                                             style="width: 0%" 
                                             aria-valuenow="0" 
                                             aria-valuemin="0" aria-valuemax="100">
                                            0%
                                        </div>
                                    </div>
                                    
                                    <a href="course_detail.php?id=<?= $course['id_kursus'] ?>" class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i>Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Anda belum mengambil kursus apapun. Silakan cek kursus tersedia di tab berikutnya!
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Kursus Tersedia -->
        <div class="tab-pane fade" id="available-courses" role="tabpanel" aria-labelledby="available-courses-tab">
            <div class="row">
                <?php if (!empty($available_courses)): ?>
                    <?php foreach($available_courses as $course): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 course-card">
                                <img src="<?php echo !empty($course['gambar']) ? '../../' . htmlspecialchars($course['gambar']) : 'https://via.placeholder.com/400x200/2563eb/ffffff?text=' . urlencode(substr($course['nama_kursus'], 0, 20)); ?>" 
                                     class="card-img-top" alt="<?= htmlspecialchars($course['nama_kursus']) ?>" 
                                     style="height: 180px; object-fit: cover;"
                                     onerror="this.src='https://via.placeholder.com/400x200/2563eb/ffffff?text=Kursus'">
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($course['nama_kursus']) ?></h5>
                                    <p class="card-text"><?= htmlspecialchars(substr($course['deskripsi'], 0, 100)) ?>...</p>
                                    <p class="text-muted">
                                        <i class="fas fa-clock me-1"></i><?= $course['durasi'] ?> jam
                                        <br>
                                        <i class="fas fa-tag me-1"></i><?= $course['kategori'] ?>
                                    </p>
                                    
                                    <div class="d-flex justify-content-between">
                                        <a href="course_detail.php?id=<?= $course['id_kursus'] ?>" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-info-circle me-1"></i>Detail
                                        </a>
                                        <button class="btn btn-success btn-sm enroll-btn" 
                                                data-course-id="<?= $course['id_kursus'] ?>"
                                                data-course-name="<?= htmlspecialchars($course['nama_kursus']) ?>">
                                            <i class="fas fa-plus me-1"></i>Ambil Kursus
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            <?php if (empty($my_courses)): ?>
                                Tidak ada kursus yang tersedia saat ini. Silakan hubungi admin untuk menambahkan kursus baru.
                            <?php else: ?>
                                Selamat! Anda sudah mengambil semua kursus yang tersedia.
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk hasil pendaftaran -->
<div class="modal fade" id="enrollmentModal" tabindex="-1" aria-labelledby="enrollmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="enrollmentModalLabel">Status Pendaftaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="enrollmentResult">
                <!-- Hasil akan dimuat di sini -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle course enrollment
    const enrollButtons = document.querySelectorAll('.enroll-btn');
    
    enrollButtons.forEach(button => {
        button.addEventListener('click', function() {
            const courseId = this.dataset.courseId;
            const courseName = this.dataset.courseName;
            
            // Show confirmation
            if (confirm(`Apakah Anda yakin ingin mendaftar kursus "${courseName}"?`)) {
                enrollCourse(courseId, this);
            }
        });
    });
    
    function enrollCourse(courseId, button) {
        // Disable button and show loading
        button.disabled = true;
        button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...';
        
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
            // Update modal content
            document.getElementById('enrollmentResult').innerHTML = html;
            
            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('enrollmentModal'));
            modal.show();
            
            // Re-enable button
            button.disabled = false;
            button.innerHTML = '<i class="fas fa-plus me-1"></i>Ambil Kursus';
            
            // Auto refresh page after success
            if (html.includes('alert-success')) {
                setTimeout(() => {
                    location.reload();
                }, 2000);
            }
            
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mendaftar kursus');
            button.disabled = false;
            button.innerHTML = '<i class="fas fa-plus me-1"></i>Ambil Kursus';
        });
    }
});
</script>

<?php include '../../template/footer.php'; ?>

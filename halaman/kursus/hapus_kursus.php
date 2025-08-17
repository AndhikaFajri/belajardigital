<?php
session_start();
require_once '../../../konfigurasi/koneksi.php';

// Ambil data kursus berdasarkan ID
if (!isset($_GET['id'])) {
    header("Location: ../manage_courses.php");
    exit();
}

$course_id = $_GET['id'];
$query = "SELECT * FROM kursus WHERE id_kursus = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $course_id);
$stmt->execute();
$result = $stmt->get_result();
$course = $result->fetch_assoc();

if (!$course) {
    header("Location: ../manage_courses.php");
    exit();
}

include '../../../template/header.php';
$title = "Hapus Kursus";
?>

<div class="container mt-4">
    <h2>Konfirmasi Hapus Kursus</h2>
    
    <div class="alert alert-danger">
        <h4><i class="fas fa-exclamation-triangle"></i> Peringatan!</h4>
        <p>Anda yakin ingin menghapus kursus <strong><?= htmlspecialchars($course['nama_kursus']) ?></strong>?</p>
        <p>Tindakan ini tidak dapat dibatalkan dan semua data terkait kursus ini akan dihapus.</p>
    </div>
    
    <form action="../../../aksi/aksi_hapus_kursus.php" method="POST">
        <input type="hidden" name="id_kursus" value="<?= $course['id_kursus'] ?>">
        
        <button type="submit" class="btn btn-danger" name="hapus_kursus">Ya, Hapus Kursus</button>
        <a href="../manage_courses.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

<?php include '../../../template/footer.php'; ?>

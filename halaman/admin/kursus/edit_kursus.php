<?php
session_start();
require_once '../../../konfigurasi/koneksi.php';
include '../../../template/header.php';

$title = "Edit Kursus";

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
?>

<div class="container mt-4">
    <h2>Edit Kursus</h2>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $_SESSION['error']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    
    <form action="../../../aksi/aksi_edit_kursus.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id_kursus" value="<?= $course['id_kursus'] ?>">
        
        <div class="mb-3">
            <label for="nama_kursus" class="form-label">Nama Kursus</label>
            <input type="text" class="form-control" id="nama_kursus" name="nama_kursus" 
                   value="<?= htmlspecialchars($course['nama_kursus']) ?>" required>
        </div>
        
        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4" required><?= htmlspecialchars($course['deskripsi']) ?></textarea>
        </div>
        
        <div class="mb-3">
            <label for="kategori" class="form-label">Kategori</label>
            <select class="form-control" id="kategori" name="kategori" required>
                <option value="pemrograman" <?= $course['kategori'] == 'pemrograman' ? 'selected' : '' ?>>Pemrograman</option>
                <option value="database" <?= $course['kategori'] == 'database' ? 'selected' : '' ?>>Database</option>
                <option value="design" <?= $course['kategori'] == 'design' ? 'selected' : '' ?>>Design</option>
                <option value="marketing" <?= $course['kategori'] == 'marketing' ? 'selected' : '' ?>>Marketing</option>
                <option value="bisnis" <?= $course['kategori'] == 'bisnis' ? 'selected' : '' ?>>Bisnis</option>
            </select>
        </div>
        
        <div class="mb-3">
            <label for="harga" class="form-label">Harga (Rp)</label>
            <input type="number" class="form-control" id="harga" name="harga" 
                   value="<?= $course['harga'] ?>" min="0" required>
        </div>
        
        <div class="mb-3">
            <label for="durasi" class="form-label">Durasi (jam)</label>
            <input type="number" class="form-control" id="durasi" name="durasi" 
                   value="<?= $course['durasi'] ?>" min="1" required>
        </div>
        
        <div class="mb-3">
            <label for="gambar" class="form-label">Gambar Kursus (Kosongkan jika tidak ingin mengubah)</label>
            <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*">
            <?php if ($course['gambar']): ?>
                <div class="mt-2">
                    <small class="text-muted">Gambar saat ini:</small><br>
                    <img src="../../../<?= $course['gambar'] ?>" alt="Gambar Kursus" style="max-width: 200px; max-height: 150px;" class="img-thumbnail">
                    <input type="hidden" name="gambar_lama" value="<?= $course['gambar'] ?>">
                </div>
            <?php endif; ?>
        </div>
        
        <button type="submit" class="btn btn-primary">Update Kursus</button>
        <a href="../manage_courses.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>

<?php include '../../../template/footer.php'; ?>

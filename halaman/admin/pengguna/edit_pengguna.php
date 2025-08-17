<?php
$title = "Edit Pengguna - BelajarDigital";
include '../../../template/header.php';
include '../../../konfigurasi/koneksi.php';

// Cek apakah ID pengguna ada
if(isset($_GET['id'])) {
    $id_pengguna = $_GET['id'];
    
    // Query untuk mendapatkan data pengguna berdasarkan ID
    $query = "SELECT * FROM pengguna WHERE id_pengguna = $id_pengguna";
    $result = mysqli_query($conn, $query);
    
    if(mysqli_num_rows($result) == 1) {
        $pengguna = mysqli_fetch_assoc($result);
    } else {
        echo "<script>alert('Data pengguna tidak ditemukan!'); window.location='../manage_users.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('ID pengguna tidak valid!'); window.location='../manage_users.php';</script>";
    exit();
}
?>

<div class="container mt-4">
    <h1 class="text-center">Edit Pengguna</h1>
    
    <form action="../../../aksi/aksi_edit_pengguna.php" method="POST">
        <input type="hidden" name="id_pengguna" value="<?php echo $pengguna['id_pengguna']; ?>">
        
        <div class="mb-3">
            <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" 
                   value="<?php echo htmlspecialchars($pengguna['nama_lengkap']); ?>" required>
        </div>
        
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" 
                   value="<?php echo htmlspecialchars($pengguna['email']); ?>" required>
        </div>
        
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" 
                   value="<?php echo htmlspecialchars($pengguna['username']); ?>" required>
        </div>
        
        <div class="mb-3">
            <label for="password" class="form-label">Password (Kosongkan jika tidak ingin mengubah)</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>
        
        <div class="mb-3">
            <label for="level" class="form-label">Level</label>
            <select class="form-select" id="level" name="level" required>
                <option value="admin" <?php echo $pengguna['level'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                <option value="pengajar" <?php echo $pengguna['level'] == 'pengajar' ? 'selected' : ''; ?>>Pengajar</option>
                <option value="mahasiswa" <?php echo $pengguna['level'] == 'mahasiswa' ? 'selected' : ''; ?>>Mahasiswa</option>
            </select>
        </div>
        
        <button type="submit" class="btn btn-primary">Update Pengguna</button>
        <a href="../manage_users.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

<?php include '../../../template/footer.php'; ?>

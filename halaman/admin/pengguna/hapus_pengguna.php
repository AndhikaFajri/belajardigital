<?php
$title = "Hapus Pengguna - BelajarDigital";
include '../../../template/header.php';
include '../../../konfigurasi/koneksi.php';

// Cek apakah ID pengguna ada
if(isset($_GET['id'])) {
    $id_pengguna = (int)$_GET['id'];
    
    if($id_pengguna <= 0) {
        echo "<script>alert('ID pengguna tidak valid!'); window.location='../manage_users.php';</script>";
        exit();
    }
    
    // Gunakan prepared statement untuk keamanan
    $stmt = $conn->prepare("SELECT * FROM pengguna WHERE id_pengguna = ?");
    $stmt->bind_param("i", $id_pengguna);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result->num_rows == 1) {
        $pengguna = $result->fetch_assoc();
    } else {
        echo "<script>alert('Data pengguna tidak ditemukan!'); window.location='../manage_users.php';</script>";
        exit();
    }
    
    $stmt->close();
} else {
    echo "<script>alert('ID pengguna tidak valid!'); window.location='../manage_users.php';</script>";
    exit();
}
?>

<div class="container mt-4">
    <h1 class="text-center">Konfirmasi Hapus Pengguna</h1>
    
    <div class="alert alert-danger">
        <h4>Peringatan!</h4>
        <p>Anda yakin ingin menghapus pengguna berikut?</p>
    </div>
    
    <table class="table">
        <tr>
            <th>ID Pengguna</th>
            <td><?php echo $pengguna['id_pengguna']; ?></td>
        </tr>
        <tr>
            <th>Nama Lengkap</th>
            <td><?php echo htmlspecialchars($pengguna['nama_lengkap']); ?></td>
        </tr>
        <tr>
            <th>Email</th>
            <td><?php echo htmlspecialchars($pengguna['email']); ?></td>
        </tr>
        <tr>
            <th>Username</th>
            <td><?php echo htmlspecialchars($pengguna['username']); ?></td>
        </tr>
        <tr>
            <th>Level</th>
            <td><?php echo htmlspecialchars($pengguna['level']); ?></td>
        </tr>
    </table>
    
    <form action="../../../aksi/aksi_hapus_pengguna.php" method="POST" id="formHapus">
        <input type="hidden" name="id_pengguna" value="<?php echo $pengguna['id_pengguna']; ?>">
        <button type="submit" class="btn btn-danger" id="btnHapus">
            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
            Ya, Hapus Pengguna
        </button>
        <a href="../manage_users.php" class="btn btn-secondary">Batal</a>
    </form>
    
    <script>
    document.getElementById('formHapus').addEventListener('submit', function(e) {
        var btnHapus = document.getElementById('btnHapus');
        var spinner = btnHapus.querySelector('.spinner-border');
        
        btnHapus.disabled = true;
        spinner.classList.remove('d-none');
    });
    </script>
</div>

<?php include '../../../template/footer.php'; ?>

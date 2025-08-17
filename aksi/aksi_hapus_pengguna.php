<?php
include '../konfigurasi/koneksi.php';

// Cek apakah form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil dan validasi ID pengguna
    $id_pengguna = isset($_POST['id_pengguna']) ? (int)$_POST['id_pengguna'] : 0;
    
    if ($id_pengguna <= 0) {
        echo "<script>alert('ID pengguna tidak valid!'); window.location='../halaman/admin/manage_users.php';</script>";
        exit();
    }
    
    // Gunakan prepared statement untuk keamanan
    $stmt = $conn->prepare("SELECT * FROM pengguna WHERE id_pengguna = ?");
    $stmt->bind_param("i", $id_pengguna);
    $stmt->execute();
    $result_pengguna = $stmt->get_result();
    
    if ($result_pengguna->num_rows == 0) {
        echo "<script>alert('Pengguna tidak ditemukan!'); window.location='../halaman/admin/manage_users.php';</script>";
        exit();
    }
    
    $pengguna = $result_pengguna->fetch_assoc();
    
    // Cek apakah pengguna yang akan dihapus adalah admin terakhir
    $stmt_admin = $conn->prepare("SELECT COUNT(*) as total_admin FROM pengguna WHERE level = 'admin'");
    $stmt_admin->execute();
    $result_admin = $stmt_admin->get_result();
    $data_admin = $result_admin->fetch_assoc();
    
    if ($pengguna['level'] == 'admin' && $data_admin['total_admin'] <= 1) {
        echo "<script>alert('Tidak dapat menghapus admin terakhir!'); window.location='../halaman/admin/manage_users.php';</script>";
        exit();
    }
    
    // Hapus pengguna dari database dengan prepared statement
    $stmt_delete = $conn->prepare("DELETE FROM pengguna WHERE id_pengguna = ?");
    $stmt_delete->bind_param("i", $id_pengguna);
    
    if ($stmt_delete->execute()) {
        echo "<script>
            alert('Pengguna berhasil dihapus!');
            window.location='../halaman/admin/manage_users.php';
        </script>";
    } else {
        echo "<script>
            alert('Gagal menghapus pengguna: " . addslashes($conn->error) . "');
            window.location='../halaman/admin/manage_users.php';
        </script>";
    }
    
    $stmt->close();
    $stmt_admin->close();
    $stmt_delete->close();
} else {
    echo "<script>alert('Akses tidak valid!'); window.location='../manage_users.php';</script>";
}

mysqli_close($conn);
?>

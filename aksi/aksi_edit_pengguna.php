<?php
include '../konfigurasi/koneksi.php';

// Cek apakah form disubmit
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil dan validasi data dari form
    $id_pengguna = isset($_POST['id_pengguna']) ? (int)$_POST['id_pengguna'] : 0;
    $nama_lengkap = isset($_POST['nama_lengkap']) ? trim($_POST['nama_lengkap']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $level = isset($_POST['level']) ? $_POST['level'] : '';
    
    // Validasi data
    if(empty($nama_lengkap) || empty($email) || empty($username) || empty($level)) {
        echo "<script>alert('Semua field harus diisi!'); window.location='../halaman/admin/pengguna/edit_pengguna.php?id=$id_pengguna';</script>";
        exit();
    }
    
    // Validasi format email
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Format email tidak valid!'); window.location='../halaman/admin/pengguna/edit_pengguna.php?id=$id_pengguna';</script>";
        exit();
    }
    
    // Cek apakah email sudah digunakan oleh pengguna lain
    $stmt_email = $conn->prepare("SELECT * FROM pengguna WHERE email = ? AND id_pengguna != ?");
    $stmt_email->bind_param("si", $email, $id_pengguna);
    $stmt_email->execute();
    $result_email = $stmt_email->get_result();
    
    if($result_email->num_rows > 0) {
        echo "<script>alert('Email sudah digunakan oleh pengguna lain!'); window.location='../halaman/admin/pengguna/edit_pengguna.php?id=$id_pengguna';</script>";
        exit();
    }
    
    // Cek apakah username sudah digunakan oleh pengguna lain
    $stmt_username = $conn->prepare("SELECT * FROM pengguna WHERE username = ? AND id_pengguna != ?");
    $stmt_username->bind_param("si", $username, $id_pengguna);
    $stmt_username->execute();
    $result_username = $stmt_username->get_result();
    
    if($result_username->num_rows > 0) {
        echo "<script>alert('Username sudah digunakan oleh pengguna lain!'); window.location='../halaman/admin/pengguna/edit_pengguna.php?id=$id_pengguna';</script>";
        exit();
    }
    
    // Update data pengguna dengan prepared statement
    if(!empty($_POST['password'])) {
        // Jika password diubah
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE pengguna SET 
                  nama_lengkap = ?,
                  email = ?,
                  username = ?,
                  password = ?,
                  level = ?
                  WHERE id_pengguna = ?");
        $stmt->bind_param("sssssi", $nama_lengkap, $email, $username, $password, $level, $id_pengguna);
    } else {
        // Jika password tidak diubah
        $stmt = $conn->prepare("UPDATE pengguna SET 
                  nama_lengkap = ?,
                  email = ?,
                  username = ?,
                  level = ?
                  WHERE id_pengguna = ?");
        $stmt->bind_param("ssssi", $nama_lengkap, $email, $username, $level, $id_pengguna);
    }
    
    if($stmt->execute()) {
        echo "<script>alert('Data pengguna berhasil diupdate!'); window.location='../halaman/admin/manage_users.php';</script>";
    } else {
        echo "<script>alert('Gagal mengupdate data pengguna: " . addslashes($conn->error) . "'); window.location='../halaman/admin/pengguna/edit_pengguna.php?id=$id_pengguna';</script>";
    }
    
    $stmt_email->close();
    $stmt_username->close();
    $stmt->close();
} else {
    echo "<script>alert('Akses tidak valid!'); window.location='../halaman/admin/manage_users.php';</script>";
}

mysqli_close($conn);
?>

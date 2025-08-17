<?php
session_start();

// Validasi session admin
if (!isset($_SESSION['user_id']) || $_SESSION['level'] != 'admin') {
    $_SESSION['error'] = "Akses ditolak! Silakan login sebagai admin.";
    header("Location: ../login.php");
    exit();
}

require_once '../konfigurasi/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $nama_lengkap = trim($_POST['nama_lengkap']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $level = $_POST['level'];
    
    // Validasi input
    if (empty($nama_lengkap) || empty($email) || empty($username) || empty($password) || empty($level)) {
        $_SESSION['error'] = "Semua field wajib diisi!";
        header("Location: ../halaman/admin/pengguna/tambah_pengguna.php");
        exit();
    }
    
    // Validasi email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Format email tidak valid!";
        header("Location: ../halaman/admin/pengguna/tambah_pengguna.php");
        exit();
    }
    
    // Cek apakah email sudah ada
    $check_email = "SELECT * FROM pengguna WHERE email = ?";
    $stmt_email = $conn->prepare($check_email);
    $stmt_email->bind_param("s", $email);
    $stmt_email->execute();
    $result_email = $stmt_email->get_result();
    
    if ($result_email->num_rows > 0) {
        $_SESSION['error'] = "Email sudah terdaftar!";
        $stmt_email->close();
        header("Location: ../halaman/admin/pengguna/tambah_pengguna.php");
        exit();
    }
    $stmt_email->close();
    
    // Cek apakah username sudah ada
    $check_username = "SELECT * FROM pengguna WHERE username = ?";
    $stmt_username = $conn->prepare($check_username);
    $stmt_username->bind_param("s", $username);
    $stmt_username->execute();
    $result_username = $stmt_username->get_result();
    
    if ($result_username->num_rows > 0) {
        $_SESSION['error'] = "Username sudah terdaftar!";
        $stmt_username->close();
        header("Location: ../halaman/admin/pengguna/tambah_pengguna.php");
        exit();
    }
    $stmt_username->close();
    
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    
    // Insert data pengguna baru
    $insert_query = "INSERT INTO pengguna (nama_lengkap, email, username, password, level) VALUES (?, ?, ?, ?, ?)";
    $insert_stmt = $conn->prepare($insert_query);
    $insert_stmt->bind_param("sssss", $nama_lengkap, $email, $username, $hashed_password, $level);
    
    if ($insert_stmt->execute()) {
        $_SESSION['success'] = "Pengguna berhasil ditambahkan!";
        header("Location: ../halaman/admin/manage_users.php");
    } else {
        $_SESSION['error'] = "Gagal menambahkan pengguna: " . $insert_stmt->error;
        header("Location: ../halaman/admin/pengguna/tambah_pengguna.php");
    }
    
    $insert_stmt->close();
} else {
    $_SESSION['error'] = "Metode request tidak valid!";
    header("Location: ../halaman/admin/manage_users.php");
}

$conn->close();
exit();
?>

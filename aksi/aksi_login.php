<?php
session_start();
require_once '../konfigurasi/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validasi input
    if (empty($_POST['username']) || empty($_POST['password'])) {
        header("Location: ../login.php?error=empty");
        exit();
    }

    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Query untuk cek user dengan prepared statement
    $query = "SELECT * FROM pengguna WHERE username = ? LIMIT 1";
    $stmt = $conn->prepare($query);
    
    if (!$stmt) {
        error_log("Prepare failed: " . $conn->error);
        header("Location: ../login.php?error=system");
        exit();
    }
    
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        // Verifikasi password
        if (password_verify($password, $user['password'])) {
            // Regenerate session ID untuk keamanan
            session_regenerate_id(true);
            
            // Set session
            $_SESSION['login'] = true;
            $_SESSION['user_id'] = $user['id_pengguna'];
            $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['level'] = $user['level'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['foto_profil'] = $user['foto_profil'];
            
            // Update last login (kolom last_login tidak ada, gunakan tanggal_daftar atau skip)
            // $update_query = "UPDATE pengguna SET last_login = NOW() WHERE id_pengguna = ?";
            // $update_stmt = $conn->prepare($update_query);
            // $update_stmt->bind_param("i", $user['id_pengguna']);
            // $update_stmt->execute();
            
            // Redirect berdasarkan level
            if ($user['level'] == 'admin') {
                header("Location: ../halaman/admin/dashboard.php");
            } else {
                header("Location: ../halaman/mahasiswa/dashboard.php");
            }
            exit();
        } else {
            header("Location: ../login.php?error=invalid");
            exit();
        }
    } else {
        header("Location: ../login.php?error=invalid");
        exit();
    }
    
    // Hapus statement setelah digunakan
    if (isset($stmt)) {
        $stmt->close();
    }
} else {
    header("Location: ../login.php");
    exit();
}
?>

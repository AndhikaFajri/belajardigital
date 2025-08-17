<?php
session_start();

// Validasi session admin
if (!isset($_SESSION['user_id']) || $_SESSION['level'] != 'admin') {
    $_SESSION['error'] = "Akses ditolak! Silakan login sebagai admin.";
    header("Location: ../login.php");
    exit();
}

require_once '../konfigurasi/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_kursus'])) {
    $course_id = (int)$_POST['id_kursus'];
    
    // Ambil data dari form
    $nama_kursus = trim($_POST['nama_kursus']);
    $deskripsi = trim($_POST['deskripsi']);
    $harga = (float)$_POST['harga'];
    
    // Validasi input
    if (empty($nama_kursus) || empty($deskripsi)) {
        $_SESSION['error'] = "Nama kursus dan deskripsi wajib diisi!";
        header("Location: ../halaman/admin/kursus/edit_kursus.php?id=$course_id");
        exit();
    }
    
    // Cek apakah kursus ada
    $check_query = "SELECT * FROM kursus WHERE id_kursus = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("i", $course_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($result->num_rows == 0) {
        $_SESSION['error'] = "Kursus tidak ditemukan!";
        header("Location: ../halaman/admin/manage_courses.php");
        exit();
    }
    
    $course = $result->fetch_assoc();
    
    // Handle file upload jika ada
    $gambar_path = $course['gambar']; // Default ke gambar lama
    
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['gambar']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowed)) {
            // Hapus gambar lama jika ada
            if ($course['gambar'] && file_exists('../' . $course['gambar'])) {
                unlink('../' . $course['gambar']);
            }
            
            // Upload gambar baru
            $new_filename = uniqid() . '.' . $ext;
            $upload_path = '../aset/gambar/courses/' . $new_filename;
            
            if (move_uploaded_file($_FILES['gambar']['tmp_name'], $upload_path)) {
                $gambar_path = 'aset/gambar/courses/' . $new_filename;
            }
        }
    }
    
    // Update data kursus
    $update_query = "UPDATE kursus SET nama_kursus = ?, deskripsi = ?, harga = ?, gambar = ? WHERE id_kursus = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("ssdssi", $nama_kursus, $deskripsi, $harga, $gambar_path, $course_id);
    
    if ($update_stmt->execute()) {
        $_SESSION['success'] = "Kursus berhasil diperbarui!";
    } else {
        $_SESSION['error'] = "Gagal memperbarui kursus: " . $update_stmt->error;
    }
    
    $update_stmt->close();
    $check_stmt->close();
    
    header("Location: ../halaman/admin/manage_courses.php");
    exit();
} else {
    $_SESSION['error'] = "Metode request tidak valid!";
    header("Location: ../halaman/admin/manage_courses.php");
    exit();
}
?>

<?php
session_start();

// Validasi session admin
if (!isset($_SESSION['user_id']) || $_SESSION['level'] != 'admin') {
    $_SESSION['error'] = "Akses ditolak! Silakan login sebagai admin.";
    header("Location: ../halaman/admin/manage_courses.php");
    exit();
}

require_once '../konfigurasi/koneksi.php';

if (isset($_POST['id_kursus'])) {
    $course_id = (int)$_POST['id_kursus'];
    
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
    
    // Cek apakah kursus masih memiliki pendaftaran
    $check_pendaftaran_query = "SELECT COUNT(*) as total FROM pendaftaran WHERE id_kursus = ?";
    $check_pendaftaran_stmt = $conn->prepare($check_pendaftaran_query);
    $check_pendaftaran_stmt->bind_param("i", $course_id);
    $check_pendaftaran_stmt->execute();
    $pendaftaran_result = $check_pendaftaran_stmt->get_result();
    $pendaftaran_row = $pendaftaran_result->fetch_assoc();
    
    if ($pendaftaran_row['total'] > 0) {
        $_SESSION['error'] = "Tidak dapat menghapus kursus yang masih memiliki peserta terdaftar!";
        header("Location: ../halaman/admin/manage_courses.php");
        exit();
    }
    
    // Cek apakah kursus memiliki materi
    $check_materi_query = "SELECT COUNT(*) as total FROM materi WHERE id_kursus = ?";
    $check_materi_stmt = $conn->prepare($check_materi_query);
    $check_materi_stmt->bind_param("i", $course_id);
    $check_materi_stmt->execute();
    $materi_result = $check_materi_stmt->get_result();
    $materi_row = $materi_result->fetch_assoc();
    
    if ($materi_row['total'] > 0) {
        $_SESSION['error'] = "Tidak dapat menghapus kursus yang masih memiliki materi! Hapus semua materi terlebih dahulu.";
        header("Location: ../halaman/admin/manage_courses.php");
        exit();
    }
    
    // Hapus gambar kursus jika ada
    if ($course['gambar'] && file_exists('../' . $course['gambar'])) {
        unlink('../' . $course['gambar']);
    }
    
    // Hapus kursus
    $delete_query = "DELETE FROM kursus WHERE id_kursus = ?";
    $delete_stmt = $conn->prepare($delete_query);
    $delete_stmt->bind_param("i", $course_id);
    
    if ($delete_stmt->execute()) {
        $_SESSION['success'] = "Kursus berhasil dihapus!";
    } else {
        $_SESSION['error'] = "Gagal menghapus kursus: " . $delete_stmt->error;
    }
    
    $delete_stmt->close();
    $check_materi_stmt->close();
    $check_pendaftaran_stmt->close();
    $check_stmt->close();
} else {
    $_SESSION['error'] = "ID kursus tidak valid!";
}

header("Location: ../halaman/admin/manage_courses.php");
exit();
?>

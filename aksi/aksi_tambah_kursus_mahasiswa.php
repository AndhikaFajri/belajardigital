<?php
session_start();
require_once '../konfigurasi/koneksi.php';

// Set header untuk JSON response
header('Content-Type: application/json');

// Validasi login
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Silakan login terlebih dahulu']);
    exit;
}

// Validasi input
if (!isset($_POST['id_kursus']) || empty($_POST['id_kursus'])) {
    echo json_encode(['success' => false, 'message' => 'ID kursus tidak valid']);
    exit;
}

$id_kursus = intval($_POST['id_kursus']);
$id_pengguna = $_SESSION['user_id'];

try {
    // Cek apakah kursus sudah diambil atau belum
    $query_check = "SELECT id_pendaftaran FROM pendaftaran WHERE id_pengguna = ? AND id_kursus = ?";
    $stmt_check = $conn->prepare($query_check);
    $stmt_check->bind_param("ii", $id_pengguna, $id_kursus);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    
    if ($result_check->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Anda sudah terdaftar di kursus ini']);
        exit;
    }
    
    // Cek apakah kursus masih aktif
    $query_kursus = "SELECT status FROM kursus WHERE id_kursus = ? AND status = 'Aktif'";
    $stmt_kursus = $conn->prepare($query_kursus);
    $stmt_kursus->bind_param("i", $id_kursus);
    $stmt_kursus->execute();
    $result_kursus = $stmt_kursus->get_result();
    
    if ($result_kursus->num_rows == 0) {
        echo json_encode(['success' => false, 'message' => 'Kursus tidak tersedia atau tidak aktif']);
        exit;
    }
    
    // Insert pendaftaran baru
    $query_insert = "INSERT INTO pendaftaran (id_pengguna, id_kursus, status) VALUES (?, ?, 'Aktif')";
    $stmt_insert = $conn->prepare($query_insert);
    $stmt_insert->bind_param("ii", $id_pengguna, $id_kursus);
    
    if ($stmt_insert->execute()) {
        echo json_encode(['success' => true, 'message' => 'Kursus berhasil ditambahkan! Anda telah berhasil mendaftar kursus ini.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal mendaftar kursus']);
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan: ' . htmlspecialchars($e->getMessage())]);
} finally {
    // Close statements
    if (isset($stmt_check)) $stmt_check->close();
    if (isset($stmt_kursus)) $stmt_kursus->close();
    if (isset($stmt_insert)) $stmt_insert->close();
}
?>
